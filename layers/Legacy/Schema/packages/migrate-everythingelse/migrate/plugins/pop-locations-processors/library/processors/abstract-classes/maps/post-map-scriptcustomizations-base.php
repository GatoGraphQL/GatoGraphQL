<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;

abstract class PoP_Module_Processor_PostMapScriptCustomizationsBase extends PoP_Module_Processor_MapScriptCustomizationsBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_SCRIPTCUSTOMIZATION_POST];
    }

    public function getAuthorsModule(array $module)
    {
        return [PoP_Module_Processor_PostAuthorNameLayouts::class, PoP_Module_Processor_PostAuthorNameLayouts::MODULE_LAYOUTPOST_AUTHORNAME];
    }
    public function getAuthorsSeparator(array $module, array &$props)
    {
        return GD_CONSTANT_AUTHORS_SEPARATOR;
    }

    public function getLayoutExtraSubmodule(array $module)
    {
        return null;
    }

    public function getSubComponentVariations(array $module): array
    {
        $ret = parent::getSubComponentVariations($module);

        if ($layout_extra = $this->getLayoutExtraSubmodule($module)) {
            $ret[] = $layout_extra;
        }

        return $ret;
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getRelationalSubmodules(array $module): array
    {
        if ($authors_module = $this->getAuthorsModule($module)) {
            return [
                new RelationalModuleField(
                    'author',
                    [
                        $authors_module,
                    ]
                ),
            ];
        }

        return parent::getRelationalSubmodules($module);
    }

    public function getThumbField(array $module, array &$props)
    {
        return FieldQueryInterpreterFacade::getInstance()->getField(
            $this->getThumbFieldName($module, $props),
            $this->getThumbFieldArgs($module, $props),
            $this->getThumbFieldAlias($module, $props)
        );
    }

    protected function getThumbFieldName(array $module, array &$props)
    {
        return 'thumb';
    }

    protected function getThumbFieldArgs(array $module, array &$props)
    {
        return ['size' => 'thumb-sm'];
    }

    protected function getThumbFieldAlias(array $module, array &$props)
    {
        return 'thumb';
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        $thumb = $this->getThumbField($module, $props);
        return array('id', 'title', $thumb, 'url');
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret['thumb'] = array(
            'name' => FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                $this->getProp($module, $props, 'succeeding-typeResolver'),
                $this->getThumbField($module, $props)),
        );

        if ($authors_module = $this->getAuthorsModule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['authors'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($authors_module);
            $ret['authors-sep'] = $this->getAuthorsSeparator($module, $props);
        }
        if ($layout_extra = $this->getLayoutExtraSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layout-extra'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($layout_extra);
        }

        return $ret;
    }
}
