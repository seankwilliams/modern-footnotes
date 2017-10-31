/* Copyright 2017 Sean Williams
    This file is part of Modern Footnotes.

    Modern Footnotes is free software: you can redistribute it and/or modify
    it under the terms of the GNU Lesser General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Modern Footnotes is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Lesser General Public License for more details.

    You should have received a copy of the GNU Lesser General Public License
    along with Modern Footnotes.  If not, see https://www.gnu.org/licenses/lgpl-3.0.en.html.
*/
jQuery(function($) {
	$(document).on('click', '.modern-footnotes-footnote a', null, function(e) {
		e.preventDefault();
		e.stopPropagation();
		var $footnoteContent = $(this).parent().next('.modern-footnotes-footnote__note');
		if ($footnoteContent.is(":hidden")) {
			if ($(window).width() >= 768 && $("body").is(":not(.modern-footnotes--use-expandble-footnotes-desktop)")) { //use same size as bootstrap for mobile
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
});


function modern_footnotes_hide_footnotes() {
	jQuery(".modern-footnotes-footnote a").each(function() {
		var $this = jQuery(this);
		if ($this.data('unopenedContent')) {
			$this.html($this.data('unopenedContent'));
		}
	});
	jQuery(".modern-footnotes-footnote__note").hide();
	jQuery(".modern-footnotes-footnote__connector").remove();
	jQuery(".modern-footnotes-footnote--selected").removeClass("modern-footnotes-footnote--selected");
}