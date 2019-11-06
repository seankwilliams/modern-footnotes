/* Copyright 2017-2019 Sean Williams
    This file is part of Modern Footnotes.

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along
    with this program; if not, write to the Free Software Foundation, Inc.,
    51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/
jQuery(function($) {
	$(document).on('click', '.modern-footnotes-footnote a', null, function(e) {
		e.preventDefault();
		e.stopPropagation();
		next = '.modern-footnotes-footnote__note[data-mfn="' + $(this).parent().attr("data-mfn") + '"]';
		var $footnoteContent = $(this).parent().next(next);
		if ($footnoteContent.is(":hidden")) {
			if ($(window).width() >= 768 && $(this).parent().is(":not(.modern-footnotes-footnote--expands-on-desktop)")) { //use same size as bootstrap for mobile
				//tooltip style
				modern_footnotes_hide_footnotes(); //only allow one footnote to be open at a time on desktop
				$(this).parent().toggleClass('modern-footnotes-footnote--selected');
				$footnoteContent
					.show()
					.addClass('modern-footnotes-footnote__note--tooltip')
					.removeClass('modern-footnotes-footnote__note--expandable');
				//calculate the position for the footnote
				var position = $(this).parent().position();
				var fontHeight = Math.floor(parseInt($(this).parent().parent().css('font-size').replace(/px/, '')) * 1.5);
				var footnoteWidth = $footnoteContent.outerWidth();
				var windowWidth = $(window).width();
				var left = position.left - footnoteWidth / 2
				if (left < 0) left = 8 // leave some margin on left side of screen
				if (left + footnoteWidth > $(window).width()) left = $(window).width() - footnoteWidth;
				var top = (parseInt(position.top) + parseInt(fontHeight));
				$footnoteContent.css({
					top: top + 'px',
					left: left + 'px'
				});
				//add a connector between the footnote and the tooltip
				$footnoteContent.after('<div class="modern-footnotes-footnote__connector"></div>');
				var superscriptPosition = $(this).parent().position();
				var superscriptHeight = $(this).parent().outerHeight();
				var superscriptWidth = $(this).parent().outerWidth();
				var connectorHeight = top - superscriptPosition.top - superscriptHeight;
				$(".modern-footnotes-footnote__connector").css({
					top: (superscriptPosition.top + superscriptHeight) + 'px',
					height: connectorHeight,
					left: (superscriptPosition.left + superscriptWidth / 2) + 'px'
				});
			} else {
				//expandable style
				$footnoteContent
					.removeClass('modern-footnotes-footnote__note--tooltip')
					.addClass('modern-footnotes-footnote__note--expandable')
					.css('display', 'block');
				$(this).data('unopenedContent', $(this).html());
				$(this).html('x');
			}
		} else {
			modern_footnotes_hide_footnotes();
		}
	}).on('click', '.modern-footnotes-footnote__note', null, function(e) {
		e.stopPropagation();
	}).on('click', '.modern-footnotes-footnote__note__close a', null, function(e) {
		e.preventDefault();
		modern_footnotes_hide_footnotes();
	}).on('click', function() {
		modern_footnotes_hide_footnotes();
	});

	//hide all footnotes on window resize or clicking anywhere but on the footnote link
	$(window).resize(function() {
		modern_footnotes_hide_footnotes();
	});

	//some plugins, like TablePress, cause shortcodes to be rendered
	//in a different order than they appear in the HTML. This can cause
	//the numbering to be out of order. I couldn't find a way to deal
	//with this on the PHP side (as of 1/27/18), so this JavaScript fix
	//will correct the numbering if it's not sequential.
	var $footnotesAnchorLinks = $("body .modern-footnotes-footnote a");
	var usedReferenceNumbers = [0];
	if ($footnotesAnchorLinks.length > 1) {
		$footnotesAnchorLinks.each(function() {
			if ($(this).is("a[refnum]")) {
				var manualRefNum = $(this).attr("refnum");
				if ($(this).html() != manualRefNum) {
					$(this).html(manualRefNum);
				}
				if (!isNaN(parseFloat(manualRefNum)) && isFinite(manualRefNum)) { //prevent words from being added to this array
					usedReferenceNumbers.push(manualRefNum);
				}
			}
			else {
				var refNum = Math.max.apply(null, usedReferenceNumbers) + 1;
				if ($(this).html() != refNum) {
					$(this).html(refNum);
				}
				usedReferenceNumbers.push(refNum);
			}
		});
	}

});


function modern_footnotes_hide_footnotes() {
	jQuery(".modern-footnotes-footnote a").each(function() {
		var $this = jQuery(this);
		if ($this.data('unopenedContent')) {
			$this.html($this.data('unopenedContent'));
		}
	});
	jQuery(".modern-footnotes-footnote__note").hide().css({'left': '', 'top': ''}); //remove left and top property to prevent improper calculations per the bug report at https://wordpress.org/support/topic/footnotes-resizing-on-subsequent-clicks/
	jQuery(".modern-footnotes-footnote__connector").remove();
	jQuery(".modern-footnotes-footnote--selected").removeClass("modern-footnotes-footnote--selected");
}
