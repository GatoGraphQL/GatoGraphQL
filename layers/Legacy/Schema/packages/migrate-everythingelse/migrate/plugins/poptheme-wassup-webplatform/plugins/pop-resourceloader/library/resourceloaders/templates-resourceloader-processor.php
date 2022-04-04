<?php

class PoPTheme_Wassup_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor
{
    public final const RESOURCE_FRAME_BACKGROUND = 'frame_background';
    public final const RESOURCE_FRAME_TOPSIMPLE = 'frame_topsimple';
    public final const RESOURCE_FRAME_SIDE = 'frame_side';
    public final const RESOURCE_FRAME_TOP = 'frame_top';
    public final const RESOURCE_EXTENSIONPAGESECTIONFRAMEOPTIONS = 'extensionpagesectionframeoptions';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_FRAME_BACKGROUND],
            [self::class, self::RESOURCE_FRAME_TOPSIMPLE],
            [self::class, self::RESOURCE_FRAME_SIDE],
            [self::class, self::RESOURCE_FRAME_TOP],
            [self::class, self::RESOURCE_EXTENSIONPAGESECTIONFRAMEOPTIONS],
        ];
    }
    
    public function getTemplate(array $resource)
    {
        $templates = array(
            self::RESOURCE_FRAME_BACKGROUND => POP_TEMPLATE_FRAME_BACKGROUND,
            self::RESOURCE_FRAME_TOPSIMPLE => POP_TEMPLATE_FRAME_TOPSIMPLE,
            self::RESOURCE_FRAME_SIDE => POP_TEMPLATE_FRAME_SIDE,
            self::RESOURCE_FRAME_TOP => POP_TEMPLATE_FRAME_TOP,
            self::RESOURCE_EXTENSIONPAGESECTIONFRAMEOPTIONS => POP_TEMPLATE_EXTENSIONPAGESECTIONFRAMEOPTIONS,
        );
        return $templates[$resource[1]];
    }
    
    public function getVersion(array $resource)
    {
        return POPTHEME_WASSUPWEBPLATFORM_VERSION;
    }
    
    public function getPath(array $resource)
    {
        return POPTHEME_WASSUPWEBPLATFORM_URL.'/js/dist/templates';
    }
    
    public function getDir(array $resource)
    {
        return POPTHEME_WASSUPWEBPLATFORM_DIR.'/js/dist/templates';
    }
}


