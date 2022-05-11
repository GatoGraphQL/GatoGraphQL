<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

abstract class PoP_Module_Processor_PreviewUserLayoutsBase extends PoP_Module_Processor_PreviewObjectLayoutsBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_PREVIEWUSER];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        $ret = array_merge(
            parent::getDataFields($module, $props),
            array('displayName', 'isProfile')
        );

        if ($this->showTitle($module)) {
            $ret[] = 'title';
        }
        if ($this->showShortDescription($module)) {
            $ret[] = 'shortDescriptionFormatted';
        }

        return $ret;
    }

    public function showShortDescription(array $module)
    {
        return true;
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($belowavatar_modules = $this->getBelowavatarLayoutSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $belowavatar_modules
            );
        }
        if ($belowexcerpt_templates = $this->getBelowexcerptLayoutSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $belowexcerpt_templates
            );
        }
        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            if ($useravatar = $this->getUseravatarSubmodule($module)) {
                $ret[] = $useravatar;
            }
        }

        return $ret;
    }

    protected function showTitle(array $module)
    {
        return false;
    }

    public function getUseravatarSubmodule(array $module)
    {
        return null;
    }

    public function getBelowavatarLayoutSubmodules(array $module)
    {
        return array();
    }
    public function getBelowexcerptLayoutSubmodules(array $module)
    {
        return array();
    }
    // function getExtraClass(array $module) {

    //     return '';
    // }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        if ($belowavatar_modules = $this->getBelowavatarLayoutSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['belowavatar'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
                $belowavatar_modules
            );
        }
        if ($belowexcerpt_modules = $this->getBelowexcerptLayoutSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['belowexcerpt'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
                $belowexcerpt_modules
            );
        }
        if ($this->showShortDescription($module)) {
            $ret['show-short-description'] = true;
        }

        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            if ($useravatar = $this->getUseravatarSubmodule($module)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['useravatar'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($useravatar);
            }
        }

        return $ret;
    }
}
