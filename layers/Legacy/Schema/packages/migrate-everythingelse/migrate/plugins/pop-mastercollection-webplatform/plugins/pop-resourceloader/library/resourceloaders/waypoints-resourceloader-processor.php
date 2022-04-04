<?php

class PoP_CoreProcessors_WaypointsResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public final const RESOURCE_WAYPOINTS = 'waypoints';
    public final const RESOURCE_WAYPOINTSFETCHMORE = 'waypoints-fetchmore';
    public final const RESOURCE_WAYPOINTSHISTORYSTATE = 'waypoints-historystate';
    public final const RESOURCE_WAYPOINTSTHEATER = 'waypoints-theater';
    public final const RESOURCE_WAYPOINTSTOGGLECLASS = 'waypoints-toggleclass';
    public final const RESOURCE_WAYPOINTSTOGGLECOLLAPSE = 'waypoints-togglecollapse';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_WAYPOINTS],
            [self::class, self::RESOURCE_WAYPOINTSFETCHMORE],
            [self::class, self::RESOURCE_WAYPOINTSHISTORYSTATE],
            [self::class, self::RESOURCE_WAYPOINTSTHEATER],
            [self::class, self::RESOURCE_WAYPOINTSTOGGLECLASS],
            [self::class, self::RESOURCE_WAYPOINTSTOGGLECOLLAPSE],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_WAYPOINTS => 'waypoints',
            self::RESOURCE_WAYPOINTSFETCHMORE => 'waypoints-fetchmore',
            self::RESOURCE_WAYPOINTSHISTORYSTATE => 'waypoints-historystate',
            self::RESOURCE_WAYPOINTSTHEATER => 'waypoints-theater',
            self::RESOURCE_WAYPOINTSTOGGLECLASS => 'waypoints-toggleclass',
            self::RESOURCE_WAYPOINTSTOGGLECOLLAPSE => 'waypoints-togglecollapse',
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
        return POP_MASTERCOLLECTIONWEBPLATFORM_DIR.'/js/'.$subpath.'libraries/3rdparties/waypoints';
    }
    
    public function getAssetPath(array $resource)
    {
        return POP_MASTERCOLLECTIONWEBPLATFORM_DIR.'/js/libraries/3rdparties/waypoints/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_MASTERCOLLECTIONWEBPLATFORM_URL.'/js/'.$subpath.'libraries/3rdparties/waypoints';
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_WAYPOINTS => array(
                'Waypoints',
            ),
            self::RESOURCE_WAYPOINTSFETCHMORE => array(
                'WaypointsFetchMore',
            ),
            self::RESOURCE_WAYPOINTSHISTORYSTATE => array(
                'WaypointsHistoryState',
            ),
            self::RESOURCE_WAYPOINTSTHEATER => array(
                'WaypointsTheater',
            ),
            self::RESOURCE_WAYPOINTSTOGGLECLASS => array(
                'WaypointsToggleClass',
            ),
            self::RESOURCE_WAYPOINTSTOGGLECOLLAPSE => array(
                'WaypointsToggleCollapse',
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
            case self::RESOURCE_WAYPOINTS:
                $dependencies[] = [PoP_CoreProcessors_VendorJSResourceLoaderProcessor::class, PoP_CoreProcessors_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_WAYPOINTS];
                break;
        }

        return $dependencies;
    }
}


