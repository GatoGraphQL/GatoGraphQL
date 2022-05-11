<?php

abstract class GD_URE_Module_Processor_MemberPrivilegesLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [URE_PoPProcessors_TemplateResourceLoaderProcessor::class, URE_PoPProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUTUSER_MEMBERPRIVILEGES];
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        return array('memberPrivilegesByName');
    }

    public function getDescription(array $module, array &$props)
    {
        return '';
    }

    public function getLabelClass(array $module, array &$props)
    {
        return 'label-warning';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        if ($description = $this->getDescription($module, $props)) {
            $ret[GD_JS_TITLES]['description'] = $description;
        }

        if ($label_class = $this->getLabelClass($module, $props)) {
            $ret[GD_JS_CLASSES]['label'] = $label_class;
        }
    
        return $ret;
    }
}
