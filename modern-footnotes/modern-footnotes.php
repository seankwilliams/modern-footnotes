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

function modern_footnotes_content_filter($content){
	$matches = array();
	if (preg_match_all('/\(\(((.|\n)*?)\)\)/', $content, $matches)) {
		for ($i = 0; $i < count($matches[0]); $i++) {
			$replace_with = '<sup class="modern-footnotes-footnote"><a href="#">' . ($i + 1) . '</a></sup>' .
				'<span class="modern-footnotes-footnote__note">' . $matches[1][$i] . '</span>';
			$pos = strpos($content, $matches[0][$i]);
			$content = substr_replace($content, $replace_with, $pos, strlen($matches[0][$i]));
		}
	}
	
	return $content;
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
add_filter( 'the_content', 'modern_footnotes_content_filter');

wp_enqueue_style('modern_footnotes', plugins_url('/modern-footnotes/styles.css'), array(), '1.0');
wp_enqueue_script('modern_footnotes', plugins_url('/modern-footnotes/modern-footnotes.js'), array('jquery'), '1.0', TRUE);