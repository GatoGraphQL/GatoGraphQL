<?php

interface PoP_Avatar_FunctionsAPI
{
    public function deleteFiles($user_id);
    public function saveFilename($user_id, $original_filename);
    public function deleteFilename($user_id);
    public function getAvatarSizes();
    public function fetchAvatar($args = '');
    public function userAvatarExists($id, $args = '');
    public function getFilename($user_id);
    public function getUploadPath();
    public function useMeta();
    public function getAvatar($id_or_email, $size = 96, $default = '', $alt = '', $args = null);
}
