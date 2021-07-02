<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Misc\RequestUtils;

// The editor filters are not initialized when fetching json page. So initialize it below
// This way, function getValue('contentEditor') on typeResolver-posts.php
// will process the right html output, and not the one the HTML tab (without <p> <br> etc tags)
// Code copied from wp-includes/class-wp-editor.php => public static function editor( $content, $editor_id, $settings = array() )
// Comment Leo 20/06/2017: this cannot be executed on 'init'! If doing so, function `loadingSite` initializes
// ApplicationState::getVars() before the WordPress query vars are set, which means that
// function is_home, isAuthor, isPage, etc, they all fail, returning always false
// For that, instead execute when we have initialized PoP
// HooksAPIFacade::getInstance()->addFilter('init', 'gdEditorInit');
HooksAPIFacade::getInstance()->addFilter('\PoP\ComponentModel\Engine:beginning', 'gdEditorInit');
function gdEditorInit()
{
    if (is_admin() || RequestUtils::loadingSite()) {
        return;
    }

    // Since WP 4.3.0, the functions below are deprecated, replaced with 'format_for_editor'
    // $default_editor = wp_default_editor();
    // // 'html' is used for the "Text" editor tab.
    // if ( 'html' === $default_editor ) {
    //   HooksAPIFacade::getInstance()->addFilter('the_editor_content', 'wp_htmledit_pre');
    // } else {
    //   HooksAPIFacade::getInstance()->addFilter('the_editor_content', 'wp_richedit_pre');
    // }
    HooksAPIFacade::getInstance()->addFilter('the_editor_content', 'format_for_editor', 10, 2);
}


// Absolutely always state that the default editor is Visual. This way, function getValue('contentEditor') on typeResolver-posts.php
// will process the right html output, and not the one the HTML tab (without <p> <br> etc tags)
HooksAPIFacade::getInstance()->addFilter('wp_default_editor', 'gdWpDefaultEditor', 10000000, 1);
function gdWpDefaultEditor($default_editor)
{
    if (is_admin()) {
        // return $default_editor;
        // Fix bug: somehow in the admin the tags are removed and it's all messed up. But it doesn't happen on 'html' mode
        // So just set it on HTML mode always then
        return 'html';
    }

    return 'tinymce';
}

HooksAPIFacade::getInstance()->addFilter('teeny_mce_before_init', 'gdMceBeforeInit');
HooksAPIFacade::getInstance()->addFilter('tiny_mce_before_init', 'gdMceBeforeInit');
function gdMceBeforeInit($mceInit)
{
    if (is_admin()) {
        return $mceInit;
    }

    // There's a problem, in which switching from HTML to Visual strips it down from the linebreaks
    // So add the configuration for this not to happen (http://www.tinymce.com/wiki.php/Configuration3x:apply_source_formatting)
    // $mceInit['apply_source_formatting'] = true;

    // Add Bootstrap classes by default: Table
    // Documentation: http://www.tinymce.com/wiki.php/Configuration:table_default_attributes
    $mceInit['table_default_attributes'] = json_encode(
        array(
            'class' => 'table table-bordered',
            'style' => 'width: 100%',
        )
    );
    return $mceInit;
}

// Solution to problem: links can't be edited inside wpEditor inside Bootstrap modal
// Solution: replace tinymce plugin wplink with link, and then implement solutions stated in these links:
// - http://jsfiddle.net/e99xf/13/
// - http://stackoverflow.com/questions/18111582/tinymce-4-links-plugin-modal-in-not-editable
HooksAPIFacade::getInstance()->addFilter('teeny_mce_plugins', 'gdMcePluginsEdit');
HooksAPIFacade::getInstance()->addFilter('tiny_mce_plugins', 'gdMcePluginsEdit');
function gdMcePluginsEdit($plugins)
{
    if (is_admin()) {
        return $plugins;
    }

    // Replace the WP Link button with the tinymce standard link button
    array_splice($plugins, array_search('wplink', $plugins), 1);
    // unset($plugins['wplink']);
  
    // Do not allow for fullscreen because it creates trouble inside the addons pageSection (which can also be fullscreen)
    // unset($plugins['fullscreen']);
    array_splice($plugins, array_search('fullscreen', $plugins), 1);
    return $plugins;
}
// Remove them also from the quicktags
HooksAPIFacade::getInstance()->addFilter('quicktags_settings', 'gdQuicktagsSettings');
function gdQuicktagsSettings($qtInit)
{
    if (is_admin()) {
        return $qtInit;
    }

    // As defined in wp-includes/class-wp-editor.php public static function editorSettings($editor_id, $set)
    $qtInit['buttons'] = str_replace(array(',link', ',fullscreen'), '', $qtInit['buttons']);
    return $qtInit;
}
HooksAPIFacade::getInstance()->addFilter('mce_buttons', 'gdMceButtonsEdit');
function gdMceButtonsEdit($buttons)
{
    if (is_admin()) {
        return $buttons;
    }

    array_splice($buttons, array_search('wp_more', $buttons), 1);

    // Move the align buttons from 1st to 2nd row
    array_splice($buttons, array_search('alignleft', $buttons), 1);
    array_splice($buttons, array_search('aligncenter', $buttons), 1);
    array_splice($buttons, array_search('alignright', $buttons), 1);
    array_splice($buttons, array_search('strikethrough', $buttons), 1);

    // Move from 2nd to 1st row
    array_splice($buttons, array_search('italic', $buttons)+1, 0, array('underline'));
    // array_splice($buttons, array_search('strikethrough', $buttons)+1, 0, array('forecolor'));

    // // Add the code and table buttons
    // array_splice($buttons, array_search('bullist', $buttons), 0, array('table'));
    // array_splice($buttons, array_search('wp_adv', $buttons), 0, array('code'));
    return $buttons;
}
HooksAPIFacade::getInstance()->addFilter('mce_buttons_2', 'gdMceButtonsEdit2');
function gdMceButtonsEdit2($buttons)
{
    if (is_admin()) {
        return $buttons;
    }

    // Move the align buttons from 1st to 2nd row
    array_splice($buttons, array_search('alignjustify', $buttons), 0, array('strikethrough', 'alignleft', 'aligncenter', 'alignright'));

    // Move from 2nd to 1st row
    array_splice($buttons, array_search('underline', $buttons), 1);
    // array_splice($buttons, array_search('forecolor', $buttons), 1);

    array_splice($buttons, array_search('wp_help', $buttons), 1);
    array_splice($buttons, array_search('charmap', $buttons), 1);

    // Add the code and table buttons
    array_splice($buttons, array_search('removeformat', $buttons)+1, 0, array('table'));
    array_splice($buttons, array_search('redo', $buttons)+1, 0, array('code'));
    return $buttons;
}
HooksAPIFacade::getInstance()->addFilter('mce_external_plugins', 'gdMceExternalPlugins');
function gdMceExternalPlugins($plugins)
{
    if (is_admin()) {
        return $plugins;
    }

    $js_folder = POP_MASTERCOLLECTIONWEBPLATFORM_URL.'/js';
    $includes_js_folder = $js_folder.'/includes';

    $plugins['link'] = $includes_js_folder . '/tinymce/plugins/link/plugin.min.js';
    $plugins['table'] = $includes_js_folder . '/tinymce/plugins/table/plugin.min.js';
    $plugins['code'] = $includes_js_folder . '/tinymce/plugins/code/plugin.min.js';

    return $plugins;
}

/**
 * Add Media Hack
 * Always show the 'Add Media' button, even if the user is not logged in
 */
HooksAPIFacade::getInstance()->addFilter('gdWpEditorSet', 'gdWpEditorSet');
function gdWpEditorSet($set)
{
    $set['media_buttons'] = true;
    return $set;
}

/**
 * Add 2 buttons: one for the Media, and one for Image Gallery
 */
HooksAPIFacade::getInstance()->addAction('media_buttons', 'popMediaButtons', 20, 1);
function popMediaButtons($editor_id = 'content')
{
    if (!is_admin()) {
        $img = '<span class="wp-media-buttons-icon"></span> ';

        printf(
            '<button type="button" class="button insert-media add_media pop-mediabutton" data-editor="%s">%s</button>',
            esc_attr($editor_id),
            $img . TranslationAPIFacade::getInstance()->__('Add Photo/File', 'pop-coreprocessors')
        );

        // Class 'gallery' makes it open the gallery.
        printf(
            '<button type="button" class="button insert-media add_media gallery pop-mediabutton" data-editor="%s">%s</button>',
            esc_attr($editor_id),
            $img . TranslationAPIFacade::getInstance()->__('Add Image Gallery', 'pop-coreprocessors')
        );

        // Class 'gallery' makes it open the gallery.
        printf(
            '<button type="button" class="button pop-mediabutton" data-toggle="collapse" data-target=".%1$s" aria-expanded="false">%2$s</button>'.
            '<div class="collapse %1$s alert alert-warning"><a href=".%1$s" class="close" data-toggle="collapse">x</a>%3$s</div>', //'<div><a href=".%1$s" class="close" data-toggle="collapse">%4$s</a></div>'
            'oembed-info',
            '<i class="fa fa-fw fa-info-circle"></i>'.TranslationAPIFacade::getInstance()->__('Embed Youtube videos, Tweets or others', 'pop-coreprocessors'),
            // sprintf(
            //   TranslationAPIFacade::getInstance()->__('Embed %sYoutube video, %sTweet or others', 'pop-coreprocessors'),
            //   '<i class="fa fa-fw fa-youtube"></i>',
            //   '<i class="fa fa-fw fa-twitter"></i>'
            // ),
            TranslationAPIFacade::getInstance()->__(
                '<p>To embed a video or another object, simply place its URL into the content area. Make sure the URL is on its own line and not hyperlinked. For example:</p><pre>Check out this cool video:

https://www.youtube.com/watch?v=sxm3Xyutc1s

That is a lovely tango!</pre><p>You can embed URLs from all these services:</p><p><i class="fa fa-fw fa-flickr"></i>Flickr, <i class="fa fa-fw fa-instagram"></i>Instagram, <i class="fa fa-fw fa-reddit"></i>Reddit, <i class="fa fa-fw fa-slideshare"></i>SlideShare, <i class="fa fa-fw fa-soundcloud"></i>SoundCloud, <i class="fa fa-fw fa-spotify"></i>Spotify, <i class="fa fa-fw fa-twitter"></i>Twitter, <i class="fa fa-fw fa-vimeo"></i>Vimeo, <i class="fa fa-fw fa-vine"></i>Vine, <i class="fa fa-fw fa-youtube"></i>YouTube, and others.</p>',
                'pop-coreprocessors'
            )
            // TranslationAPIFacade::getInstance()->__('Close', 'pop-coreprocessors')
        );
    }
}
