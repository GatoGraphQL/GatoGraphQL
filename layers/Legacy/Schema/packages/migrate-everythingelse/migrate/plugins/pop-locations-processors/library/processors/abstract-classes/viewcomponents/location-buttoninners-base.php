<?php
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalComponentFieldNode;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_LocationViewComponentButtonInnersBase extends PoP_Module_Processor_ButtonInnersBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_Locations_TemplateResourceLoaderProcessor::class, PoP_Locations_TemplateResourceLoaderProcessor::RESOURCE_VIEWCOMPONENT_LOCATIONBUTTONINNER];
    }

    public function getLocationComponent(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }
    public function separator(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return ' | ';
    }

    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'fa-map-marker';
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($location_component = $this->getLocationComponent($component)) {
            $ret['separator'] = $this->separator($component, $props);
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['location-layout'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($location_component);
        } else {
            $ret[GD_JS_TITLES] = array(
                'locations' => TranslationAPIFacade::getInstance()->__('Locations', 'em-popprocessors')
            );
        }

        return $ret;
    }

    /**
     * @return RelationalComponentFieldNode[]
     */
    public function getRelationalComponentFieldNodes(\PoP\ComponentModel\Component\Component $component): array
    {
        if ($location_component = $this->getLocationComponent($component)) {
            return [
                new RelationalComponentFieldNode(
                    new LeafField(
                        'locations',
                        null,
                        [],
                        [],
                        ASTNodesFactory::getNonSpecificLocation()
                    ),
                    [
                        $location_component,
                    ]
                ),
            ];
        }
        return parent::getRelationalComponentFieldNodes($component);
    }
}
