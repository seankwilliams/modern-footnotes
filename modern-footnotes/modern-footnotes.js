jQuery(function($) {
	$(document).on('click', '.modern-footnotes-footnote a', null, function(e) {
		e.preventDefault();
		e.stopPropagation();
		var $footnoteContent = $(this).parent().next('.modern-footnotes-footnote__note');
		if ($footnoteContent.is(":hidden")) {
			if ($(window).width() >= 768) { //only allow one footnote to be open at a time on desktop
				modern_footnotes_hide_footnotes();
			}
			$footnoteContent.toggle();
			if ($(window).width() >= 768) { //use same size as bootstrap for mobile
				//desktop
				$(this).parent().toggleClass('modern-footnotes-footnote--selected');
				$footnoteContent
					.addClass('modern-footnotes-footnote__note--desktop')
					.removeClass('modern-footnotes-footnote__note--mobile');
				//calculate the position for the footnote
				var position = $(this).parent().position();
				var lineHeight = $(this).parent().parent().css('line-height').replace(/px/, '');
				var footnoteWidth = $footnoteContent.outerWidth();
				var windowWidth = $(window).width();
				var left = position.left - footnoteWidth / 2
				if (left < 0) left = 8 // leave some margin on left side of screen
				if (left + footnoteWidth > $(window).width()) left = $(window).width() - footnoteWidth;
				var top = (parseInt(position.top) + parseInt(lineHeight));
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
				//mobile
				$footnoteContent
					.removeClass('modern-footnotes-footnote__note--desktop')
					.addClass('modern-footnotes-footnote__note--mobile')
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