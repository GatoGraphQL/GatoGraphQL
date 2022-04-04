<?php

class PoP_UserAvatarWebPlatform_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor
{
    public final const RESOURCE_LAYOUT_LOGGEDINUSERAVATAR = 'layout_loggedinuseravatar';
    public final const RESOURCE_FILEUPLOAD_PICTURE = 'fileupload_picture';
    public final const RESOURCE_FILEUPLOAD_PICTURE_UPLOAD = 'fileupload_picture_upload';
    public final const RESOURCE_FILEUPLOAD_PICTURE_DOWNLOAD = 'fileupload_picture_download';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_LAYOUT_LOGGEDINUSERAVATAR],
            [self::class, self::RESOURCE_FILEUPLOAD_PICTURE],
            [self::class, self::RESOURCE_FILEUPLOAD_PICTURE_UPLOAD],
            [self::class, self::RESOURCE_FILEUPLOAD_PICTURE_DOWNLOAD],
        ];
    }
    
    public function getTemplate(array $resource)
    {
        $templates = array(
            self::RESOURCE_LAYOUT_LOGGEDINUSERAVATAR => POP_TEMPLATE_LAYOUT_LOGGEDINUSERAVATAR,
            self::RESOURCE_FILEUPLOAD_PICTURE => POP_TEMPLATE_FILEUPLOAD_PICTURE,
            self::RESOURCE_FILEUPLOAD_PICTURE_UPLOAD => POP_TEMPLATE_FILEUPLOAD_PICTURE_UPLOAD,
            self::RESOURCE_FILEUPLOAD_PICTURE_DOWNLOAD => POP_TEMPLATE_FILEUPLOAD_PICTURE_DOWNLOAD,
        );
        return $templates[$resource[1]];
    }
    
    public function getVersion(array $resource)
    {
        return POP_USERAVATARWEBPLATFORM_VERSION;
    }
    
    public function getPath(array $resource)
    {
        return POP_USERAVATARWEBPLATFORM_URL.'/js/dist/templates';
    }
    
    public function getDir(array $resource)
    {
        return POP_USERAVATARWEBPLATFORM_DIR.'/js/dist/templates';
    }
}


