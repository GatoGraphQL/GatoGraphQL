<?php

class PoP_SPAResourceLoader_JSSPAResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public final const RESOURCE_CODESPLITJSLIBRARYMANAGER = 'codesplit-jslibrary-manager';
    public final const RESOURCE_SPARESOURCELOADER = 'sparesourceloader';
    public final const RESOURCE_SPARESOURCELOADERMANAGEROVERRIDE = 'sparesourceloader-manager-override';
    public final const RESOURCE_HANDLEBARSHELPERSSPARESOURCELOADERHOOKS = 'handlebarshelpers-sparesourceloader-hooks';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_CODESPLITJSLIBRARYMANAGER],
            [self::class, self::RESOURCE_SPARESOURCELOADER],
            [self::class, self::RESOURCE_SPARESOURCELOADERMANAGEROVERRIDE],
            [self::class, self::RESOURCE_HANDLEBARSHELPERSSPARESOURCELOADERHOOKS],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_CODESPLITJSLIBRARYMANAGER => 'codesplit-jslibrary-manager',
            self::RESOURCE_SPARESOURCELOADER => 'resourceloader',
            self::RESOURCE_SPARESOURCELOADERMANAGEROVERRIDE => 'resourceloader-manager-override',
            self::RESOURCE_HANDLEBARSHELPERSSPARESOURCELOADERHOOKS => 'handlebarshelpers-resourceloader-hooks',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_SPARESOURCELOADER_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_SPARESOURCELOADER_DIR.'/js/'.$subpath.'libraries';
    }
    
    public function getAssetPath(array $resource)
    {
        return POP_SPARESOURCELOADER_DIR.'/js/libraries/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_SPARESOURCELOADER_URL.'/js/'.$subpath.'libraries';
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_CODESPLITJSLIBRARYMANAGER => array(
                'CodeSplitJSLibraryManager',
            ),
            self::RESOURCE_SPARESOURCELOADER => array(
                'SPAResourceLoader',
            ),
            self::RESOURCE_SPARESOURCELOADERMANAGEROVERRIDE => array(
                'ManagerSPAResourceLoaderOverride',
            ),
            self::RESOURCE_HANDLEBARSHELPERSSPARESOURCELOADERHOOKS => array(
                'SPAResourceLoaderHandlebarsHelperHooks',
            ),
        );

        if ($object = $objects[$resource[1]]) {
            return $object;
        }

        return parent::getJsobjects($resource);
    }

    public function getDecoratedResources(array $resource)
    {
        $decorated = parent::getDecoratedResources($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_SPARESOURCELOADER:
                $decorated[] = [PoP_FrontEnd_JSResourceLoaderProcessor::class, PoP_FrontEnd_JSResourceLoaderProcessor::RESOURCE_POPMANAGER];
                break;

            case self::RESOURCE_CODESPLITJSLIBRARYMANAGER:
            case self::RESOURCE_HANDLEBARSHELPERSSPARESOURCELOADERHOOKS:
                $decorated[] = [PoP_FrontEnd_JSResourceLoaderProcessor::class, PoP_FrontEnd_JSResourceLoaderProcessor::RESOURCE_JSLIBRARYMANAGER];
                break;
        }

        return $decorated;
    }
}


