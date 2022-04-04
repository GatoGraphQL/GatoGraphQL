<?php

class PoPTheme_Wassup_VendorCSSResourceLoaderProcessor extends PoP_VendorCSSResourceLoaderProcessor
{
    public final const RESOURCE_EXTERNAL_CSS_FONTAWESOME = 'css-external-fontawesome';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_EXTERNAL_CSS_FONTAWESOME],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $use_cdn = PoP_WebPlatform_ServerUtils::accessExternalcdnResources();
        $filenames = array(
            self::RESOURCE_EXTERNAL_CSS_FONTAWESOME => 'font-awesome'.(!$use_cdn ? '.4.7.0' : ''),
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POPTHEME_WASSUP_VENDORRESOURCESVERSION;
    }
    
    public function getDir(array $resource)
    {
        return POPTHEME_WASSUP_DIR.'/css/includes/cdn';
    }
    
    public function getReferencedFiles(array $resource)
    {
        $referenced_files = parent::getReferencedFiles($resource);

        switch ($resource[1]) {
            case self::RESOURCE_EXTERNAL_CSS_FONTAWESOME:
                $referenced_files[] = '../fonts/fontawesome-webfont.eot';
                $referenced_files[] = '../fonts/fontawesome-webfont.woff2';
                $referenced_files[] = '../fonts/fontawesome-webfont.woff';
                $referenced_files[] = '../fonts/fontawesome-webfont.ttf';
                $referenced_files[] = '../fonts/fontawesome-webfont.svg';
                break;
        }

        return $referenced_files;
    }
    
    public function getAssetPath(array $resource)
    {
        if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
            $filenames = array(
                self::RESOURCE_EXTERNAL_CSS_FONTAWESOME => 'font-awesome.4.7.0',
            );
            if ($filename = $filenames[$resource[1]]) {
                return $this->getDir($resource).'/'.$filename.$this->getSuffix($resource);
            }
        }

        return parent::getAssetPath($resource);
    }
    
    public function getPath(array $resource)
    {
        if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {
            $paths = array(
                self::RESOURCE_EXTERNAL_CSS_FONTAWESOME => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/',
            );
            if ($path = $paths[$resource[1]]) {
                return $path;
            }
        }

        return POPTHEME_WASSUP_URL.'/css/includes/cdn';
    }
}


