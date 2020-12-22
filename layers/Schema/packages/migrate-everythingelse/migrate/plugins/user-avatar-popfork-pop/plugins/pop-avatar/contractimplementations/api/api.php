<?php

class UserAvatarPoP_FunctionsAPI extends PoP_Avatar_FunctionsAPI_Base implements PoP_Avatar_FunctionsAPI
{
    public function deleteFiles($user_id)
    {
        user_avatar_delete_files($user_id);
    }

    public function saveFilename($user_id, $original_filename)
    {
        gd_useravatar_save_filename($user_id, $original_filename);
    }

    public function deleteFilename($user_id)
    {
        gd_useravatar_delete_filename($user_id);
    }

    public function getAvatarSizes()
    {
        return gd_useravatar_avatar_sizes();
    }

    public function fetchAvatar($args = '')
    {
        return user_avatar_fetch_avatar($args);
    }

    public function userAvatarExists($id, $args = '')
    {
        return user_avatar_avatar_exists($id, $args);
    }

    public function getFilename($user_id)
    {
        return gd_useravatar_get_filename($user_id);
    }

    public function getUploadPath()
    {
        return gd_get_avatar_upload_path();
    }

    public function useMeta()
    {
        return USERAVATARPOP_USEMETA;
    }

    public function getAvatar($id_or_email, $size = 96, $default = '', $alt = '', $args = null)
    {
        // This is a WordPress function, not a user-avatar function
        return get_avatar($id_or_email, $size, $default, $alt, $args);
    }
}

/**
 * Initialize
 */
new UserAvatarPoP_FunctionsAPI();
