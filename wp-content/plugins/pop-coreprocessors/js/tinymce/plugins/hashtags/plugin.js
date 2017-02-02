/*global tinymce, jQuery */
(function (tinymce, $) {
    'use strict';

    tinymce.PluginManager.add('hashtags', function( editor, url ) {
        var options = editor.getParam('hashtags');
        var menu = [];
        $.each(options.values, function(index, value) {

            menu.push({
                text: value,
                onclick: function() {
                    editor.insertContent(value+'&nbsp;');
                }
            });
        });
        editor.addButton('hashtags', {
            text: options.title,
            icon: false,
            type: 'menubutton',
            menu: menu
        });
    });
}(tinymce, jQuery));