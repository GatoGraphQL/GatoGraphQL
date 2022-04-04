<?php

class PoP_Locations_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor
{
    public final const RESOURCE_FORMCOMPONENT_TYPEAHEADMAP = 'formcomponent_typeaheadmap';
    public final const RESOURCE_LAYOUT_LOCATIONADDRESS = 'layout_locationaddress';
    public final const RESOURCE_LAYOUT_LOCATIONNAME = 'layout_locationname';
    public final const RESOURCE_LAYOUT_LOCATIONS = 'layout_locations';
    public final const RESOURCE_LAYOUTLOCATION_TYPEAHEAD_COMPONENT = 'layoutlocation_typeahead_component';
    public final const RESOURCE_LAYOUTLOCATION_CARD = 'layoutlocation_card';
    public final const RESOURCE_MAP = 'map';
    public final const RESOURCE_MAP_ADDMARKER = 'map_addmarker';
    public final const RESOURCE_MAP_DIV = 'map_div';
    public final const RESOURCE_MAP_INDIVIDUAL = 'map_individual';
    public final const RESOURCE_MAP_INNER = 'map_inner';
    public final const RESOURCE_MAP_SCRIPT = 'map_script';
    public final const RESOURCE_MAP_SCRIPT_DRAWMARKERS = 'map_script_drawmarkers';
    public final const RESOURCE_MAP_SCRIPT_MARKERS = 'map_script_markers';
    public final const RESOURCE_MAP_SCRIPT_RESETMARKERS = 'map_script_resetmarkers';
    public final const RESOURCE_MAP_SCRIPTCUSTOMIZATION_POST = 'map_scriptcustomization_post';
    public final const RESOURCE_MAP_SCRIPTCUSTOMIZATION_USER = 'map_scriptcustomization_user';
    public final const RESOURCE_MAP_STATICIMAGE = 'map_staticimage';
    public final const RESOURCE_MAP_STATICIMAGE_URLPARAM = 'map_staticimage_urlparam';
    public final const RESOURCE_MAP_STATICIMAGE_LOCATIONS = 'map_staticimage_locations';
    public final const RESOURCE_SCRIPT_TRIGGERTYPEAHEADSELECT_LOCATION = 'script_triggertypeaheadselect_location';
    public final const RESOURCE_VIEWCOMPONENT_LOCATIONLINK = 'viewcomponent_locationlink';
    public final const RESOURCE_VIEWCOMPONENT_LOCATIONBUTTON = 'viewcomponent_locationbutton';
    public final const RESOURCE_VIEWCOMPONENT_LOCATIONBUTTONINNER = 'viewcomponent_locationbuttoninner';

    public function getResourcesToProcess()
    {
        return array(
            [self::class, self::RESOURCE_FORMCOMPONENT_TYPEAHEADMAP],
            [self::class, self::RESOURCE_LAYOUT_LOCATIONADDRESS],
            [self::class, self::RESOURCE_LAYOUT_LOCATIONNAME],
            [self::class, self::RESOURCE_LAYOUT_LOCATIONS],
            [self::class, self::RESOURCE_LAYOUTLOCATION_TYPEAHEAD_COMPONENT],
            [self::class, self::RESOURCE_LAYOUTLOCATION_CARD],
            [self::class, self::RESOURCE_MAP],
            [self::class, self::RESOURCE_MAP_ADDMARKER],
            [self::class, self::RESOURCE_MAP_DIV],
            [self::class, self::RESOURCE_MAP_INDIVIDUAL],
            [self::class, self::RESOURCE_MAP_INNER],
            [self::class, self::RESOURCE_MAP_SCRIPT],
            [self::class, self::RESOURCE_MAP_SCRIPT_DRAWMARKERS],
            [self::class, self::RESOURCE_MAP_SCRIPT_MARKERS],
            [self::class, self::RESOURCE_MAP_SCRIPT_RESETMARKERS],
            [self::class, self::RESOURCE_MAP_SCRIPTCUSTOMIZATION_POST],
            [self::class, self::RESOURCE_MAP_SCRIPTCUSTOMIZATION_USER],
            [self::class, self::RESOURCE_MAP_STATICIMAGE],
            [self::class, self::RESOURCE_MAP_STATICIMAGE_URLPARAM],
            [self::class, self::RESOURCE_MAP_STATICIMAGE_LOCATIONS],
            [self::class, self::RESOURCE_SCRIPT_TRIGGERTYPEAHEADSELECT_LOCATION],
            [self::class, self::RESOURCE_VIEWCOMPONENT_LOCATIONLINK],
            [self::class, self::RESOURCE_VIEWCOMPONENT_LOCATIONBUTTON],
            [self::class, self::RESOURCE_VIEWCOMPONENT_LOCATIONBUTTONINNER],
        );
    }
    
    public function getTemplate(array $resource)
    {
        $templates = array(
            self::RESOURCE_FORMCOMPONENT_TYPEAHEADMAP => POP_TEMPLATE_FORMCOMPONENT_TYPEAHEADMAP,
            self::RESOURCE_LAYOUT_LOCATIONADDRESS => POP_TEMPLATE_LAYOUT_LOCATIONADDRESS,
            self::RESOURCE_LAYOUT_LOCATIONNAME => POP_TEMPLATE_LAYOUT_LOCATIONNAME,
            self::RESOURCE_LAYOUT_LOCATIONS => POP_TEMPLATE_LAYOUT_LOCATIONS,
            self::RESOURCE_LAYOUTLOCATION_TYPEAHEAD_COMPONENT => POP_TEMPLATE_LAYOUTLOCATION_TYPEAHEAD_COMPONENT,
            self::RESOURCE_LAYOUTLOCATION_CARD => POP_TEMPLATE_LAYOUTLOCATION_CARD,
            self::RESOURCE_MAP => POP_TEMPLATE_MAP,
            self::RESOURCE_MAP_ADDMARKER => POP_TEMPLATE_MAP_ADDMARKER,
            self::RESOURCE_MAP_DIV => POP_TEMPLATE_MAP_DIV,
            self::RESOURCE_MAP_INDIVIDUAL => POP_TEMPLATE_MAP_INDIVIDUAL,
            self::RESOURCE_MAP_INNER => POP_TEMPLATE_MAP_INNER,
            self::RESOURCE_MAP_SCRIPT => POP_TEMPLATE_MAP_SCRIPT,
            self::RESOURCE_MAP_SCRIPT_DRAWMARKERS => POP_TEMPLATE_MAP_SCRIPT_DRAWMARKERS,
            self::RESOURCE_MAP_SCRIPT_MARKERS => POP_TEMPLATE_MAP_SCRIPT_MARKERS,
            self::RESOURCE_MAP_SCRIPT_RESETMARKERS => POP_TEMPLATE_MAP_SCRIPT_RESETMARKERS,
            self::RESOURCE_MAP_SCRIPTCUSTOMIZATION_POST => POP_TEMPLATE_MAP_SCRIPTCUSTOMIZATION_POST,
            self::RESOURCE_MAP_SCRIPTCUSTOMIZATION_USER => POP_TEMPLATE_MAP_SCRIPTCUSTOMIZATION_USER,
            self::RESOURCE_MAP_STATICIMAGE => POP_TEMPLATE_MAP_STATICIMAGE,
            self::RESOURCE_MAP_STATICIMAGE_URLPARAM => POP_TEMPLATE_MAP_STATICIMAGE_URLPARAM,
            self::RESOURCE_MAP_STATICIMAGE_LOCATIONS => POP_TEMPLATE_MAP_STATICIMAGE_LOCATIONS,
            self::RESOURCE_SCRIPT_TRIGGERTYPEAHEADSELECT_LOCATION => POP_TEMPLATE_SCRIPT_TRIGGERTYPEAHEADSELECT_LOCATION,
            self::RESOURCE_VIEWCOMPONENT_LOCATIONLINK => POP_TEMPLATE_VIEWCOMPONENT_LOCATIONLINK,
            self::RESOURCE_VIEWCOMPONENT_LOCATIONBUTTON => POP_TEMPLATE_VIEWCOMPONENT_LOCATIONBUTTON,
            self::RESOURCE_VIEWCOMPONENT_LOCATIONBUTTONINNER => POP_TEMPLATE_VIEWCOMPONENT_LOCATIONBUTTONINNER,
        );
        return $templates[$resource[1]];
    }
    
    public function getVersion(array $resource)
    {
        return POP_LOCATIONSWEBPLATFORM_VERSION;
    }
    
    public function getPath(array $resource)
    {
        return POP_LOCATIONSWEBPLATFORM_URL.'/js/dist/templates';
    }
    
    public function getDir(array $resource)
    {
        return POP_LOCATIONSWEBPLATFORM_DIR.'/js/dist/templates';
    }
    
    public function getGlobalscopeMethodCalls(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_MAP_SCRIPTCUSTOMIZATION_POST:
            case self::RESOURCE_MAP_SCRIPTCUSTOMIZATION_USER:
                return array(
                    'MapRuntime' => array(
                        'setMarkerData',
                    ),
                );

            case self::RESOURCE_MAP_SCRIPT_DRAWMARKERS:
                return array(
                    'MapRuntime' => array(
                        'drawMarkers',
                    ),
                    'Manager' => array(
                        'getBlock',
                        'getPageSection',
                    ),
                );

            case self::RESOURCE_MAP_SCRIPT_MARKERS:
                return array(
                    'MapRuntime' => array(
                        'initMarker',
                    ),
                );

            case self::RESOURCE_MAP_SCRIPT_RESETMARKERS:
                return array(
                    'MapRuntime' => array(
                        'resetMarkerIds',
                    ),
                );

            case self::RESOURCE_SCRIPT_TRIGGERTYPEAHEADSELECT_LOCATION:
                return array(
                    'TypeaheadSelectable' => array(
                        'getTypeaheadTrigger',
                    ),
                    'DynamicRender' => array(
                        'renderDBObjectLayoutFromDatum',
                    ),
                    'Manager' => array(
                        'getBlock',
                        'getPageSection',
                        'getDBObject',
                    ),
                );
        }

        return parent::getGlobalscopeMethodCalls($resource);
    }

    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_VIEWCOMPONENT_LOCATIONBUTTON:
                $dependencies[] = [PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_COMPARE];
                break;
        }

        return $dependencies;
    }
}


