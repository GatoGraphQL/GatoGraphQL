<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

abstract class PoP_Module_Processor_PreviewUserLayoutsBase extends PoP_Module_Processor_PreviewObjectLayoutsBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_PREVIEWUSER];
    }

    /**
     * @todo Migrate from string to LeafComponentField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField[]
     */
    public function getLeafComponentFields(array $component, array &$props): array
    {
        $ret = array_merge(
            parent::getLeafComponentFields($component, $props),
            array('displayName', 'isProfile')
        );

        if ($this->showTitle($component)) {
            $ret[] = 'title';
        }
        if ($this->showShortDescription($component)) {
            $ret[] = 'shortDescriptionFormatted';
        }

        return $ret;
    }

    public function showShortDescription(array $component)
    {
        return true;
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($belowavatar_components = $this->getBelowavatarLayoutSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $belowavatar_components
            );
        }
        if ($belowexcerpt_templates = $this->getBelowexcerptLayoutSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $belowexcerpt_templates
            );
        }
        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            if ($useravatar = $this->getUseravatarSubcomponent($component)) {
                $ret[] = $useravatar;
            }
        }

        return $ret;
    }

    protected function showTitle(array $component)
    {
        return false;
    }

    public function getUseravatarSubcomponent(array $component)
    {
        return null;
    }

    public function getBelowavatarLayoutSubcomponents(array $component)
    {
        return array();
    }
    public function getBelowexcerptLayoutSubcomponents(array $component)
    {
        return array();
    }
    // function getExtraClass(array $component) {

    //     return '';
    // }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($belowavatar_components = $this->getBelowavatarLayoutSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['belowavatar'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance(), 'getComponentOutputName'], 
                $belowavatar_components
            );
        }
        if ($belowexcerpt_components = $this->getBelowexcerptLayoutSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['belowexcerpt'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance(), 'getComponentOutputName'], 
                $belowexcerpt_components
            );
        }
        if ($this->showShortDescription($component)) {
            $ret['show-short-description'] = true;
        }

        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            if ($useravatar = $this->getUseravatarSubcomponent($component)) {
                $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['useravatar'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName($useravatar);
            }
        }

        return $ret;
    }
}
