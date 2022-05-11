<?php

define('GD_CONSTANT_FULLUSER_TITLEPOSITION_TOP', 'top');
define('GD_CONSTANT_FULLUSER_TITLEPOSITION_BODY', 'body');

abstract class PoP_Module_Processor_FullUserLayoutsBase extends PoP_Module_Processor_FullObjectLayoutsBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_FULLUSER];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        return array_merge(
            parent::getDataFields($module, $props),
            array('shortDescriptionFormatted', 'descriptionFormatted')
        );
    }

    public function titlePosition(array $module, array &$props)
    {
        return GD_CONSTANT_FULLUSER_TITLEPOSITION_TOP;
    }

    public function showDescription(array $module, array &$props)
    {
        return true;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($this->getTitleSubmodule($module, $props)) {
            $ret['title-position'] = $this->titlePosition($module, $props);
        }

        if ($this->showDescription($module, $props)) {
            $ret['show-description'] = true;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        if ($this->showDescription($module, $props)) {
            $this->appendProp($module, $props, 'class', 'showdescription');
        } else {
            $this->appendProp($module, $props, 'class', 'nodescription');
        }

        parent::initModelProps($module, $props);
    }
}
