<?php

define('GD_CONSTANT_FULLUSER_TITLEPOSITION_TOP', 'top');
define('GD_CONSTANT_FULLUSER_TITLEPOSITION_BODY', 'body');

abstract class PoP_Module_Processor_FullUserLayoutsBase extends PoP_Module_Processor_FullObjectLayoutsBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_FULLUSER];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        return array_merge(
            parent::getDataFields($componentVariation, $props),
            array('shortDescriptionFormatted', 'descriptionFormatted')
        );
    }

    public function titlePosition(array $componentVariation, array &$props)
    {
        return GD_CONSTANT_FULLUSER_TITLEPOSITION_TOP;
    }

    public function showDescription(array $componentVariation, array &$props)
    {
        return true;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        if ($this->getTitleSubmodule($componentVariation, $props)) {
            $ret['title-position'] = $this->titlePosition($componentVariation, $props);
        }

        if ($this->showDescription($componentVariation, $props)) {
            $ret['show-description'] = true;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        if ($this->showDescription($componentVariation, $props)) {
            $this->appendProp($componentVariation, $props, 'class', 'showdescription');
        } else {
            $this->appendProp($componentVariation, $props, 'class', 'nodescription');
        }

        parent::initModelProps($componentVariation, $props);
    }
}
