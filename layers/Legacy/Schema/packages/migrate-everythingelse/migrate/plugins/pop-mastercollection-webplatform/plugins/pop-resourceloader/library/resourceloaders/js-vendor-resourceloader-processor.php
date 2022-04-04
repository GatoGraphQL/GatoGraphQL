<?php

class PoP_CoreProcessors_VendorJSResourceLoaderProcessor extends PoP_VendorJSResourceLoaderProcessor
{
    public final const RESOURCE_EXTERNAL_GOOGLEMAPS = 'external-googlemaps';
    public final const RESOURCE_EXTERNAL_GMAPS = 'external-gmaps';
    public final const RESOURCE_EXTERNAL_PERFECTSCROLLBAR = 'external-perfectscrollbar';
    public final const RESOURCE_EXTERNAL_JQUERYCOOKIE = 'external-jquerycookie';
    public final const RESOURCE_EXTERNAL_WAYPOINTS = 'external-waypoints';
    public final const RESOURCE_EXTERNAL_TYPEAHEAD = 'external-typeahead';
    public final const RESOURCE_EXTERNAL_FULLSCREEN = 'external-fullscreen';
    public final const RESOURCE_EXTERNAL_DYNAMICMAXHEIGHT = 'external-dynamicmaxheight';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_EXTERNAL_GOOGLEMAPS],
            [self::class, self::RESOURCE_EXTERNAL_GMAPS],
            [self::class, self::RESOURCE_EXTERNAL_PERFECTSCROLLBAR],
            [self::class, self::RESOURCE_EXTERNAL_JQUERYCOOKIE],
            [self::class, self::RESOURCE_EXTERNAL_WAYPOINTS],
            [self::class, self::RESOURCE_EXTERNAL_TYPEAHEAD],
            [self::class, self::RESOURCE_EXTERNAL_FULLSCREEN],
            [self::class, self::RESOURCE_EXTERNAL_DYNAMICMAXHEIGHT],
        ];
    }
    
    public function getFileUrl(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_GOOGLEMAPS:
                return getGooglemapsUrl();
        }

        return parent::getFileUrl($resource);
    }
    
    public function canBundle(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_GOOGLEMAPS:
                return false;
        }

        return parent::canBundle($resource);
    }
    
    public function getFilename(array $resource)
    {
        $use_cdn = PoP_WebPlatform_ServerUtils::accessExternalcdnResources();
        $filenames = array(
            self::RESOURCE_EXTERNAL_GMAPS => 'gmaps'.(!$use_cdn ? '.0.4.24' : ''),
            self::RESOURCE_EXTERNAL_PERFECTSCROLLBAR => 'perfect-scrollbar.jquery'.(!$use_cdn ? '.0.6.11' : ''),
            self::RESOURCE_EXTERNAL_JQUERYCOOKIE => 'jquery.cookie'.(!$use_cdn ? '.1.4.1' : ''),
            self::RESOURCE_EXTERNAL_WAYPOINTS => 'jquery.waypoints'.(!$use_cdn ? '.4.0.1' : ''),
            self::RESOURCE_EXTERNAL_TYPEAHEAD => 'typeahead.bundle'.(!$use_cdn ? '.0.11.1' : ''),
            self::RESOURCE_EXTERNAL_FULLSCREEN => 'jquery.fullscreen',
            self::RESOURCE_EXTERNAL_DYNAMICMAXHEIGHT => 'jquery.dynamicmaxheight',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_MASTERCOLLECTIONWEBPLATFORM_VENDORRESOURCESVERSION;
    }
    
    public function getDir(array $resource)
    {

        // Scripts not under a CDN
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_DYNAMICMAXHEIGHT:
                return POP_MASTERCOLLECTIONWEBPLATFORM_DIR.'/js/includes';
        }
    
        return POP_MASTERCOLLECTIONWEBPLATFORM_DIR.'/js/includes/cdn';
    }
    
    public function getAssetPath(array $resource)
    {
        $filenames = array(
            self::RESOURCE_EXTERNAL_GMAPS => 'gmaps.0.4.24',
            self::RESOURCE_EXTERNAL_PERFECTSCROLLBAR => 'perfect-scrollbar.jquery.0.6.11',
            self::RESOURCE_EXTERNAL_JQUERYCOOKIE => 'jquery.cookie.1.4.1',
            self::RESOURCE_EXTERNAL_WAYPOINTS => 'jquery.waypoints.4.0.1',
            self::RESOURCE_EXTERNAL_TYPEAHEAD => 'typeahead.bundle.0.11.1',
            self::RESOURCE_EXTERNAL_FULLSCREEN => 'jquery.fullscreen',
            self::RESOURCE_EXTERNAL_DYNAMICMAXHEIGHT => 'jquery.dynamicmaxheight',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $this->getDir($resource).'/'.$filename.$this->getSuffix($resource);
        }

        return parent::getAssetPath($resource);
    }
    
    public function getSuffix(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_GMAPS:
            case self::RESOURCE_EXTERNAL_PERFECTSCROLLBAR:
            case self::RESOURCE_EXTERNAL_JQUERYCOOKIE:
            case self::RESOURCE_EXTERNAL_WAYPOINTS:
            case self::RESOURCE_EXTERNAL_TYPEAHEAD:
            case self::RESOURCE_EXTERNAL_DYNAMICMAXHEIGHT:
                return '.min.js';

            case self::RESOURCE_EXTERNAL_FULLSCREEN:
                return '-min.js';
        }

        return parent::getSuffix($resource);
    }
    
    public function getPath(array $resource)
    {
        if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
            $paths = array(
                self::RESOURCE_EXTERNAL_GMAPS => 'https://cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.24',
                self::RESOURCE_EXTERNAL_PERFECTSCROLLBAR => 'https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/0.6.11/js/min',
                self::RESOURCE_EXTERNAL_JQUERYCOOKIE => 'https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1',
                self::RESOURCE_EXTERNAL_WAYPOINTS => 'https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1',
                self::RESOURCE_EXTERNAL_TYPEAHEAD => 'https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1',
                self::RESOURCE_EXTERNAL_FULLSCREEN => 'https://cdnjs.cloudflare.com/ajax/libs/jquery-fullscreen-plugin/1.1.4',
            );
            if ($path = $paths[$resource[1]]) {
                return $path;
            }
        }

        // Scripts not under a CDN
        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_DYNAMICMAXHEIGHT:
                return POP_MASTERCOLLECTIONWEBPLATFORM_URL.'/js/includes';
        }

        return POP_MASTERCOLLECTIONWEBPLATFORM_URL.'/js/includes/cdn';
    }
    
    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);

        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_GMAPS:
                $dependencies[] = [self::class, self::RESOURCE_EXTERNAL_GOOGLEMAPS];
                break;
                
            case self::RESOURCE_EXTERNAL_DYNAMICMAXHEIGHT:
                $dependencies[] = [PoP_CoreProcessors_VendorCSSResourceLoaderProcessor::class, PoP_CoreProcessors_VendorCSSResourceLoaderProcessor::RESOURCE_EXTERNAL_CSS_DYNAMICMAXHEIGHT];
                break;

            case self::RESOURCE_EXTERNAL_PERFECTSCROLLBAR:
                $dependencies[] = [PoP_CoreProcessors_VendorCSSResourceLoaderProcessor::class, PoP_CoreProcessors_VendorCSSResourceLoaderProcessor::RESOURCE_EXTERNAL_CSS_PERFECTSCROLLBAR];
                break;
        }

        return $dependencies;
    }
}


