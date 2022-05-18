<?php

define('GD_CONSTANT_FULLUSER_TITLEPOSITION_TOP', 'top');
define('GD_CONSTANT_FULLUSER_TITLEPOSITION_BODY', 'body');

abstract class PoP_Module_Processor_FullUserLayoutsBase extends PoP_Module_Processor_FullObjectLayoutsBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_FULLUSER];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        return array_merge(
            parent::getDataFields($component, $props),
            array('shortDescriptionFormatted', 'descriptionFormatted')
        );
    }

    public function titlePosition(array $component, array &$props)
    {
        return GD_CONSTANT_FULLUSER_TITLEPOSITION_TOP;
    }

    public function showDescription(array $component, array &$props)
    {
        return true;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($this->getTitleSubmodule($component, $props)) {
            $ret['title-position'] = $this->titlePosition($component, $props);
        }

        if ($this->showDescription($component, $props)) {
            $ret['show-description'] = true;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        if ($this->showDescription($component, $props)) {
            $this->appendProp($component, $props, 'class', 'showdescription');
        } else {
            $this->appendProp($component, $props, 'class', 'nodescription');
        }

        parent::initModelProps($component, $props);
    }
}
