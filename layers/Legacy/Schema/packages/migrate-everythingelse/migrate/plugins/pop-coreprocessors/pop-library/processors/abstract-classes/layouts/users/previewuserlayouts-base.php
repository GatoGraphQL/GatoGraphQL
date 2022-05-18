<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_PreviewUserLayoutsBase extends PoP_Module_Processor_PreviewObjectLayoutsBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_PREVIEWUSER];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        $ret = array_merge(
            parent::getDataFields($componentVariation, $props),
            array('displayName', 'isProfile')
        );

        if ($this->showTitle($componentVariation)) {
            $ret[] = 'title';
        }
        if ($this->showShortDescription($componentVariation)) {
            $ret[] = 'shortDescriptionFormatted';
        }

        return $ret;
    }

    public function showShortDescription(array $componentVariation)
    {
        return true;
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($belowavatar_componentVariations = $this->getBelowavatarLayoutSubmodules($componentVariation)) {
            $ret = array_merge(
                $ret,
                $belowavatar_componentVariations
            );
        }
        if ($belowexcerpt_templates = $this->getBelowexcerptLayoutSubmodules($componentVariation)) {
            $ret = array_merge(
                $ret,
                $belowexcerpt_templates
            );
        }
        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            if ($useravatar = $this->getUseravatarSubmodule($componentVariation)) {
                $ret[] = $useravatar;
            }
        }

        return $ret;
    }

    protected function showTitle(array $componentVariation)
    {
        return false;
    }

    public function getUseravatarSubmodule(array $componentVariation)
    {
        return null;
    }

    public function getBelowavatarLayoutSubmodules(array $componentVariation)
    {
        return array();
    }
    public function getBelowexcerptLayoutSubmodules(array $componentVariation)
    {
        return array();
    }
    // function getExtraClass(array $componentVariation) {

    //     return '';
    // }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($belowavatar_componentVariations = $this->getBelowavatarLayoutSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['belowavatar'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
                $belowavatar_componentVariations
            );
        }
        if ($belowexcerpt_componentVariations = $this->getBelowexcerptLayoutSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['belowexcerpt'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'], 
                $belowexcerpt_componentVariations
            );
        }
        if ($this->showShortDescription($componentVariation)) {
            $ret['show-short-description'] = true;
        }

        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            if ($useravatar = $this->getUseravatarSubmodule($componentVariation)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['useravatar'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($useravatar);
            }
        }

        return $ret;
    }
}
