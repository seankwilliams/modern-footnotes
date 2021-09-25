/* Copyright 2017-2021 Sean Williams
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
  $(document).on('mouseenter', '.modern-footnotes-footnote.modern-footnotes-footnote--hover-on-desktop a', null, function(e) {
    if ($(window).width() >= 768) {
      window.modernFootnotesActivelyHovering = true;
      window.modernFootnotesOpenedFootnoteViaHover = true;
      modern_footnotes_show_tooltip_footnote($(this).parent(), true); //don't transfer focus when hovering - this messes up text highlighting
    }
  });
  $(document).on('mouseenter', '.modern-footnotes-footnote__connector,.modern-footnotes-footnote__note', null, function(e) {
    window.modernFootnotesActivelyHovering = true;
  });
  $(document).on('mouseleave', '.modern-footnotes-footnote.modern-footnotes-footnote--hover-on-desktop,.modern-footnotes-footnote.modern-footnotes-footnote--hover-on-desktop .modern-footnotes-footnote__connector,.modern-footnotes-footnote.modern-footnotes-footnote--hover-on-desktop .modern-footnotes-footnote__note', null, function(e) {
    window.modernFootnotesActivelyHovering = false;
    if (window.modernFootnotesHoverCloseTimeout != null) {
      clearTimeout(window.modernFootnotesHoverCloseTimeout);
    }
    window.modernFootnotesHoverCloseTimeout = setTimeout(function() {
      window.modernFootnotesHoverCloseTimeout = null;
      if (!window.modernFootnotesActivelyHovering) {
        modern_footnotes_hide_footnotes();
      }
    }, 600);
  });
	$(document).on('click', '.modern-footnotes-footnote a', null, function(e) {
		e.preventDefault();
		e.stopPropagation();
		next = '.modern-footnotes-footnote__note[data-mfn="' + $(this).parent().attr("data-mfn") + '"]';
		var $footnoteContent = $(this).parent().nextAll(next).eq(0);
		if ($footnoteContent.is(":hidden")) {
			if ($(window).width() >= 768 && $(this).parent().is(":not(.modern-footnotes-footnote--expands-on-desktop)")) { //use same size as bootstrap for mobile
        modern_footnotes_show_tooltip_footnote($(this).parent());
        $(this).attr("aria-pressed","true");
			} else if ($(window).width() < 768 || $(this).parent().is(":not(.modern-footnotes-footnote--hover-on-desktop)")) {
				//expandable style
        $(this).attr("aria-pressed","true");
				$footnoteContent
					.removeClass('modern-footnotes-footnote__note--tooltip')
					.addClass('modern-footnotes-footnote__note--expandable')
					.css('display', 'block');
				$(this).data('unopenedContent', $(this).html());
				$(this).html('x');
			} else {
        //do nothing when user is in desktop + .modern-footnotes-footnote--hover-on-desktop is present (behavior is handled by hovering, in that case
      }
		} else {
			modern_footnotes_hide_footnotes($(this));
		}
	}).on('click', '.modern-footnotes-footnote__note', null, function(e) {
		e.stopPropagation();
	}).on('click', function() {
    //when clicking the body, close tooltip-style footnotes
    if ($(window).width() >= 768 && $(".modern-footnotes-footnote--expands-on-desktop").length == 0) {
      modern_footnotes_hide_footnotes(); 
    }
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
	var usedReferenceNumbers = {};
	if ($footnotesAnchorLinks.length > 1) {
		$footnotesAnchorLinks.each(function() {
      var postScope = $(this).parent().attr("data-mfn-post-scope");
      if (typeof usedReferenceNumbers[postScope] === 'undefined') {
        usedReferenceNumbers[postScope] = [0];
      }
      if ($(this).is("a[data-mfn-reset]")) {
        usedReferenceNumbers[postScope] = [0];
      }
			if ($(this).is("a[refnum]")) {
				var manualRefNum = $(this).attr("refnum");
				if ($(this).html() != manualRefNum) {
					$(this).html(manualRefNum);
				}
				if (!isNaN(parseFloat(manualRefNum)) && isFinite(manualRefNum)) { //prevent words from being added to this array
					usedReferenceNumbers[postScope].push(manualRefNum);
				}
			}
			else {
				var refNum = Math.max.apply(null, usedReferenceNumbers[postScope]) + 1;
				if ($(this).html() != refNum) {
					$(this).html(refNum);
				}
				usedReferenceNumbers[postScope].push(refNum);
			}
		});
	}

});


/* if $footnoteAnchor provided, closes that footnote. Otherwise, closes all footnotes */
function modern_footnotes_hide_footnotes($footnoteAnchor) {
  window.modernFootnotesOpenedFootnoteViaHover = false;
  if ($footnoteAnchor != null) {
    if ($footnoteAnchor.data('unopenedContent')) {
      $footnoteAnchor.html($footnoteAnchor.data('unopenedContent'));
    }
    let next = '.modern-footnotes-footnote__note[data-mfn="' + $footnoteAnchor.parent().attr("data-mfn") + '"]';
    let $note = $footnoteAnchor.parent().nextAll(next).eq(0); //use nextAll insetad of next in case people are adding HTML between the footnote elements, which some folks use as a customization: https://wordpress.org/support/topic/expandable-footnote-does-not-disappear/
    $note.hide().css({'left': '', 'top': ''}); //remove left and top property to prevent improper calculations per the bug report at https://wordpress.org/support/topic/footnotes-resizing-on-subsequent-clicks/
    $note.next(".modern-footnotes-footnote__connector").remove();
    $footnoteAnchor.removeClass("modern-footnotes-footnote--selected");
    $footnoteAnchor.attr("aria-pressed","false");
    $footnoteAnchor.focus();
  } else {
    jQuery(".modern-footnotes-footnote a").each(function() {
      var $this = jQuery(this);
      if ($this.data('unopenedContent')) {
        $this.html($this.data('unopenedContent'));
      }
    });
    jQuery(".modern-footnotes-footnote > a").attr("aria-pressed", "false");
    jQuery(".modern-footnotes-footnote__note").hide().css({'left': '', 'top': ''}); //remove left and top property to prevent improper calculations per the bug report at https://wordpress.org/support/topic/footnotes-resizing-on-subsequent-clicks/
    jQuery(".modern-footnotes-footnote__connector").remove();
    jQuery(".modern-footnotes-footnote--selected").removeClass("modern-footnotes-footnote--selected");
  }
}

function modern_footnotes_show_tooltip_footnote($footnoteElement, doNotTransferFocus) {
  //tooltip style
  modern_footnotes_hide_footnotes(); //only allow one footnote to be open at a time on desktop
  $footnoteElement.toggleClass('modern-footnotes-footnote--selected');
  let next = '.modern-footnotes-footnote__note[data-mfn="' + $footnoteElement.attr("data-mfn") + '"]';
  var $footnoteContent = $footnoteElement.nextAll(next).eq(0);
  $footnoteContent
    .show()
    .addClass('modern-footnotes-footnote__note--tooltip')
    .removeClass('modern-footnotes-footnote__note--expandable');
  if (!doNotTransferFocus) {
    $footnoteContent.focus();
  }
  //accessibility - close footnote on escape key
  $footnoteContent
    .unbind('keydown')
    .bind('keydown', function(event) {
      if (event.key == 'Escape') {
        modern_footnotes_hide_footnotes($footnoteElement.children('a'));
      }
    });
  //calculate the position for the footnote
  var position = $footnoteElement.position();
  var fontHeight = Math.floor(parseInt($footnoteElement.parent().css('font-size').replace(/px/, '')) * 1.5);
  var footnoteWidth = $footnoteContent.outerWidth();
  var windowWidth = jQuery(window).width();
  var left = position.left - footnoteWidth / 2
  if (left < 0) left = 8 // leave some margin on left side of screen
  if (left + footnoteWidth > jQuery(window).width()) left = jQuery(window).width() - footnoteWidth;
  var top = (parseInt(position.top) + parseInt(fontHeight));
  $footnoteContent.css({
    top: top + 'px',
    left: left + 'px'
  });
  //add a connector between the footnote and the tooltip
  $footnoteContent.after('<div class="modern-footnotes-footnote__connector"></div>');
  var superscriptPosition = $footnoteElement.position();
  var superscriptHeight = $footnoteElement.outerHeight();
  var superscriptWidth = $footnoteElement.outerWidth();
  var connectorHeight = top - superscriptPosition.top - superscriptHeight;
  jQuery(".modern-footnotes-footnote__connector").css({
    top: (superscriptPosition.top + superscriptHeight) + 'px',
    height: connectorHeight,
    left: (superscriptPosition.left + superscriptWidth / 2) + 'px'
  });
}