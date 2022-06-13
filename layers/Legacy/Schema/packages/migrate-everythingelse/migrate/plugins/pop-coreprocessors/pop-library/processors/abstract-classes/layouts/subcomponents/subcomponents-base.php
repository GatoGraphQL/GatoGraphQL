<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalComponentFieldNode;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;

abstract class PoP_Module_Processor_SubcomponentLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_SUBCOMPONENT];
    }

    abstract public function getSubcomponentFieldNode(\PoP\ComponentModel\Component\Component $component): LeafField;

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        return array();
    }

    /**
     * @return RelationalComponentFieldNode[]
     */
    public function getRelationalComponentFieldNodes(\PoP\ComponentModel\Component\Component $component): array
    {
        return [
            new RelationalComponentFieldNode(
                $this->getSubcomponentFieldNode($component),
                $this->getLayoutSubcomponents($component)
            ),
        ];
    }

    public function isIndividual(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return true;
    }

    public function getHtmlTag(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'div';
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret['subcomponent-field'] = $this->getSubcomponentFieldNode($component);
        if ($layouts = $this->getLayoutSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['layouts'] = array_map(
                \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName(...),
                $layouts
            );
        }
        $ret['individual'] = $this->isIndividual($component, $props);
        $ret['html-tag'] = $this->getHtmlTag($component, $props);

        return $ret;
    }
}
