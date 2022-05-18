<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;

abstract class PoP_Module_Processor_PostMapScriptCustomizationsBase extends PoP_Module_Processor_MapScriptCustomizationsBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_SCRIPTCUSTOMIZATION_POST];
    }

    public function getAuthorsModule(array $component)
    {
        return [PoP_Module_Processor_PostAuthorNameLayouts::class, PoP_Module_Processor_PostAuthorNameLayouts::COMPONENT_LAYOUTPOST_AUTHORNAME];
    }
    public function getAuthorsSeparator(array $component, array &$props)
    {
        return GD_CONSTANT_AUTHORS_SEPARATOR;
    }

    public function getLayoutExtraSubmodule(array $component)
    {
        return null;
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        if ($layout_extra = $this->getLayoutExtraSubmodule($component)) {
            $ret[] = $layout_extra;
        }

        return $ret;
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getRelationalSubmodules(array $component): array
    {
        if ($authors_component = $this->getAuthorsModule($component)) {
            return [
                new RelationalModuleField(
                    'author',
                    [
                        $authors_component,
                    ]
                ),
            ];
        }

        return parent::getRelationalSubmodules($component);
    }

    public function getThumbField(array $component, array &$props)
    {
        return FieldQueryInterpreterFacade::getInstance()->getField(
            $this->getThumbFieldName($component, $props),
            $this->getThumbFieldArgs($component, $props),
            $this->getThumbFieldAlias($component, $props)
        );
    }

    protected function getThumbFieldName(array $component, array &$props)
    {
        return 'thumb';
    }

    protected function getThumbFieldArgs(array $component, array &$props)
    {
        return ['size' => 'thumb-sm'];
    }

    protected function getThumbFieldAlias(array $component, array &$props)
    {
        return 'thumb';
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        $thumb = $this->getThumbField($component, $props);
        return array('id', 'title', $thumb, 'url');
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret['thumb'] = array(
            'name' => FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                $this->getProp($component, $props, 'succeeding-typeResolver'),
                $this->getThumbField($component, $props)),
        );

        if ($authors_component = $this->getAuthorsModule($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['authors'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($authors_component);
            $ret['authors-sep'] = $this->getAuthorsSeparator($component, $props);
        }
        if ($layout_extra = $this->getLayoutExtraSubmodule($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layout-extra'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($layout_extra);
        }

        return $ret;
    }
}
