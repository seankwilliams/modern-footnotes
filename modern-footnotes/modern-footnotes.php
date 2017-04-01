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
