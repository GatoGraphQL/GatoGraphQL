<?php
namespace PoP\ExampleModules;

define('POP_MODULE_EXAMPLE_HOME', \PoP\Engine\DefinitionUtils::getDefinition('example-home'));
define('POP_MODULE_EXAMPLE_AUTHOR', \PoP\Engine\DefinitionUtils::getDefinition('example-author'));
define('POP_MODULE_EXAMPLE_TAG', \PoP\Engine\DefinitionUtils::getDefinition('example-tag'));

class ModuleProcessor_Groups extends \PoP\Engine\ModuleProcessorBase
{
    public function getModulesToProcess()
    {
        return array(
            POP_MODULE_EXAMPLE_HOME,
            POP_MODULE_EXAMPLE_AUTHOR,
            POP_MODULE_EXAMPLE_TAG,
        );
    }

    public function getModules($module)
    {
        $ret = parent::getModules($module);

        switch ($module) {
            case POP_MODULE_EXAMPLE_HOME:
                $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
                if ($cmsapi->getHomeStaticPage()) {
                    $ret[] = POP_MODULE_EXAMPLE_HOMESTATICPAGE;
                } else {
                    $ret[] = POP_MODULE_EXAMPLE_HOMEWELCOME;
                    $ret[] = POP_MODULE_EXAMPLE_LATESTPOSTS;
                }
                break;

            case POP_MODULE_EXAMPLE_AUTHOR:
                $ret[] = POP_MODULE_EXAMPLE_AUTHORDESCRIPTION;
                $ret[] = POP_MODULE_EXAMPLE_AUTHORLATESTPOSTS;
                break;

            case POP_MODULE_EXAMPLE_TAG:
                $ret[] = POP_MODULE_EXAMPLE_TAGDESCRIPTION;
                $ret[] = POP_MODULE_EXAMPLE_TAGLATESTPOSTS;
                break;
        }

        return $ret;
    }
}

/**
 * Initialization
 */
new ModuleProcessor_Groups();
