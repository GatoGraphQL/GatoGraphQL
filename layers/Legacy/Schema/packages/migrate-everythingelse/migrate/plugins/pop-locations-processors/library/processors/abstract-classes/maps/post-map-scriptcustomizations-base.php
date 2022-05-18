<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;

abstract class PoP_Module_Processor_PostMapScriptCustomizationsBase extends PoP_Module_Processor_MapScriptCustomizationsBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_SCRIPTCUSTOMIZATION_POST];
    }

    public function getAuthorsModule(array $componentVariation)
    {
        return [PoP_Module_Processor_PostAuthorNameLayouts::class, PoP_Module_Processor_PostAuthorNameLayouts::MODULE_LAYOUTPOST_AUTHORNAME];
    }
    public function getAuthorsSeparator(array $componentVariation, array &$props)
    {
        return GD_CONSTANT_AUTHORS_SEPARATOR;
    }

    public function getLayoutExtraSubmodule(array $componentVariation)
    {
        return null;
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($layout_extra = $this->getLayoutExtraSubmodule($componentVariation)) {
            $ret[] = $layout_extra;
        }

        return $ret;
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getRelationalSubmodules(array $componentVariation): array
    {
        if ($authors_module = $this->getAuthorsModule($componentVariation)) {
            return [
                new RelationalModuleField(
                    'author',
                    [
                        $authors_module,
                    ]
                ),
            ];
        }

        return parent::getRelationalSubmodules($componentVariation);
    }

    public function getThumbField(array $componentVariation, array &$props)
    {
        return FieldQueryInterpreterFacade::getInstance()->getField(
            $this->getThumbFieldName($componentVariation, $props),
            $this->getThumbFieldArgs($componentVariation, $props),
            $this->getThumbFieldAlias($componentVariation, $props)
        );
    }

    protected function getThumbFieldName(array $componentVariation, array &$props)
    {
        return 'thumb';
    }

    protected function getThumbFieldArgs(array $componentVariation, array &$props)
    {
        return ['size' => 'thumb-sm'];
    }

    protected function getThumbFieldAlias(array $componentVariation, array &$props)
    {
        return 'thumb';
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        $thumb = $this->getThumbField($componentVariation, $props);
        return array('id', 'title', $thumb, 'url');
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret['thumb'] = array(
            'name' => FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                $this->getProp($componentVariation, $props, 'succeeding-typeResolver'),
                $this->getThumbField($componentVariation, $props)),
        );

        if ($authors_module = $this->getAuthorsModule($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['authors'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($authors_module);
            $ret['authors-sep'] = $this->getAuthorsSeparator($componentVariation, $props);
        }
        if ($layout_extra = $this->getLayoutExtraSubmodule($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['layout-extra'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($layout_extra);
        }

        return $ret;
    }
}
