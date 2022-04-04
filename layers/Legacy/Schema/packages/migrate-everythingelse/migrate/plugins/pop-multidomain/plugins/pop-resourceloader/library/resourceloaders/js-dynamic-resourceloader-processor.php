<?php

class PoP_MultiDomain_DynamicJSResourceLoaderProcessor extends PoP_DynamicJSResourceLoaderProcessor
{
    public final const RESOURCE_MULTIDOMAIN_INITDOMAINSCRIPTS = 'multidomain-init-domain-scripts';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_MULTIDOMAIN_INITDOMAINSCRIPTS],
        ];
    }
    
    public function getFilename(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_MULTIDOMAIN_INITDOMAINSCRIPTS:
                global $pop_multidomain_initdomainscripts_configfile;
                return $pop_multidomain_initdomainscripts_configfile->getFilename();
        }

        return parent::getFilename($resource);
    }
    
    public function getDir(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_MULTIDOMAIN_INITDOMAINSCRIPTS:
                global $pop_multidomain_initdomainscripts_configfile;
                return $pop_multidomain_initdomainscripts_configfile->getDir();
        }
    
        return parent::getDir($resource);
    }
    
    public function getAssetPath(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_MULTIDOMAIN_INITDOMAINSCRIPTS:
                global $pop_multidomain_initdomainscripts_configfile;
                return $pop_multidomain_initdomainscripts_configfile->getFilepath();
        }

        return parent::getAssetPath($resource);
    }
    
    public function getFileUrl(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_MULTIDOMAIN_INITDOMAINSCRIPTS:
                global $pop_multidomain_initdomainscripts_configfile;
                return $pop_multidomain_initdomainscripts_configfile->getFileurl();
        }

        return parent::getFileUrl($resource);
    }
    
    public function getDecoratedResources(array $resource)
    {
        $decorated = parent::getDecoratedResources($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_MULTIDOMAIN_INITDOMAINSCRIPTS:
                $decorated[] = [PoP_MultiDomain_JSResourceLoaderProcessor::class, PoP_MultiDomain_JSResourceLoaderProcessor::RESOURCE_MULTIDOMAIN];
                break;
        }

        return $decorated;
    }
}


