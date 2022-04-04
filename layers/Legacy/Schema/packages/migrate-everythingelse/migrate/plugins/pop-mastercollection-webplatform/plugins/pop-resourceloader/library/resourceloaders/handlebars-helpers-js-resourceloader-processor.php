<?php

class PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor extends PoP_HandlebarsHelpersJSResourceLoaderProcessor
{
    public final const RESOURCE_HANDLEBARSHELPERS_ARRAYS = 'handlebars-helpers-arrays';
    public final const RESOURCE_HANDLEBARSHELPERS_COMPARE = 'handlebars-helpers-compare';
    public final const RESOURCE_HANDLEBARSHELPERS_DATE = 'handlebars-helpers-date';
    public final const RESOURCE_HANDLEBARSHELPERS_DBOBJECT = 'handlebars-helpers-dbobject';
    public final const RESOURCE_HANDLEBARSHELPERS_LABELS = 'handlebars-helpers-labels';
    public final const RESOURCE_HANDLEBARSHELPERS_MOD = 'handlebars-helpers-mod';
    public final const RESOURCE_HANDLEBARSHELPERS_MULTILAYOUT = 'handlebars-helpers-multilayout';
    public final const RESOURCE_HANDLEBARSHELPERS_OPERATORS = 'handlebars-helpers-operators';
    public final const RESOURCE_HANDLEBARSHELPERS_SHOWMORE = 'handlebars-helpers-showmore';
    public final const RESOURCE_HANDLEBARSHELPERS_STATUS = 'handlebars-helpers-status';
    public final const RESOURCE_HANDLEBARSHELPERS_REPLACE = 'handlebars-helpers-replace';
    public final const RESOURCE_HANDLEBARSHELPERS_URLPARAM = 'handlebars-helpers-urlparam';
    public final const RESOURCE_HANDLEBARSHELPERS_LATESTCOUNT = 'helpers-handlebars-latestcount';
    public final const RESOURCE_HANDLEBARSHELPERS_FEEDBACKMESSAGE = 'helpers-handlebars-feedbackmessage';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_HANDLEBARSHELPERS_ARRAYS],
            [self::class, self::RESOURCE_HANDLEBARSHELPERS_COMPARE],
            [self::class, self::RESOURCE_HANDLEBARSHELPERS_DATE],
            [self::class, self::RESOURCE_HANDLEBARSHELPERS_DBOBJECT],
            [self::class, self::RESOURCE_HANDLEBARSHELPERS_LABELS],
            [self::class, self::RESOURCE_HANDLEBARSHELPERS_MOD],
            [self::class, self::RESOURCE_HANDLEBARSHELPERS_MULTILAYOUT],
            [self::class, self::RESOURCE_HANDLEBARSHELPERS_OPERATORS],
            [self::class, self::RESOURCE_HANDLEBARSHELPERS_SHOWMORE],
            [self::class, self::RESOURCE_HANDLEBARSHELPERS_STATUS],
            [self::class, self::RESOURCE_HANDLEBARSHELPERS_REPLACE],
            [self::class, self::RESOURCE_HANDLEBARSHELPERS_URLPARAM],
            [self::class, self::RESOURCE_HANDLEBARSHELPERS_LATESTCOUNT],
            [self::class, self::RESOURCE_HANDLEBARSHELPERS_FEEDBACKMESSAGE],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_HANDLEBARSHELPERS_ARRAYS => 'arrays',
            self::RESOURCE_HANDLEBARSHELPERS_COMPARE => 'compare',
            self::RESOURCE_HANDLEBARSHELPERS_DATE => 'date',
            self::RESOURCE_HANDLEBARSHELPERS_DBOBJECT => 'dbobject',
            self::RESOURCE_HANDLEBARSHELPERS_LABELS => 'labels',
            self::RESOURCE_HANDLEBARSHELPERS_MOD => 'mod',
            self::RESOURCE_HANDLEBARSHELPERS_MULTILAYOUT => 'multilayout',
            self::RESOURCE_HANDLEBARSHELPERS_OPERATORS => 'operators',
            self::RESOURCE_HANDLEBARSHELPERS_SHOWMORE => 'showmore',
            self::RESOURCE_HANDLEBARSHELPERS_STATUS => 'status',
            self::RESOURCE_HANDLEBARSHELPERS_REPLACE => 'replace',
            self::RESOURCE_HANDLEBARSHELPERS_URLPARAM => 'urlparam',
            self::RESOURCE_HANDLEBARSHELPERS_LATESTCOUNT => 'latestcount',
            self::RESOURCE_HANDLEBARSHELPERS_FEEDBACKMESSAGE => 'feedbackmessage',
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
        return POP_MASTERCOLLECTIONWEBPLATFORM_DIR.'/js/'.$subpath.'libraries/handlebars-helpers';
    }
    
    public function getAssetPath(array $resource)
    {
        return POP_MASTERCOLLECTIONWEBPLATFORM_DIR.'/js/libraries/handlebars-helpers/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_MASTERCOLLECTIONWEBPLATFORM_URL.'/js/'.$subpath.'libraries/handlebars-helpers';
    }
    
    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_HANDLEBARSHELPERS_FEEDBACKMESSAGE:
                $dependencies[] = [PoP_CoreProcessors_ResourceLoaderProcessor::class, PoP_CoreProcessors_ResourceLoaderProcessor::RESOURCE_FEEDBACKMESSAGE];
                break;
        }

        return $dependencies;
    }
}


