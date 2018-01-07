<?php
/*
Plugin Name: Modern Footnotes
Plugin URI:  http://prismtechstudios.com/modern-footnotes
Description: Add inline footnotes to your post via the footnote icon on the toolbar for editing posts and pages. Or, use the [mfn] or [modern_footnote] shortcodes [mfn]like this[/mfn].
Version:     1.1.1
Author:      Prism Tech Studios
Author URI:  http://prismtechstudios.com/
License:     Lesser GPL3
License URI: https://www.gnu.org/licenses/lgpl-3.0.en.html
*/

//don't let users call this file directly
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

$modern_footnotes_count = 1;

function modern_footnotes_func($atts, $content = "") {
	global $modern_footnotes_count;
	$additional_classes = '';
	$settings = get_option('modern_footnotes_settings');
	if (isset($settings['use_expandable_footnotes_on_desktop_instead_of_tooltips']) && $settings['use_expandable_footnotes_on_desktop_instead_of_tooltips']) {
		$additional_classes = 'modern-footnotes-footnote--expands-on-desktop';
	}
	$content = '<sup class="modern-footnotes-footnote ' . $additional_classes . '"><a href="#">' . $modern_footnotes_count . '</a></sup>' .
				'<span class="modern-footnotes-footnote__note">' . $content . '</span>';
	$modern_footnotes_count++;
	return $content;
}

//reset the footnote counter for every new post
function modern_footnotes_reset_count() {
	global $modern_footnotes_count;
	$modern_footnotes_count = 1;
}

add_shortcode('modern_footnote', 'modern_footnotes_func');
add_shortcode('mfn', 'modern_footnotes_func');
add_filter('the_post', 'modern_footnotes_reset_count');

addaction('enqueue', 'wpenqueuescripts'); function enqueue() {
	wpenqueue_style('modern_footnotes', plugin_dir_url(__FILE) . 'styles.min.css', array(), '1.1.1');
	wp_enqueue_script('modern_footnotes', plugin_dir_url(__FILE) . 'modern-footnotes.min.js', array('jquery'), '1.1.1', TRUE); 
}

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
}

function modern_footnotes_sanitize_callback($plugin_options) {  
	return $plugin_options;
}

function modern_footnotes_use_expandable_footnotes_on_desktop_instead_of_tooltips_element_callback() {
	$options = get_option('modern_footnotes_settings');
	
	$html = '<input type="checkbox" id="use_expandable_footnotes_on_desktop_instead_of_tooltips" name="modern_footnotes_settings[use_expandable_footnotes_on_desktop_instead_of_tooltips]" value="1"' . checked( 1, isset($options['use_expandable_footnotes_on_desktop_instead_of_tooltips']) && $options['use_expandable_footnotes_on_desktop_instead_of_tooltips'], FALSE ) . '/>';
	$html .= '<label for="use_expandable_footnotes_on_desktop_instead_of_tooltips">Use expandable footnotes on desktop insetad of the default tooltip style</label>';

	echo $html;
}

if (is_admin()) { // admin actions
	add_action( 'admin_menu', 'modern_footnotes_menu' );
	add_action( 'admin_init', 'modern_footnotes_register_settings' );
}

//setup button on the WordPress editor
function modern_footnotes_add_container_button() {
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
		return;
	if ( get_user_option('rich_editing') == 'true') {
		add_filter('mce_external_plugins', 'modern_footnotes_add_container_plugin');
		add_filter('mce_buttons', 'modern_footnotes_register_container_button');
	}
}
if (is_admin()) {
	add_action('init', 'modern_footnotes_add_container_button');
}


function modern_footnotes_register_container_button($buttons) {
	array_push($buttons, "modern_footnotes");
	return $buttons;
}

function modern_footnotes_add_container_plugin($plugin_array) {
	$plugin_array['modern_footnotes'] = plugin_dir_url(__FILE__) . 'modern-footnotes.mce-button.min.js';
	return $plugin_array;
}