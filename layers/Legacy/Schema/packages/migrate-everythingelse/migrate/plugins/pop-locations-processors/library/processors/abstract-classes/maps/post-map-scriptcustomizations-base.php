<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalComponentFieldNode;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;

abstract class PoP_Module_Processor_PostMapScriptCustomizationsBase extends PoP_Module_Processor_MapScriptCustomizationsBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_MAP_SCRIPTCUSTOMIZATION_POST];
    }

    public function getAuthorsComponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_PostAuthorNameLayouts::class, PoP_Module_Processor_PostAuthorNameLayouts::COMPONENT_LAYOUTPOST_AUTHORNAME];
    }
    public function getAuthorsSeparator(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return GD_CONSTANT_AUTHORS_SEPARATOR;
    }

    public function getLayoutExtraSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($layout_extra = $this->getLayoutExtraSubcomponent($component)) {
            $ret[] = $layout_extra;
        }

        return $ret;
    }

    /**
     * @return RelationalComponentFieldNode[]
     */
    public function getRelationalComponentFieldNodes(\PoP\ComponentModel\Component\Component $component): array
    {
        if ($authors_component = $this->getAuthorsComponent($component)) {
            return [
                new RelationalComponentFieldNode(
                    new LeafField(
                        'author',
                        null,
                        [],
                        [],
                        LocationHelper::getNonSpecificLocation()
                    ),
                    [
                        $authors_component,
                    ]
                ),
            ];
        }

        return parent::getRelationalComponentFieldNodes($component);
    }

    public function getThumbField(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return FieldQueryInterpreterFacade::getInstance()->getField(
            $this->getThumbFieldName($component, $props),
            $this->getThumbFieldArgs($component, $props),
            $this->getThumbFieldAlias($component, $props)
        );
    }

    protected function getThumbFieldName(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'thumb';
    }

    protected function getThumbFieldArgs(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return ['size' => 'thumb-sm'];
    }

    protected function getThumbFieldAlias(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'thumb';
    }

    /**
     * @todo Migrate from string to LeafComponentFieldNode
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentFieldNode[]
     */
    public function getLeafComponentFieldNodes(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $thumb = $this->getThumbField($component, $props);
        return array('id', 'title', $thumb, 'url');
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret['thumb'] = array(
            'name' => FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                $this->getProp($component, $props, 'succeeding-typeResolver'),
                $this->getThumbField($component, $props) // @todo Fix: pass LeafField
            ),
        );

        if ($authors_component = $this->getAuthorsComponent($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['authors'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($authors_component);
            $ret['authors-sep'] = $this->getAuthorsSeparator($component, $props);
        }
        if ($layout_extra = $this->getLayoutExtraSubcomponent($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['layout-extra'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($layout_extra);
        }

        return $ret;
    }
}
