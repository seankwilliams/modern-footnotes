<?php
/*
Plugin Name: Modern Footnotes
Plugin URI:  http://prismtechstudios.com/modern-footnotes
Description: Add inline footnotes to your post by enclosing text in double parenthesis, ((like this)).
Version:     1.0
Author:      Prism Tech Studios
Author URI:  https://prismtechstudios.com/
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

function modern_footnotes_activate() {
	try {
		file_get_contents('http://prismtechstudios.com/modern-footnotes/activate.php?' .
							'd=' . urlencode($_SERVER['SERVER_NAME']));
	} catch (Exception $ex) {
		//do nothing
	}
}

function modern_footnotes_deactivate() {
	try {
		file_get_contents('http://prismtechstudios.com/modern-footnotes/deactivate.php?d=' . urlencode($_SERVER['SERVER_NAME']));
	} catch (Exception $ex) {
		//do nothing
	}
}

register_activation_hook(__FILE__, 'modern_footnotes_activate');
register_deactivation_hook(__FILE__, 'modern_footnotes_deactivate');
add_shortcode('modern_footnote', 'modern_footnotes_func');
add_shortcode('mfn', 'modern_footnotes_func');
add_filter('the_post', 'modern_footnotes_reset_count');

wp_enqueue_style('modern_footnotes', plugins_url('/modern-footnotes/styles.css'), array(), '1.0');
wp_enqueue_script('modern_footnotes', plugins_url('/modern-footnotes/modern-footnotes.js'), array('jquery'), '1.0', TRUE);