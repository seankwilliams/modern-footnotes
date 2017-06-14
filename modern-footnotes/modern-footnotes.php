<?php
/*
Plugin Name: Modern Footnotes
Plugin URI:  http://prismtechstudios.com/modern-footnotes
Description: Add inline footnotes to your post via the footnote icon on the toolbar for editing posts and pages. Or, use the [mfn] or [modern_footnote] shortcodes [mfn]like this[/mfn].
Version:     1.0.1
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
	$content = '<sup class="modern-footnotes-footnote"><a href="#">' . $modern_footnotes_count . '</a></sup>' .
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

wp_enqueue_style('modern_footnotes', plugin_dir_url(__FILE__) . 'styles.min.css', array(), '1.0.1'); 
wp_enqueue_script('modern_footnotes', plugin_dir_url(__FILE__) . 'modern-footnotes.min.js', array('jquery'), '1.0.1', TRUE);

//modify the admin

function modern_footnotes_add_container_button() {
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
		return;
	if ( get_user_option('rich_editing') == 'true') {
		add_filter('mce_external_plugins', 'modern_footnotes_add_container_plugin');
		add_filter('mce_buttons', 'modern_footnotes_register_container_button');
	}
}
add_action('init', 'modern_footnotes_add_container_button');


function modern_footnotes_register_container_button($buttons) {
	array_push($buttons, "modern_footnotes");
	return $buttons;
}

function modern_footnotes_add_container_plugin($plugin_array) {
	$plugin_array['modern_footnotes'] = plugin_dir_url(__FILE__) . 'modern-footnotes.mce-button.min.js';
	return $plugin_array;
}