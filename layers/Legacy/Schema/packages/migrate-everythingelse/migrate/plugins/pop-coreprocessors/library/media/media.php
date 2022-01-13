<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

\PoP\Root\App::getHookManager()->addFilter('icon_dirs', 'gdMediaIconDirs', 100);

// Icons copied from EG-Attachment plugin
function gdMediaIconDirs($args)
{

    // If $args is not an array => return directly the value
    if (!is_array($args)) {
        return $args ;
    }

    // Add the icons path of the Resources
    $media_icons = array(dirname(dirname(__FILE__)).'/media/img/flags' => dirname(__FILE__).'/img/flags');

    return array_merge($media_icons, $args);
}

/**
 * Do not allow certain filetypes
 */

\PoP\Root\App::getHookManager()->addFilter('upload_mimes', 'gdUploadMimes');
function gdUploadMimes($mime)
{
    $unset = array('exe', 'gz', 'gzip', 'rar', 'tar', 'zip');

    foreach ($unset as $val) {
        unset($mime[$val]);
    }

    return $mime;
}

/**
 * Embed size (for Youtube videos): make it responsive
 * Source: http://alxmedia.se/code/2013/10/make-wordpress-default-video-embeds-responsive/
 */

\PoP\Root\App::getHookManager()->addFilter('embed_defaults', 'gdEmbedDefaultsSize');
function gdEmbedDefaultsSize()
{
    // adjust these pixel values to your needs
    // return array( 'width' => 640, 'height' => 480 );
    return array( 'width' => 480, 'height' => 400 );
}


/**
 * Allow for prettyPhoto to override its rel here
 */

function gdImageRel()
{
    return \PoP\Root\App::getHookManager()->applyFilters('gdImageRel', '');
}


/**
 * Removes the "Set Featured Image"
 *
 * @see wp-includes|media.php
 */
\PoP\Root\App::getHookManager()->addFilter('media_view_strings', 'corMediaViewStrings');
function corMediaViewStrings($strings)
{
    if (!is_admin()) {
        // Comment Leo 05/08/2017:
        // doing unset creates a bug: sometimes it adds item 'Media Library' in the Media Manager, on the left side navigation (Insert Media, Create Gallery, then Media Library)
        // which is the 'featured-image' panel
        // It happens when:
        //     First loading http://getpop.localhost/en/add-post/
        //     Then clicking http://getpop-demo.localhost/en/add-post/
        //     Opening Media Manager there
        // unset( $strings['setFeaturedImageTitle'] );
        $strings['setFeaturedImageTitle'] = '';

        // doing unset only adds the default mediaLibraryTitle instead, so setting it to an empty string works
        $strings['createPlaylistTitle'] = '';
        $strings['createVideoPlaylistTitle'] = '';
    }
    
    return $strings;
}


/**
 * Enqueue scripts and constants
 */
\PoP\Root\App::getHookManager()->addAction("wp_enqueue_scripts", "gdMediaRegisterScripts");
function gdMediaRegisterScripts()
{

    // Comment Leo 27/11/2014: Since MESYM v4.0, always embed the Media Manager so that everything is loaded for users to just log in and post stuff,
    //  so force the state to include it always (user logged in or not, we don't care)
    wp_enqueue_script('media-upload');

    wp_enqueue_media(array( 'post' => null ));
    // thickbox.js is needed to close the Media Manager (function tb_remove)
    wp_enqueue_script('thickbox');
}

\PoP\Root\App::getHookManager()->addFilter('gd_jquery_constants', 'gdJqueryConstantsMediaManagerImpl');
function gdJqueryConstantsMediaManagerImpl($jqueryConstants)
{

    // Comment Leo 27/11/2014: Since MESYM v4.0, always embed the Media Manager so that everything is loaded for users to just log in and post stuff,
    //  so force the state to include it always (user logged in or not, we don't care)
    $jqueryConstants['MEDIA_MANAGER_TITLE'] = TranslationAPIFacade::getInstance()->__("Upload media files", "greendrinks");
    
    // Changed because of a bug: this text also shows for the Set Featured Image button, for Add Action / Event / etc. So settled on a standard text
    $jqueryConstants['MEDIA_MANAGER_BUTTON'] = TranslationAPIFacade::getInstance()->__("Set", "greendrinks");
            
    return $jqueryConstants;
}


/*  Add responsive container to embeds
/* ------------------------------------ */
function alxEmbedHtml($html, $src)
{

    // Comment Leo 20/03/2016: Only add the class "responsiveembed-container" if the embed is a video
    // Otherwise, when embedding for instance from something.wordpress.com, it will embed just a title, but with
    // a huge spacing all around it, from the video container
    // Code below copied from wp-includes/class-oembed.php
    $classs = 'embed-container';
    if (// Embedded Youtube videos (www or mobile)
        preg_match('#http://((m|www)\.)?youtube\.com/watch.*#i', $src)
        || preg_match('#https://((m|www)\.)?youtube\.com/watch.*#i', $src)
        || preg_match('#http://((m|www)\.)?youtube\.com/playlist.*#i', $src)
        || preg_match('#https://((m|www)\.)?youtube\.com/playlist.*#i', $src)
        || preg_match('#http://youtu\.be/.*#i', $src)
        || preg_match('#https://youtu\.be/.*#i', $src)
        // Embedded Vimeo iframe videos
        || preg_match('#https?://(.+\.)?vimeo\.com/.*#i', $src)
        // Embedded Vine videos
        || preg_match('#https?://vine.co/v/.*#i', $src)
        // Embedded Daily Motion videos
        || preg_match('#https?://(www\.)?dailymotion\.com/.*#i', $src)
        // Embedded Soundcloud songs
        || preg_match('#https?://(www\.)?soundcloud\.com/.*#i', $src)
        // Embedded Slideshare presentations
        || preg_match('#https?://(.+?\.)?slideshare\.net/.*#i', $src)
        // Embedded Spotify music
        || preg_match('#https?://(open|play)\.spotify\.com/.*#i', $src)
        // Embedded Issuu magazines
        || preg_match('#https?://(www\.)?issuu\.com/.+/docs/.+#i', $src)
        // Embedded Mixcloud Radio
        || preg_match('#https?://(www\.)?mixcloud\.com/.*#i', $src)
        // Embedded KickStarter campaigns
        || preg_match('#https?://(www\.)?kickstarter\.com/projects/.*#i', $src)
        || preg_match('#https?://kck\.st/.*#i', $src)
    ) {
        $classs = 'responsiveembed-container';
    }

    return sprintf(
        '<div class="%s">%s</div>',
        $classs,
        $html
    );
}
\PoP\Root\App::getHookManager()->addFilter('embed_oembed_html', 'alxEmbedHtml', 10, 2);
// \PoP\Root\App::getHookManager()->addFilter( 'video_embed_html', 'alxEmbedHtml' ); // Jetpack
