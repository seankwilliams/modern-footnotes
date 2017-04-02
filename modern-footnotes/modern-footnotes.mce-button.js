(function() {
tinymce.PluginManager.add('modern_footnotes', function( editor, url ) {
    editor.addButton( 'modern_footnotes', {
        title: 'Add a Footnote',
        icon: 'modern-footnotes-admin-button',
        onclick: function() {
			//if text is highlighted, wrap that text in a footnote
			//otherwise, show an editor to insert a footnote
			editor.focus();
			var content = editor.selection.getContent();
			if (content.length > 0) {
				if (content.indexOf('[modern_footnote]') == -1 && content.indexOf('[/modern_footnote]') == -1 &&
					content.indexOf('[mfn]') == -1 && content.indexOf('[/mfn]') == -1) {
					editor.selection.setContent('[modern_footnote]' + content + '[/modern_footnote]');
				} else if (content.indexOf('[modern_footnote]') != -1 && content.indexOf('[/modern_footnote]') != -1) {
					editor.selection.setContent(content.replace(/\[modern\_footnote\]/, '').replace(/\[\/modern\_footnote\]/, ''));
				} else if (content.indexOf('[mfn]') != -1 && content.indexOf('[/mfn]') != -1) {
					editor.selection.setContent(content.replace(/\[mfn\]/, '').replace(/\[\/mfn\]/, ''));
				} else {
					//we don't have a full tag in the selection, do nothing
				}
			} else {
				editor.windowManager.open( {
					title: 'Insert Footnote',
					body: [{
						type: 'textbox',
						name: 'footnote',
						label: 'Footnote'
					}],
					onsubmit: function( e ) {
						editor.insertContent( '[modern_footnote]' + e.data.footnote + '[/modern_footnote]');
					}
				});
			}
		}

    });
});
})();