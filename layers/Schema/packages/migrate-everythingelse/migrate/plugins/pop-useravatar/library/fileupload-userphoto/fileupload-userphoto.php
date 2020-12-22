<?php

class GD_FileUpload_UserPhoto
{

    // $user_upload_path: Path where to upload the Avatar under the server/uploads/files folder
    protected $user_upload_path;

    public function __construct()
    {
        GD_FileUpload_UserPhotoFactory::setInstance($this);
    }

    /**
     * Copy the thumb files to a folder by User Avatar plugin, this plugin will use it later on
     */
    public function savePicture($user_id, $delete_source = false)
    {

        // Calculate upload_path from the user_id
        $user_upload_path = GD_FileUpload_Picture_Utils::getFileuploadUserPath($user_id);
            
        // The images are NOT loaded under /uploads/mesym/fileupload-userphoto, but under /uploads/fileupload-userphoto/
        // So recreate the path
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $source_files_path_base = $cmsengineapi->getContentDir().'/uploads/fileupload-userphoto/';

        // delete the previous files
        $pluginapi = PoP_Avatar_FunctionsAPIFactory::getInstance();
        $pluginapi->deleteFiles($user_id);

        // Simply copy the uploaded pictures to the corresponding folder
        $source_files_path = $source_files_path_base.$user_upload_path.DIRECTORY_SEPARATOR;
        $destination_folder = $pluginapi->getUploadPath()."{$user_id}/";
        recurseCopy($source_files_path, $destination_folder);

        // Retrieve the filename of the picture: it is the only file in the folder
        $original_folder = $source_files_path.'original/';
        $original_filename = '';
        $allfiles = scandir($original_folder);
        array_shift($allfiles); // Skip . and .. folders
        array_shift($allfiles);
        if ($filename = $allfiles[0]) {
            $original_filename = basename($filename);
        }

        // Delete source: needed to delete the images when first creating a user, since the created user_upload_path folder
        // is something like _43204930_432049320 and won't be used again
        // (In addition, there's a bug: since different users share the same user_upload_path, for it being saved in the settings cache,
        // then a 2nd user will see a 1st user's pic set by default when registering)
        if ($delete_source) {
            delTree($source_files_path);
        }

        // Save the filename in the user meta
        if ($original_filename) {
            $pluginapi->saveFilename($user_id, $original_filename);
        } else {
            // No filename => No avatar uploaded, delete the existing one
            $pluginapi->deleteFilename($user_id);
        }
    }

    /**
     * Copy the Avatar to the thumb files folder
     */
    public function copyPicture($user_id)
    {

        // Copy the images from the user avatar folder into the fileupload-userphoto folder in uploads

        // Calculate user_upload_path from the user_id
        $user_upload_path = GD_FileUpload_Picture_Utils::getFileuploadUserPath($user_id);
            
        // The images are NOT loaded under /uploads/mesym/fileupload-userphoto, but under /uploads/fileupload-userphoto/
        // So recreate the path
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $working_dir = $cmsengineapi->getContentDir().'/uploads/fileupload-userphoto/'.$user_upload_path.'/';
        delTree($working_dir);
        
        $pluginapi = PoP_Avatar_FunctionsAPIFactory::getInstance();
        $original_file_folder = $pluginapi->getUploadPath()."{$user_id}/";
        recurseCopy($original_file_folder, $working_dir);
    }
}

/**
 * Initialization
 */
new GD_FileUpload_UserPhoto();
