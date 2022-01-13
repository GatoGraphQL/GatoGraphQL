<?php

class PoP_CoreProcessors_TypeaheadResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public const RESOURCE_TYPEAHEAD = 'typeahead';
    public const RESOURCE_TYPEAHEADSEARCH = 'typeahead-search';
    public const RESOURCE_TYPEAHEADFETCHLINK = 'typeahead-fetchlink';
    public const RESOURCE_TYPEAHEADSELECTABLE = 'typeahead-selectable';
    public const RESOURCE_TYPEAHEADVALIDATE = 'typeahead-validate';
    public const RESOURCE_TYPEAHEADSTORAGE = 'typeahead-storage';
    public const RESOURCE_TYPEAHEADSUGGESTIONS = 'typeahead-suggestions';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_TYPEAHEAD],
            [self::class, self::RESOURCE_TYPEAHEADSEARCH],
            [self::class, self::RESOURCE_TYPEAHEADFETCHLINK],
            [self::class, self::RESOURCE_TYPEAHEADSELECTABLE],
            [self::class, self::RESOURCE_TYPEAHEADVALIDATE],
            [self::class, self::RESOURCE_TYPEAHEADSTORAGE],
            [self::class, self::RESOURCE_TYPEAHEADSUGGESTIONS],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_TYPEAHEAD => 'typeahead',
            self::RESOURCE_TYPEAHEADSEARCH => 'typeahead-search',
            self::RESOURCE_TYPEAHEADFETCHLINK => 'typeahead-fetchlink',
            self::RESOURCE_TYPEAHEADSELECTABLE => 'typeahead-selectable',
            self::RESOURCE_TYPEAHEADVALIDATE => 'typeahead-validate',
            self::RESOURCE_TYPEAHEADSTORAGE => 'typeahead-storage',
            self::RESOURCE_TYPEAHEADSUGGESTIONS => 'typeahead-suggestions',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_MASTERCOLLECTIONWEBPLATFORM_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_MASTERCOLLECTIONWEBPLATFORM_DIR.'/js/'.$subpath.'libraries/3rdparties/typeahead';
    }
    
    public function getAssetPath(array $resource)
    {
        return POP_MASTERCOLLECTIONWEBPLATFORM_DIR.'/js/libraries/3rdparties/typeahead/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_MASTERCOLLECTIONWEBPLATFORM_URL.'/js/'.$subpath.'libraries/3rdparties/typeahead';
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_TYPEAHEAD => array(
                'Typeahead',
            ),
            self::RESOURCE_TYPEAHEADSEARCH => array(
                'TypeaheadSearch',
            ),
            self::RESOURCE_TYPEAHEADFETCHLINK => array(
                'TypeaheadFetchLink',
            ),
            self::RESOURCE_TYPEAHEADSELECTABLE => array(
                'TypeaheadSelectable',
            ),
            self::RESOURCE_TYPEAHEADVALIDATE => array(
                'TypeaheadValidate',
            ),
            self::RESOURCE_TYPEAHEADSTORAGE => array(
                'TypeaheadStorage',
            ),
            self::RESOURCE_TYPEAHEADSUGGESTIONS => array(
                'TypeaheadSuggestions',
            ),
        );
        if ($object = $objects[$resource[1]]) {
            return $object;
        }

        return parent::getJsobjects($resource);
    }
    
    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_TYPEAHEAD:
                $dependencies[] = [PoP_CoreProcessors_VendorJSResourceLoaderProcessor::class, PoP_CoreProcessors_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_TYPEAHEAD];

                // Also add the Handlebar templates needed to render the typeahead views on runtime
                if ($typeahead_layouts = array_unique(
                    \PoP\Root\App::applyFilters(
                        'PoP_CoreProcessors_ResourceLoaderProcessor:typeahead:templates',
                        array()
                    )
                )
                ) {
                    $dependencies = array_merge(
                        $dependencies,
                        $typeahead_layouts
                    );
                }
                break;
        }

        return $dependencies;
    }
}


