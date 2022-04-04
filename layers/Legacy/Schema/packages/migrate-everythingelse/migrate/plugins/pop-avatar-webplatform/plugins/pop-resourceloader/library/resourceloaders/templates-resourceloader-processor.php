<?php

class PoP_AvatarWebPlatform_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor
{
    public final const RESOURCE_LAYOUT_AUTHOR_USERPHOTO = 'layout_author_userphoto';
    public final const RESOURCE_LAYOUT_USERAVATAR = 'layout_useravatar';
    public final const RESOURCE_LAYOUTPOST_AUTHORAVATAR = 'layoutpost_authoravatar';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_LAYOUT_AUTHOR_USERPHOTO],
            [self::class, self::RESOURCE_LAYOUT_USERAVATAR],
            [self::class, self::RESOURCE_LAYOUTPOST_AUTHORAVATAR],
        ];
    }
    
    public function getTemplate(array $resource)
    {
        $templates = array(
            self::RESOURCE_LAYOUT_AUTHOR_USERPHOTO => POP_TEMPLATE_LAYOUT_AUTHOR_USERPHOTO,
            self::RESOURCE_LAYOUT_USERAVATAR => POP_TEMPLATE_LAYOUT_USERAVATAR,
            self::RESOURCE_LAYOUTPOST_AUTHORAVATAR => POP_TEMPLATE_LAYOUTPOST_AUTHORAVATAR,
        );
        return $templates[$resource[1]];
    }
    
    public function getVersion(array $resource)
    {
        return POP_AVATARWEBPLATFORM_VERSION;
    }
    
    public function getPath(array $resource)
    {
        return POP_AVATARWEBPLATFORM_URL.'/js/dist/templates';
    }
    
    public function getDir(array $resource)
    {
        return POP_AVATARWEBPLATFORM_DIR.'/js/dist/templates';
    }
}


