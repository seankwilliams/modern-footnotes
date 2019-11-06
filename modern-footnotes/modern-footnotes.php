<?php
/*
Plugin Name: Modern Footnotes
Plugin URI:  http://prismtechstudios.com/modern-footnotes
Description: Add inline footnotes to your post via the footnote icon on the toolbar for editing posts and pages. Or, use the [mfn] or [modern_footnote] shortcodes [mfn]like this[/mfn].
Version:     1.3.6
Author:      Prism Tech Studios
Author URI:  http://prismtechstudios.com/
License:     GPL2
License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html
*/

//don't let users call this file directly
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

$modern_footnotes_version = '1.3.6';

$modern_footnotes_options = get_option('modern_footnotes_settings');

$modern_footnotes_used_reference_numbers = array(); //keeps track of what reference numbers have been used

function modern_footnotes_func($atts, $content = "") {
	global $modern_footnotes_used_reference_numbers, $modern_footnotes_options;
	$additional_classes = '';
	if (isset($modern_footnotes_options['use_expandable_footnotes_on_desktop_instead_of_tooltips']) && $modern_footnotes_options['use_expandable_footnotes_on_desktop_instead_of_tooltips']) {
		$additional_classes = 'modern-footnotes-footnote--expands-on-desktop';
	}
	$additional_attributes = '';
	if (isset($atts['referencenumber'])) {
		$display_number = $atts['referencenumber'];
		$additional_attributes = 'refnum="' . $display_number . '"';
	} else if (count($modern_footnotes_used_reference_numbers) == 0) {
		$display_number = 1;
	} else {
		$display_number = max($modern_footnotes_used_reference_numbers) + 1;
	}
  
  $content = do_shortcode($content); // render out any shortcodes within the contents
	
  $content = str_replace('<p>','', $content);
  $content = str_replace('</p>','<br /><br />', $content);
  
	$content = '<sup class="modern-footnotes-footnote ' . $additional_classes . '" data-mfn="' . str_replace('"',"\\\"", $display_number) . '"><a href="javascript:void(0)" ' . $additional_attributes . '>' . $display_number . '</a></sup>' .
				'<span class="modern-footnotes-footnote__note" data-mfn="' . str_replace('"',"\\\"", $display_number) . '">' . $content . '</span>'; //use a block element, not an inline element: otherwise, footnotes with line breaks won't display correctly
	$modern_footnotes_used_reference_numbers[] = $display_number;
	return $content;
}

//reset the footnote counter for every new post
function modern_footnotes_reset_count() {
	global $modern_footnotes_used_reference_numbers;
	$modern_footnotes_used_reference_numbers = array();
}

$modern_footnotes_shortcodes = array('modern_footnote','mfn');
if (isset($modern_footnotes_options['modern_footnotes_custom_shortcode']) && !empty($modern_footnotes_options['modern_footnotes_custom_shortcode'])) {
  $modern_footnotes_shortcodes[] = $modern_footnotes_options['modern_footnotes_custom_shortcode'];
}
foreach ($modern_footnotes_shortcodes as $modern_footnote_shortcode) {
  add_shortcode($modern_footnote_shortcode, 'modern_footnotes_func');
}

add_filter('the_post', 'modern_footnotes_reset_count');

// replace <mfn> HTML tags added by Gutenberg/block editor to [mfn] shortcodes
// When multiple formats are applied, Gutenberg can have multiple <mfn> tags for one footnote, so we'll have to iterate through the text and group sibling tags together (see https://github.com/seankwilliams/modern-footnotes/issues/14)
function modern_footnotes_replace_mfn_tag_with_shortcode( $content ) {
  $content = str_replace('</mfn>','<mfn>',$content); //using [mfn] instead of [/mfn] is intentional here
  $contentParts = explode('<mfn>', $content);
  $contentData = array();
  //$tagsFromPreviousSegment = array();
  $inFootnote = FALSE;
  foreach ($contentParts as $c) {
    $contentData[] = array(
      "content" => $c,
      "inFootnote" => $inFootnote
    );    
    $inFootnote = !$inFootnote;
  }
  $wasInFootnote = FALSE;
  for ($i = 0; $i < count($contentData); $i++) {
    //if this is only opening tags or only closing tags, place it in the footnote
    $replacedString = preg_replace("/<\/?\\w+\\s?\\w?.*?>/ms", "", $contentData[$i]['content']);
    if (strlen($replacedString) === 0 && !$contentData[$i]['inFootnote'] && $wasInFootnote) { // check $wasInFootnote to fix https://github.com/seankwilliams/modern-footnotes/issues/18
      $contentData[$i]['inFootnote'] = TRUE;
    } else {
      $wasInFootnote = $contentData[$i]['inFootnote'];
    }
  }
  $finalContent = '';
  $inFootnote = FALSE;
  foreach ($contentData as $cd) {
    if ($cd['inFootnote'] && !$inFootnote) {
      $inFootnote = TRUE;
      $finalContent .= '[mfn]';
    } else if ($inFootnote && !$cd['inFootnote']) {
      $inFootnote = FALSE;
      $finalContent .= '[/mfn]';
    }
    
    $finalContent .= $cd['content'];
  }
  if ($inFootnote) {
    $finalContent .= '[/mfn]';
  }
  return $finalContent;
}
add_filter( 'the_content', 'modern_footnotes_replace_mfn_tag_with_shortcode' );
 


function modern_footnotes_enqueue_scripts_styles() {
	global $modern_footnotes_options, $modern_footnotes_version;
	wp_enqueue_style('modern_footnotes', plugin_dir_url(__FILE__) . 'styles.min.css', array(), $modern_footnotes_version);
	wp_enqueue_script('modern_footnotes', plugin_dir_url(__FILE__) . 'modern-footnotes.min.js', array('jquery'), $modern_footnotes_version, TRUE); 
	
	if (!is_admin() && isset($modern_footnotes_options['modern_footnotes_custom_css']) && !empty($modern_footnotes_options['modern_footnotes_custom_css'])) {
		wp_add_inline_style( 'modern_footnotes', $modern_footnotes_options['modern_footnotes_custom_css'] );
	}
}

add_action('wp_enqueue_scripts', 'modern_footnotes_enqueue_scripts_styles'); 

//
//modify the admin
//

//create a settings page
function modern_footnotes_menu() {
	add_options_page( 'Modern Footnotes Options', 'Modern Footnotes', 'manage_options', __FILE__, 'modern_footnotes_options' );
}

function modern_footnotes_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	echo '<h1>Modern Footnotes Options</h1>';
	echo '<form method="post" action="options.php">';
	settings_fields('modern_footnotes_settings');
	do_settings_sections(__FILE__);
	submit_button();
	echo '</form>';
	echo '</div>';
}

function modern_footnotes_register_settings() { // whitelist options
	register_setting('modern_footnotes_settings', 'modern_footnotes_settings',
				array(
					'type' => 'boolean',
					'default' => FALSE,
					'sanitize_callback' => 'modern_footnotes_sanitize_callback'
				));
	add_settings_section(
		'modern_footnotes_option_group_section',
		'Modern Footnotes Settings',
		function() { /* do nothing, no HTML needed for section heading */ },
		__FILE__
	);
	add_settings_field(
		'modern_footnotes_use_expandable_footnotes_on_desktop_instead_of_tooltips',
		'Expandable footnotes on desktop',
		'modern_footnotes_use_expandable_footnotes_on_desktop_instead_of_tooltips_element_callback',
		__FILE__,
		'modern_footnotes_option_group_section'
	);
	add_settings_field(
		'modern_footnotes_custom_css',
		'Modern Footnotes Custom CSS',
		'modern_footnotes_custom_css_element_callback',
		__FILE__,
		'modern_footnotes_option_group_section'
	);
	add_settings_field(
		'modern_footnotes_custom_shortcode',
		'Modern Footnotes Custom Shortcode',
		'modern_footnotes_custom_shortcode_element_callback',
		__FILE__,
		'modern_footnotes_option_group_section'
	);
}

function modern_footnotes_sanitize_callback($plugin_options) {  
	global $modern_footnotes_options;
	
	if (isset($plugin_options['modern_footnotes_custom_css']) && !empty($plugin_options['modern_footnotes_custom_css'])) {
		//strip style HTML tags from the custom CSS property
		$plugin_options['modern_footnotes_custom_css'] = preg_replace('/<\/?style.*?>/i', '', $plugin_options['modern_footnotes_custom_css']);
	}
	
	if (isset($plugin_options['modern_footnotes_custom_shortcode']) && !empty($plugin_options['modern_footnotes_custom_shortcode'])) {
		//remove invalid characters from shortcode
		$plugin_options['modern_footnotes_custom_shortcode'] = preg_replace('/[^a-zA-Z0-9-_]/i', '', $plugin_options['modern_footnotes_custom_shortcode']);
		if ((!isset($modern_footnotes_options['modern_footnotes_custom_shortcode']) || $modern_footnotes_options['modern_footnotes_custom_shortcode'] != $plugin_options['modern_footnotes_custom_shortcode']) &&
			  shortcode_exists($plugin_options['modern_footnotes_custom_shortcode'])) {
			add_settings_error( 'modern_footnotes_custom_shortcode', 'shortcode-in-use', 'The shortcode "' . $plugin_options['modern_footnotes_custom_shortcode'] . '" is already in use, please enter a different one' );
			$plugin_options['modern_footnotes_custom_shortcode'] = '';
		}
	}
	return $plugin_options;
}

function modern_footnotes_use_expandable_footnotes_on_desktop_instead_of_tooltips_element_callback() {
	global $modern_footnotes_options;
	
	$html = '<input type="checkbox" id="use_expandable_footnotes_on_desktop_instead_of_tooltips" name="modern_footnotes_settings[use_expandable_footnotes_on_desktop_instead_of_tooltips]" value="1"' . checked( 1, isset($modern_footnotes_options['use_expandable_footnotes_on_desktop_instead_of_tooltips']) && $modern_footnotes_options['use_expandable_footnotes_on_desktop_instead_of_tooltips'], FALSE ) . '/>';
	$html .= '<label for="use_expandable_footnotes_on_desktop_instead_of_tooltips">Use expandable footnotes on desktop insetad of the default tooltip style</label>';

	echo $html;
}

function modern_footnotes_custom_css_element_callback() {
	global $modern_footnotes_options;
	
	$html = '<textarea id="modern_footnotes_custom_css" name="modern_footnotes_settings[modern_footnotes_custom_css]" style="max-width:100%;width:400px;height:200px">' . (isset($modern_footnotes_options['modern_footnotes_custom_css']) ? $modern_footnotes_options['modern_footnotes_custom_css'] : '') . '</textarea>';
	$html .= '<label for="modern_footnotes_custom_css">Enter any custom CSS for the plugin, without any &lt;style&gt; tags.</label>';

	echo $html;
}

function modern_footnotes_custom_shortcode_element_callback() {
	global $modern_footnotes_options;
	
	$html = '<input type="text" id="modern_footnotes_custom_shortcode" name="modern_footnotes_settings[modern_footnotes_custom_shortcode]" value="' . (isset($modern_footnotes_options['modern_footnotes_custom_shortcode']) ? $modern_footnotes_options['modern_footnotes_custom_shortcode'] : '') . '" />';
	$html .= '<label for="modern_footnotes_custom_shortcode">Custom shortcode if you\'d like to use something other than [mfn] or [modern_footnote]. Enter the shortcode without the brackets.</label>';

	echo $html;
}

function modern_footnotes_return_blank_for_rss_func($atts, $content = "") {
  return "";
}

// remove shortcode from RSS feed
function modern_footnotes_remove_from_rss_feed($content){
  foreach ($GLOBALS['modern_footnotes_shortcodes'] as $modern_footnote_shortcode) {
    remove_shortcode($modern_footnote_shortcode);
    add_shortcode($modern_footnote_shortcode, 'modern_footnotes_return_blank_for_rss_func');
  }
  return $content;
}
add_filter('the_excerpt_rss', 'modern_footnotes_remove_from_rss_feed');
add_filter('the_content_feed', 'modern_footnotes_remove_from_rss_feed');


if (is_admin()) { // admin actions
	add_action( 'admin_menu', 'modern_footnotes_menu' );
	add_action( 'admin_init', 'modern_footnotes_register_settings' );
}

//setup button on the WordPress editor

//
// Pre-Gutenberg editor
//
function modern_footnotes_add_container_button() {
  if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
    return;
  if ( get_user_option('rich_editing') == 'true') {
    add_filter('mce_external_plugins', 'modern_footnotes_add_container_plugin');
    add_filter('mce_buttons', 'modern_footnotes_register_container_button');
  }
}
if (is_admin()) { 
  add_filter('init', 'modern_footnotes_add_container_button');
  
  function modern_footnotes_enqueue_admin_scripts() {
    global $modern_footnotes_version;
    wp_enqueue_style('modern_footnotes', plugin_dir_url(__FILE__) . 'styles.mce-button.min.css', array(), $modern_footnotes_version);
  }

  add_action('admin_enqueue_scripts', 'modern_footnotes_enqueue_admin_scripts'); 
}


function modern_footnotes_register_container_button($buttons) {
  array_push($buttons, "modern_footnotes");
  return $buttons;
}

function modern_footnotes_add_container_plugin($plugin_array) {
  $plugin_array['modern_footnotes'] = plugin_dir_url(__FILE__) . 'modern-footnotes.mce-button.min.js';
  return $plugin_array;
}
//
// End Pre-Gutenberg editor
//

// 
// Gutenberg / Block Editor 
//
function modern_footnotes_block_editor_button() {
    global $modern_footnotes_version;
    wp_enqueue_script( 'modern_footnotes_block_editor_js',
        plugin_dir_url(__FILE__) . 'modern-footnotes.block-editor.min.js',
        array( 'wp-rich-text', 'wp-element', 'wp-editor' ),
        $modern_footnotes_version
    );
    wp_enqueue_style('modern_footnotes_block_editor_css', plugin_dir_url(__FILE__) . 'styles.block-editor-button.min.css', array(), $modern_footnotes_version);
}
add_action( 'enqueue_block_editor_assets', 'modern_footnotes_block_editor_button' );
//
// End Gutenberg / Block Editor
//
