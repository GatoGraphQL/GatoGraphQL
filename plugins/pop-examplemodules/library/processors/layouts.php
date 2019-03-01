<?php
namespace PoP\ExampleModules;

define('POP_MODULE_EXAMPLE_404', \PoP\Engine\DefinitionUtils::getModuleDefinition('example-404'));
define('POP_MODULE_EXAMPLE_HOMEWELCOME', \PoP\Engine\DefinitionUtils::getModuleDefinition('example-homewelcome'));
define('POP_MODULE_EXAMPLE_COMMENT', \PoP\Engine\DefinitionUtils::getModuleDefinition('example-comment'));
define('POP_MODULE_EXAMPLE_AUTHORPROPERTIES', \PoP\Engine\DefinitionUtils::getModuleDefinition('example-authorproperties'));
define('POP_MODULE_EXAMPLE_TAGPROPERTIES', \PoP\Engine\DefinitionUtils::getModuleDefinition('example-tagproperties'));

class ModuleProcessor_Layouts extends \PoP\Engine\ModuleProcessorBase
{
    public function getModulesToProcess()
    {
        return array(
            POP_MODULE_EXAMPLE_404,
            POP_MODULE_EXAMPLE_HOMEWELCOME,
            POP_MODULE_EXAMPLE_COMMENT,
            POP_MODULE_EXAMPLE_AUTHORPROPERTIES,
            POP_MODULE_EXAMPLE_TAGPROPERTIES,
        );
    }

    public function getImmutableConfiguration($module, &$props)
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        switch ($module) {
            case POP_MODULE_EXAMPLE_404:
                $ret['msg'] = __('Ops, this is a broken link...', 'pop-examplemodules');
                break;

            case POP_MODULE_EXAMPLE_HOMEWELCOME:
                $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
                $ret['msg'] = sprintf(
                    __('Welcome to %s!', 'pop-examplemodules'),
                    $cmsapi->getSiteName()
                );
                break;
        }
    
        return $ret;
    }

    public function getDataFields($module, $props)
    {
        $ret = parent::getDataFields($module, $props);

        switch ($module) {
            case POP_MODULE_EXAMPLE_COMMENT:
                $ret[] = 'content';
                break;
            
            case POP_MODULE_EXAMPLE_AUTHORPROPERTIES:
                $ret = array_merge(
                    $ret,
                    array('display-name', 'description', 'url')
                );
                break;
            
            case POP_MODULE_EXAMPLE_TAGPROPERTIES:
                $ret = array_merge(
                    $ret,
                    array('name', 'slug', 'description', 'count')
                );
                break;
        }
    
        return $ret;
    }

    public function getDbobjectRelationalSuccessors($module)
    {
        $ret = parent::getDbobjectRelationalSuccessors($module);

        switch ($module) {
            case POP_MODULE_EXAMPLE_COMMENT:
                $ret['author'] = array(
                    GD_DATALOADER_CONVERTIBLEUSERLIST => array(
                        POP_MODULE_EXAMPLE_AUTHORPROPERTIES,
                    ),
                );
                break;
        }

        return $ret;
    }
}

/**
 * Initialization
 */
new ModuleProcessor_Layouts();
