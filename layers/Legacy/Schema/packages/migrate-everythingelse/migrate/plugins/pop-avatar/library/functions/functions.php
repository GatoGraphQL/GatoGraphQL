<?php

/**
 * Allow to retrieve Original Size "Avatars" (these original ones need not be squared)
 */

function gdGetAvatarPhotoinfo($user_id, $use_default = true)
{

    // Change PoP: If the function has a filter, then execute this one instead
    // This way we allow the AWS logic to take over
    if (has_filter('gdGetAvatarPhotoinfo:override')) {
        return \PoP\Root\App::applyFilters('gdGetAvatarPhotoinfo:override', array(), $user_id, $use_default);
    }

    // If the user has no avatar/photo, use the default one
    $pluginapi = PoP_Avatar_FunctionsAPIFactory::getInstance();
    $avatar_data = $pluginapi->userAvatarExists($user_id, array('source' => 'photo'));
    if (!$avatar_data && $use_default) {
        $avatar_data = $pluginapi->userAvatarExists(POP_AVATAR_GENERICAVATARUSER, array('source' => 'photo'));
    }

    if ($avatar_data) {
        $size = @getimagesize($avatar_data['file']);
        return array(
            'src' => $avatar_data['url'],
            'width' => $size[0],
            'height' => $size[1],
        );
    }
    
    return array();
}

function gdAvatarExtractUrl($avatar)
{
        
    // Remove the user-avatar-pic.php and the params, but only if it is there (not with the profile picture)
    $imgsrc = getHtmlAttribute($avatar, 'img', 'src');
    // if (strpos($imgsrc, 'user-avatar-pic.php') !== false) {

    //     $imgsrc = substr($imgsrc, strpos($imgsrc, 'src=') + 4);
    //     $imgsrc = substr($imgsrc, 0, strpos($imgsrc, '&'));
    // }

    return $imgsrc;
}


/**
 * Return the default avatar
 */
// Hook is added through the Plug-in (interface) implementation
// \PoP\Root\App::addFilter('gd_avatar_default', 'getDefaultAvatar', 1, 5);
function getDefaultAvatar($avatar, $id_or_email, $size, $default, $alt)
{

    // Return the avatar for the default avatar user
    $pluginapi = PoP_Avatar_FunctionsAPIFactory::getInstance();
    return $pluginapi->fetchAvatar(
        array(
            'item_id' => POP_AVATAR_GENERICAVATARUSER,
            'width' => $size,
            'height' => $size,
            'alt' => $alt,
        )
    );
}
