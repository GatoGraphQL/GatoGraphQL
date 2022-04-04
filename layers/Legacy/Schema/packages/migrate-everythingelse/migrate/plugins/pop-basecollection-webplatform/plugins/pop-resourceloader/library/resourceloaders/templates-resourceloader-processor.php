<?php

class PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor
{
    public final const RESOURCE_BLOCK = 'block';
    public final const RESOURCE_BASICBLOCK = 'basicblock';
    public final const RESOURCE_PAGESECTION_PLAIN = 'pagesection_plain';
    public final const RESOURCE_CONTENT = 'content';
    public final const RESOURCE_CONTENTMULTIPLE_INNER = 'contentmultiple_inner';
    public final const RESOURCE_CONTENTSINGLE_INNER = 'contentsingle_inner';
    public final const RESOURCE_NOCONTENT = 'nocontent';
    public final const RESOURCE_MULTIPLE = 'multiple';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_BLOCK],
            [self::class, self::RESOURCE_BASICBLOCK],
            [self::class, self::RESOURCE_PAGESECTION_PLAIN],
            [self::class, self::RESOURCE_CONTENT],
            [self::class, self::RESOURCE_CONTENTMULTIPLE_INNER],
            [self::class, self::RESOURCE_CONTENTSINGLE_INNER],
            [self::class, self::RESOURCE_NOCONTENT],
            [self::class, self::RESOURCE_MULTIPLE],
        ];
    }
    
    public function getTemplate(array $resource)
    {
        $templates = array(
            self::RESOURCE_BLOCK => POP_TEMPLATE_BLOCK,
            self::RESOURCE_BASICBLOCK => POP_TEMPLATE_BASICBLOCK,
            self::RESOURCE_PAGESECTION_PLAIN => POP_TEMPLATE_PAGESECTION_PLAIN,
            self::RESOURCE_CONTENT => POP_TEMPLATE_CONTENT,
            self::RESOURCE_CONTENTMULTIPLE_INNER => POP_TEMPLATE_CONTENTMULTIPLE_INNER,
            self::RESOURCE_CONTENTSINGLE_INNER => POP_TEMPLATE_CONTENTSINGLE_INNER,
            self::RESOURCE_NOCONTENT => POP_TEMPLATE_NOCONTENT,
            self::RESOURCE_MULTIPLE => POP_TEMPLATE_MULTIPLE,
        );
        return $templates[$resource[1]];
    }
    
    public function getVersion(array $resource)
    {
        return POP_BASECOLLECTIONWEBPLATFORM_VERSION;
    }
    
    public function getPath(array $resource)
    {
        return POP_BASECOLLECTIONWEBPLATFORM_URL.'/js/dist/templates';
    }
    
    public function getDir(array $resource)
    {
        return POP_BASECOLLECTIONWEBPLATFORM_DIR.'/js/dist/templates';
    }
}


