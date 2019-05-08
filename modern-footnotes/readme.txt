=== Modern Footnotes ===
Contributors: Sean Williams
Tags: footnotes, citations, inline footnotes, inline citations, mobile-friendly citations, mobile-friendly footnotes
Requires at least: 4.4.8
Tested up to: 5.2
Stable tag: 1.3.0
License: GNU General Public License v2
License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html

Add inline footnotes to your posts. On desktop, the footnotes will appear as tooltips. On mobile, the footnote will expand beneath the text.

== Description ==
Footnotes optimized for desktop and mobile, inspired by the styles of Grantland and FiveThirtyEight.

Use a footnote in your post by using the footnote icon in the WordPress editor or by using the shortcode: [mfn]this will be a footnote[/mfn] The plugin will automatically associate sequential numbers with each plugin.

On desktop, footnotes will appear as a tooltip when the user clicks on the number. On mobile, footnotes will expand as a section below the current text.

== Installation ==
1. Upload the modern-footnotes folder to your wp-content/plugins directory.
2. Activate the plugin in the WordPress Plugins section.
3. Use a footnote in your post by using the footnote icon in the WordPress editor or by using the shortcode: [mfn]this will be a footnote[/mfn] 
4. Reference numbers will be automatically assigned sequentially so the first footnote is labeled 1, then the next footnote is 2, then 3, etc. You can specify custom reference numbers by using the "referencenumber" attribute to specify a particular reference number. For example: [mfn referencenumber=3]This will have the number 3 with it.[/mfn] 
5. If you want to customize the styles, you can do so by overriding the following styles in custom CSS for your theme:
.modern-footnotes-footnote - The superscript element displaying the footnote number
.modern-footnotes-footnote--selected - A superscript element that is currently active
.modern-footnotes-footnote__note – Styling that applies to both mobile and desktop footnotes
.modern-footnotes-footnote__note--mobile - The styling for a mobile footnote
.modern-footnotes-footnote__note--desktop - The styling for a desktop footnote

== Frequently Asked Questions ==
=How do I create a footnote?=
Use a footnote in your post by using the footnote icon in the WordPress editor or by using the shortcode: [mfn]this will be a footnote[/mfn]

=Can I make desktop footnotes expand like they do on mobile instead of using tooltips?=
Yes. Go to Settings -> Modern Footnotes and choose the "Expandable footnotes on desktop" option.

=Can I customize the reference numbers output by the plugin?=
Yes. You can specify custom reference numbers by using the "referencenumber" attribute to specify a particular reference number. For example: [mfn referencenumber=3]This will have the number 3 with it.[/mfn] 

=Can I customize the styles of footnotes?=
If you want to customize the styles, you can do so by overriding the following styles in custom CSS for your theme:
.modern-footnotes-footnote - The superscript element displaying the footnote number
.modern-footnotes-footnote--selected - A superscript element that is currently active
.modern-footnotes-footnote__note – Styling that applies to both mobile and desktop footnotes
.modern-footnotes-footnote__note--mobile - The styling for a mobile footnote
.modern-footnotes-footnote__note--desktop - The styling for a desktop footnote

=Is there support for the Block Editor/Gutenberg Editor?=
Yes. You can use the Modern Footnotes button in the toolbar of the Block Editor to move the selected text into a footnote. However, if you want to customize the reference numbers output by the plugin, you'll have to type out the shortcode instead.

== Screenshots ==
1. http://prismtechstudios.com/modern-footnotes/modern-footnotes-1.png
2. http://prismtechstudios.com/modern-footnotes/modern-footnotes-2.png
3. http://prismtechstudios.com/modern-footnotes/modern-footnotes-3.png

== Changelog ==
1.0 - 4/1/17 - initial version.
1.1 - 11/8/17 - Added option to use expandable version of footnotes on desktop instead of the default tooltip style.
1.1.1 - 11/22/17 - Fixed error occuring in PHP versions below 5.3
1.1.2 - 1/6/18 - Fixed problem where scripts were enqueued incorrectly.
1.1.3 - 1/11/18 - Fixed issue where icon was not showing in the admin MCE editor
1.1.4 - 1/27/18 - Fixed issue where footnote numbering wouldn't be sequential when other plugins like TablePress caused shortcodes to render in a different order than they appear in the HTML.
1.2 - 9/14/18 - Stopped using href="#" for <a> elements for increased theme compatibility. Added ability to have a custom shortcode. Added a custom CSS area. Added the ability to manually override citations.
1.2.1 - 10/3/18 - Fixed a problem where footnote would improperly size when it was opened multiple times at the edge of a container
1.2.2 - 10/8/18 - Changed license from LGPL to GPL2.
1.2.3 - 11/1/18 - Tested with WordPress 5.0
1.2.4 - 11/30/18 - Fixed a problem where HTML tags could not be entered inside a footnote.
1.2.5 - 11/30/18 - Fixed issue with footnotes causing line breaks.
1.2.6 - 1/28/19 - Removed footnote shortcodes from rendering in RSS feeds.
1.2.7 - 1/29/19 - Fixed additional shortcode rendering issue in RSS feeds.
1.3.0 - 2/19/19 - Fixed problem where Classic Editor button did not appear in WP 5.x. Added Gutenberg button. Allow shortcode within footnotes.