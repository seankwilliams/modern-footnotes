=== Modern Footnotes ===
Contributors: Sean Williams
Tags: footnotes, citations, inline footnotes, inline citations, mobile-friendly citations, mobile-friendly footnotes
Requires at least: 4.6
Tested up to: 6.2
Stable tag: 1.4.16
License: GNU General Public License v2
License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html

Add inline footnotes to your posts. On desktop, the footnotes will appear as tooltips. On mobile, the footnote will expand beneath the text. The plugin includes an option to display footnotes at the bottom of posts/pages.

== Description ==
Footnotes optimized for desktop and mobile, inspired by the styles of Grantland and FiveThirtyEight.

Use a footnote in your post by using the footnote icon in the WordPress editor or by using the shortcode: [mfn]this will be a footnote[/mfn] The plugin will automatically associate sequential numbers with each plugin.

On desktop, footnotes will appear as a tooltip when the user clicks on the number. On mobile, footnotes will expand as a section below the current text.

You can also use the [mfn_list] shortcode to display a list of footnotes used in the article.

The official GitHub repository is at <a href="https://github.com/seankwilliams/modern-footnotes" target="_blank">https://github.com/seankwilliams/modern-footnotes</a>

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

== Shortcode options ==
You can modify some behaviours or styles of your footnotes by using the following options within our shortcode.
[mfn referencenumber=3]This footnote will have the number 3[/mfn] 
[mfn class='my-pretty-class']This footnote will have 'my-pretty-class' as additional class, allowing for custom styling of individual footnotes.[/mfn]
[mfn referencereset='true']This footnote will reset the footnote counter and therfore receive 1 as its number. Following footnotes will also receive their number according to this new start.[/mfn]


== Frequently Asked Questions ==
=How do I create a footnote?=
Use a footnote in your post by using the footnote icon in the WordPress editor or by using the shortcode: [mfn]this will be a footnote[/mfn]

=Can the footnotes also be listed at the bottom of the post?=
Yes. Go to Settings -> Modern Footnotes and choose the "Display footnote list at bottom of posts" option. Or, if you only want the footnotes at the bottom of the post when printing, choose the "When printing, list footnotes at the bottom of posts" option.

=Can I make desktop footnotes expand like they do on mobile instead of using tooltips?=
Yes. Go to Settings -> Modern Footnotes and choose the "Expandable footnotes on desktop" option.

=Can I customize the reference numbers output by the plugin?=
Yes. You can specify custom reference numbers by using the "referencenumber" attribute to specify a particular reference number. For example: [mfn referencenumber=3]This will have the number 3 with it.[/mfn] 

=Can I make the footnote numbers "reset" at 1?=
Yes. Designate the footnote where you want numbers to reset with the referencereset='true' attribute and the reference counter will restart at 1. For example: [mfn referencereset='true']This will have the number 1 with it, regardless of how many footnotes came before it. The next footnote after this will be the number 2.[/mfn] Please note that this may not be compatible with all other plugins depending on how the plugin manipulates the HTML, so if the numbers aren't appearing right, you may have to use the "refnum" attribute instead (see "Can I customize the reference numbers output by the plugin?")

=Can I customize the styles of footnotes?=
If you want to customize the styles, you can do so by overriding the following styles in custom CSS for your theme:
.modern-footnotes-footnote - The superscript element displaying the footnote number
.modern-footnotes-footnote--selected - A superscript element that is currently active
.modern-footnotes-footnote__note – Styling that applies to both mobile and desktop footnotes
.modern-footnotes-footnote__note--mobile - The styling for a mobile footnote
.modern-footnotes-footnote__note--desktop - The styling for a desktop footnote

Furthermore, if you want to apply different styles to different footnotes, you can add additional classes to each individual footnote by using the `class` option in the shortcode like this:
[mfn class="my-pretty-class"] [/mfn]

=Is there support for the Block Editor/Gutenberg Editor?=
Yes. You can use the Modern Footnotes button in the toolbar of the Block Editor to move the selected text into a footnote. However, if you want to customize the reference numbers output by the plugin, you'll have to type out the shortcode instead.

=Why isn't the word "footnote" appearing by all of my footnotes in the Block Editor/Gutenberg Editor?=
The word "footnote" only shows up by the first footnote in each paragraph in the editor, but all footnotes are highlighted gray. This is to work around a technical limitation of Gutenberg's editor, though hopefully we can come up with a better solution in the future. Don't worry, your footnotes will show up correctly when viewing the page/blog post though!

=How can I support the plugin's development?=
Modern Footnotes is an open source project built with its contributors' free time. Any gestures of support are quite meaningful. You can support the plugin by leaving a positive review or <a href="https://ko-fi.com/modernfootnotes" target="_blank">buying a coffee for the developer</a>. And, if you would like to help develop the plugin, we deeply appreciate any contributions to the project on <a href="https://github.com/seankwilliams/modern-footnotes" target="_blank">GitHub</a>.

== Screenshots ==
1. http://prismtechstudios.com/modern-footnotes/modern-footnotes-1.png
2. http://prismtechstudios.com/modern-footnotes/modern-footnotes-2.png
3. http://prismtechstudios.com/modern-footnotes/modern-footnotes-3.png
4. http://prismtechstudios.com/modern-footnotes/modern-footnotes-4.png

== Changelog ==

= 1.4.16 = 
* Security fix for XSS issue. Thanks to Rio Darmawan for identifying the issue.

= 1.4.15 =
* Fix for duplicate HTML ids

= 1.4.14 =
* Minor fix for a PHP warning where foreach was attempting to access a null object

= 1.4.13 =
* Fixed a problem where using the hover option for footnotes would cause footnote display issues on mobile.

= 1.4.12 =
* Add ability to provide a custom class attribute to mfn shortcode tags
* Fix JavaScript error loading Modern Footnotes on widgets page
* Add a new option to customize the heading of a footnote list
* Fix PHP type warnings when strings were provided as footnote reference numbers

= 1.4.11 =
* Accessibility fix: set aria-describedby and gave footnote links a role of "button"

= 1.4.10 = 
* Remove outline style from tooltip when users are focused on it
* Added an option to add heading text to a footnote list

= 1.4.9 =
* Fixed a PHP warning in debug mode

= 1.4.8 =
* Fixed a PHP warning in debug mode

= 1.4.7 =
* Add option to add title attribute with footnote contents to footnote numbers

= 1.4.6 =
* Removed title attribute from footnotes (may add this in again in the future as a setting, if it's requested)

= 1.4.5 =
* Fix to issue where footnotes would sometimes interfere with line height
* Only load CSS/JS for Modern Footnotes on pages where it's used
* Add option to show tooltips on hover
* Add title attribute to a element that opens footnotes
* Fixed issue with rendering footnotes in excerpts
* Fixed issue related to WordPress back-end compatibility with other plugins
* Accessibility improvements: set focus in and out of tooltip footnotes, and make the escape key work when in a footnote

=1.4.4=
* Fixed PHP warning

=1.4.3=
* Add an option to list footnotes at the bottom of an RSS feed

=1.4.2=
* Change "x" button to only close one collapsible footnote at a time to prevent mobile scrolling issues

=1.4.1=
* Added CSS to make the footnotes list display better with some themes.

=1.4=
* Added option to list footnotes at bottom of the page. Fixed issue where Display Posts plugin would cause posts to sometimes render with incorrect numbering.

=1.3.11=
* Prepared plugin for localization

=1.3.10=
* Fixed problem on pages that listed multiple posts where footnote numbers wouldn't reset. Added an option to reset footnote numbering with an attribute. Fixed a minor code issue with the Gutenberg editor.

=1.3.9=
* Fixed problem in Chrome where a footnote near the right side of the screen would appear with compressed width on desktop

=1.3.8=
* Fixed problems with some custom implementations where footnotes wouldn't open due to extra DOM elements being placed between the footnote link & content

=1.3.7=
* Fixed JavaScript error

=1.3.6=
* Fix for problem where clicking on a footnote with the same numbering as another footnote would open both footnotes

=1.3.5=
* Fix for problem where footnote numbering would incorrectly start with '3' in some cases, especially when combined with the Yoast SEO plugin

=1.3.4=
* Fix for problem where some extra, empty footnotes would randomly appear

=1.3.3=
* Fix issue where applying multiple formats in conjunction with footnotes in the Gutenberg editor would mess up footnote formatting

=1.3.2=
* Fix issue where links inside footnotes wouldn't appear inline with other text

=1.3.1=
* Updated style so that long links are truncated with ellipsis in tooltips

=1.3.0=
* Fixed problem where Classic Editor button did not appear in WP 5.x. Added Gutenberg button. Allow shortcode within footnotes.

=1.2.7=
* Fixed additional shortcode rendering issue in RSS feeds.

=1.2.6=
* Removed footnote shortcodes from rendering in RSS feeds.

=1.2.5=
* Fixed issue with footnotes causing line breaks.

=1.2.4=
* Fixed a problem where HTML tags could not be entered inside a footnote.

=1.2.3=
* Tested with WordPress 5.0

=1.2.2=
* Changed license from LGPL to GPL2.

=1.2.1=
* Fixed a problem where footnote would improperly size when it was opened multiple times at the edge of a container

=1.2=
* Stopped using href="#" for `a` HTML elements for increased theme compatibility. Added ability to have a custom shortcode. Added a custom CSS area. Added the ability to manually override citations.

=1.1.4=
* Fixed issue where footnote numbering wouldn't be sequential when other plugins like TablePress caused shortcodes to render in a different order than they appear in the HTML.

=1.1.3=
* Fixed issue where icon was not showing in the admin MCE editor

=1.1.2=
* Fixed problem where scripts were enqueued incorrectly.

=1.1.1=
* Fixed error occuring in PHP versions below 5.3

=1.1=
* Added option to use expandable version of footnotes on desktop instead of the default tooltip style

=1.0=
* initial version