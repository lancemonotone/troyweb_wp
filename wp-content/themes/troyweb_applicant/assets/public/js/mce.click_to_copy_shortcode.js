(function() {
    tinymce.PluginManager.add('custom_shortcodes', function(editor, url) {
        editor.addButton('custom_shortcodes_dropdown', {
            type: 'listbox',
            text: 'Shortcodes',
            onselect: function(e) {
                const text = editor.selection.getContent({ 'format': 'html' });
                if (text.length === 0) {
                    alert('Please select some text.');
                    return;
                }
                // This will replace the placeholder content with the selected text
                const selected_shortcode = this.value();
                const return_text = selected_shortcode.replace('{{content}}', text);
                editor.execCommand('mceInsertContent', 0, return_text);
            },
            values: custom_shortcodes_data.shortcodes
        });
    });
})();

