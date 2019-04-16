<?php
namespace PoP\Engine;

class ModelInstanceProcessor_Utils
{
    public static function getModelInstanceId()
    {

        // The string is too long. Use a hashing function to shorten it
        return md5(implode('-', self::getModelInstanceComponents()));
    }

    public static function getModelInstanceComponents()
    {
        $components = array();

        // Comment Leo 05/04/2017: do also add the version, because otherwise there are PHP errors
        // happening from stale configuration that is not deleted, and still served, after a new version is deployed
        // By adding the version, that will not happen anymore
        $components[] = __('version:', 'pop-engine').popVersion();

        // Mix the information specific to the module, with that present in $vars
        return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
            'ModelInstanceProcessor:model_instance_components',
            array_merge(
                $components,
                self::getModelInstanceComponentsFromVars()
            )
        );
    }

    protected static function getModelInstanceComponentsFromVars()
    {
        $components = array();
        
        $vars = Engine_Vars::getVars();
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();

        // There will always be a nature. Add it.
        $nature = $vars['nature'];
        $route = $vars['route'];
        $components[] = __('nature:', 'pop-engine').$nature;
        $components[] = __('route:', 'pop-engine').$route;

        // Properties specific to each nature
        switch ($nature) {
            case POP_NATURE_PAGE:
                $page_id = $vars['routing-state']['queried-object-id'];
                $components[] = __('page id:', 'pop-engine').$page_id;
                break;
        }

        // Other properties
        if ($format = $vars['format']) {
            $components[] = __('format:', 'pop-engine').$format;
        }
        if ($target = $vars['target']) {
            $components[] = __('target:', 'pop-engine').$target;
        }
        if ($action = $vars['action']) {
            $components[] = __('action:', 'pop-engine').$action;
        }
        if ($config = $vars['config']) {
            $components[] = __('config:', 'pop-engine').$config;
        }
        if ($modulefilter = $vars['modulefilter']) {
            $components[] = __('module filter:', 'pop-engine').$modulefilter;

            if ($modulefilter == POP_MODULEFILTER_MODULEPATHS && ($modulepaths = $vars['modulepaths'])) {
                $paths = array();
                foreach ($modulepaths as $modulepath) {
                    $paths[] = ModulePathManager_Utils::stringifyModulePath($modulepath);
                }
                
                $components[] = __('module paths:', 'pop-engine').implode(',', $paths);
            } elseif ($modulefilter == POP_MODULEFILTER_HEADMODULE && ($headmodule = $vars['headmodule'])) {
                $components[] = __('head module:', 'pop-engine').$headmodule;
            }
        }

        // Can the configuration change when doing a POST or GET?
        if (\PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('\PoP\Engine\ModelInstanceProcessor_Utils:components_from_vars:post-or-get-change', false)) {
            $components[] = __('operation:', 'pop-engine').(doingPost() ? 'post' : 'get');
        }
        if ($mangled = $vars['mangled']) {
            // By default it is mangled. To make it non-mangled, url must have param "mangled=none",
            // so only in these exceptional cases the identifier will add this parameter
            $components[] = __('mangled:', 'pop-engine').$mangled;
        }

        // Allow for plug-ins to add their own vars. Eg: URE source parameter
        $components = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('ModelInstanceProcessor:model_instance_components_from_vars', $components);

        return $components;
    }
}
