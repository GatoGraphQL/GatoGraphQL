<?php

class PoP_FrontEnd_DynamicJSSPAResourceLoaderProcessor extends PoP_DynamicJSResourceLoaderProcessor
{
    public final const RESOURCE_SPARESOURCELOADERCONFIG = 'resourceloader-config';
    public final const RESOURCE_SPARESOURCELOADERCONFIG_RESOURCES = 'resourceloader-config-resources';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_SPARESOURCELOADERCONFIG],
            [self::class, self::RESOURCE_SPARESOURCELOADERCONFIG_RESOURCES],
        ];
    }
    
    public function getFilename(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_SPARESOURCELOADERCONFIG:
                global $pop_sparesourceloader_configfile;
                return $pop_sparesourceloader_configfile->getFilename();
            
            case self::RESOURCE_SPARESOURCELOADERCONFIG_RESOURCES:
                global $pop_sparesourceloader_resources_configfile;
                return $pop_sparesourceloader_resources_configfile->getFilename();
        }

        return parent::getFilename($resource);
    }
    
    public function getDir(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_SPARESOURCELOADERCONFIG:
                global $pop_sparesourceloader_configfile;
                return $pop_sparesourceloader_configfile->getDir();
            
            case self::RESOURCE_SPARESOURCELOADERCONFIG_RESOURCES:
                global $pop_sparesourceloader_resources_configfile;
                return $pop_sparesourceloader_resources_configfile->getDir();
        }
    
        return parent::getDir($resource);
    }
    
    public function getFileUrl(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_SPARESOURCELOADERCONFIG:
                global $pop_sparesourceloader_configfile;
                return $pop_sparesourceloader_configfile->getFileurl();
            
            case self::RESOURCE_SPARESOURCELOADERCONFIG_RESOURCES:
                global $pop_sparesourceloader_resources_configfile;
                return $pop_sparesourceloader_resources_configfile->getFileurl();
        }

        return parent::getFileUrl($resource);
    }
    
    public function isDefer(array $resource, $model_instance_id)
    {
        switch ($resource[1]) {
            case self::RESOURCE_SPARESOURCELOADERCONFIG_RESOURCES:
                return true;
        }

        return parent::isDefer($resource, $model_instance_id);
    }

    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_SPARESOURCELOADERCONFIG:
                $dependencies[] = [PoP_SPAResourceLoader_JSSPAResourceLoaderProcessor::class, PoP_SPAResourceLoader_JSSPAResourceLoaderProcessor::RESOURCE_SPARESOURCELOADER];
                break;

            case self::RESOURCE_SPARESOURCELOADERCONFIG_RESOURCES:

                $dependencies[] = [self::class, self::RESOURCE_SPARESOURCELOADERCONFIG];
                break;
        }

        return $dependencies;
    }
}


