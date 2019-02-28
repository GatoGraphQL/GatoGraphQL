<?php
namespace PoP\Engine;

class Engine_Hooks
{
    public function __construct()
    {

        // Add functions as hooks, so we allow PoP_Application to set the 'global-state' first
        add_action(
            '\PoP\Engine\Engine_Vars:addVars',
            array($this, 'addVars'),
            10,
            1
        );
        addFilter(
            'ModelInstanceProcessor:model_instance_components_from_vars',
            array($this, 'getModelInstanceComponentsFromVars')
        );
    }

    public function addVars($vars_in_array)
    {

        // Allow WP API to set the "global-state" first
        // Each page is an independent configuration
        $vars = &$vars_in_array[0];
        if (Server\Utils::enableApi() && $vars['action'] == POP_ACTION_API) {
            $this->addFieldsToVars($vars);
        } elseif ($vars['global-state']['is-page']) {
            $dataquery_manager = DataQuery_Manager_Factory::getInstance();
            $post = $vars['global-state']['queried-object'];

            // Special pages: dataqueries' cacheablepages serve layouts, noncacheable pages serve fields.
            // So the settings for these pages depend on the URL params
            if (in_array($post->ID, $dataquery_manager->getNoncacheablepages())) {
                $this->addFieldsToVars($vars);
            } elseif (in_array($post->ID, $dataquery_manager->getCacheablepages())) {
                if ($layouts = $_REQUEST[GD_URLPARAM_LAYOUTS]) {
                    $layouts = is_array($layouts) ? $layouts : array($layouts);
                    $vars['layouts'] = $layouts;
                }
            }
        }
    }

    private function addFieldsToVars(&$vars)
    {
        if (isset($_REQUEST[GD_URLPARAM_FIELDS])) {

            // The fields param can either be an array or a string
            if (is_array($_REQUEST[GD_URLPARAM_FIELDS])) {
                $fields = $_REQUEST[GD_URLPARAM_FIELDS];
            } else {

                // If it is a string, split the items with ',', the inner items with '.', and the inner fields with '|'
                $fields = array();
                $pointer = &$fields;

                // Split the items by ","
                foreach (explode(POP_CONSTANT_PARAMVALUE_SEPARATOR, $_REQUEST[GD_URLPARAM_FIELDS]) as $commafields) {

                    // For each item, advance to the last level by following the "."
                    $dotfields = explode(POP_CONSTANT_DOTSYNTAX_DOT, $commafields);
                    for ($i = 0; $i < count($dotfields)-1; $i++) {
                        $pointer[$dotfields[$i]] = $pointer[$dotfields[$i]] ?? array();
                        $pointer = &$pointer[$dotfields[$i]];
                    }

                    // The last level can contain several fields, separated by "|"
                    $pipefields = $dotfields[count($dotfields)-1];
                    foreach (explode(POP_CONSTANT_PARAMFIELD_SEPARATOR, $pipefields) as $pipefield) {
                        $pointer[] = $pipefield;
                    }
                    $pointer = &$fields;
                }
            }

            $vars['fields'] = $fields;
        }
    }

    private function addFieldsToComponents(&$components)
    {
        $vars = Engine_Vars::getVars();
        if ($fields = $vars['fields']) {

            // Serialize instead of implode, because $fields can contain $key => $value
            $components[] = __('fields:', 'pop-engine').serialize($fields);
        }
    }

    public function getModelInstanceComponentsFromVars($components)
    {

        // Allow WP API to set the "global-state" first
        // Each page is an independent configuration
        $vars = Engine_Vars::getVars();
        if (Server\Utils::enableApi() && $vars['action'] == POP_ACTION_API) {
            $this->addFieldsToComponents($components);
        } elseif ($vars['global-state']['is-page']) {
            $dataquery_manager = DataQuery_Manager_Factory::getInstance();
            $post = $vars['global-state']['queried-object'];

            // Special pages: dataqueries' cacheablepages serve layouts, noncacheable pages serve fields.
            // So the settings for these pages depend on the URL params
            if (in_array($post->ID, $dataquery_manager->getNoncacheablepages())) {
                $this->addFieldsToComponents($components);
            } elseif (in_array($post->ID, $dataquery_manager->getCacheablepages())) {
                if ($layouts = $vars['layouts']) {
                    $components[] = __('layouts:', 'pop-engine').implode('.', $layouts);
                }
            }
        }

        return $components;
    }
}

/**
 * Initialization
 */
new Engine_Hooks();
