<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\CustomPostMedia\Misc\MediaHelpers;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

// Allow posts to have menu_order. This is needed for the TPP Debate website,
// to order the Author Thoughts Carousel, so that it always shows the General thought first, and the then article-related ones
HooksAPIFacade::getInstance()->addAction('admin_init', 'gdPostsMenuorder');
function gdPostsMenuorder()
{
    add_post_type_support('post', 'page-attributes');
}

// Make the tinymce always rich edit, also if user is logged out, or accessing the website using wget (so we can use wget to call /system/popinstall and save the service-workers.js file properly)
HooksAPIFacade::getInstance()->addFilter('user_can_richedit', '__return_true', PHP_INT_MAX);

/**
 * Add Media: do ALWAYS add a link to the image
 */
HooksAPIFacade::getInstance()->addFilter('media_send_to_editor', 'wassupMediaSendToEditor', 0, 3);
function wassupMediaSendToEditor($html, $id, $attachment)
{

    // If the image has no URL tag...
    if (substr($html, 0, 5) == '<img ') {
        // Add a link to the image. Code copied from function wp_ajax_send_attachment_to_editor()
        $html = sprintf(
            '<a href="%s">%s</a>',
            esc_url(wp_get_attachment_url($id)),
            $html
        );
    }

    return $html;
}

function gdGetPostDescription()
{
    $vars = ApplicationState::getVars();
    $cmsapplicationhelpers = \PoP\Application\HelperAPIFactory::getInstance();
    $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
    $post_id = $vars['routing-state']['queried-object-id'];
    $excerpt = $customPostTypeAPI->getExcerpt($post_id);

    // If the excerpt is empty, return the post content instead
    if (!$excerpt) {
        // 300 characters long is good enough, remove all whitespaces
        $excerpt = str_replace(
            array("\n\r", "\n", "\r", "\t"),
            array(' ', '', '', ' '),
            limitString(
                $customPostTypeAPI->getPlainTextContent($post_id),
                300
            )
        );
    }

    return $cmsapplicationhelpers->escapeHTML($excerpt);
}

function gdHeaderRouteDescription()
{
    $vars = ApplicationState::getVars();
    $route = $vars['route'];
    return HooksAPIFacade::getInstance()->applyFilters('gdHeaderRouteDescription', '', $route);
}

function gdHeaderSiteDescription()
{
    return HooksAPIFacade::getInstance()->applyFilters('gdHeaderSiteDescription', '');
}

function gdGetThemeColor()
{
    return HooksAPIFacade::getInstance()->applyFilters('gdGetThemeColor', '#FFFFFF');
}

function gdGetDocumentThumb($size = 'large')
{
    $vars = ApplicationState::getVars();
    $cmsmediaapi = \PoPSchema\Media\FunctionAPIFactory::getInstance();
    if ($vars['routing-state']['is-custompost'] || $vars['routing-state']['is-page']) {
        $post_id = $vars['routing-state']['queried-object-id'];
        if ($post_thumb_id = MediaHelpers::getThumbId($post_id)) {
            $thumb = $cmsmediaapi->getMediaSrc($post_thumb_id, $size);
            $thumb_mime_type = $cmsmediaapi->getMediaMimeType($post_thumb_id);
        }
    } elseif ($vars['routing-state']['is-user']) {
        if (defined('POP_AVATAR_INITIALIZED')) {
            $author = $vars['routing-state']['queried-object-id'];
            $userphoto = gdGetAvatarPhotoinfo($author);
            $thumb = array($userphoto['src'], $userphoto['width'], $userphoto['height']);
            $thumb_mime_type = '';
        }
    }

    if (!$thumb) {
        // Use the website logo
        $thumb = gdLogo($size);
        $thumb_mime_type = $thumb[3];
    }

    // If there's no thumb (eg: a page doesn't have Featured Image) then return nothing
    if (!$thumb[0]) {
        return null;
    }

    return array(
        'src' => $thumb[0],
        'width' => $thumb[1],
        'height' => $thumb[2],
        'mime-type' => $thumb_mime_type
    );
}
