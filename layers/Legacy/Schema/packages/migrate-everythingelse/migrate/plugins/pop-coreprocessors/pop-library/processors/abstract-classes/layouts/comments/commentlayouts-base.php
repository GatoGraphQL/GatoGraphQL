<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalComponentFieldNode;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;

abstract class PoP_Module_Processor_CommentLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);
        $ret[] = $this->getBtnreplyComponent($component);

        if ($abovelayout_components = $this->getAbovelayoutLayoutSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $abovelayout_components
            );
        }
        if ($content_component = $this->getContentSubcomponent($component)) {
            $ret[] = $content_component;
        }
        return $ret;
    }

    public function getContentSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::COMPONENT_LAYOUT_CONTENT_COMMENT];
    }

    public function getAbovelayoutLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        return array();
    }

    /**
     * @return mixed[]|null
     */
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_COMMENT];
    }

    public function getBtnreplyComponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_CommentViewComponentButtons::class, PoP_Module_Processor_CommentViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_COMMENT_REPLY];
    }

    public function getAuthornameComponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::COMPONENT_LAYOUT_MULTIPLEUSER_CONTEXTUALPOSTAUTHOR];
    }

    public function getAuthoravatarComponent(\PoP\ComponentModel\Component\Component $component)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            return [PoP_Module_Processor_PostAuthorAvatarLayouts::class, PoP_Module_Processor_PostAuthorAvatarLayouts::COMPONENT_LAYOUTPOST_AUTHORAVATAR];
        }

        return null;
    }

    /**
     * @return RelationalComponentFieldNode[]
     */
    public function getRelationalComponentFieldNodes(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getRelationalComponentFieldNodes($component);

        $components = array(
            $this->getAuthornameComponent($component),
        );

        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            if ($authoravatar = $this->getAuthoravatarComponent($component)) {
                $components[] = $authoravatar;
            }
        }

        $ret[] = new RelationalComponentFieldNode(
            new LeafField(
                'author',
                null,
                [],
                [],
                ASTNodesFactory::getNonSpecificLocation()
            ),
            $components
        );

        return $ret;
    }

    public function isRuntimeAdded(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return false;
    }

    /**
     * @todo Migrate from string to LeafComponentFieldNode
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentFieldNode[]
     */
    public function getLeafComponentFieldNodes(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getLeafComponentFieldNodes($component, $props);

        $ret = array_merge(
            $ret,
            array('date')
        );

        return $ret;
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $btnreply = $this->getBtnreplyComponent($component);
        $authorname = $this->getAuthornameComponent($component);

        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['btn-replycomment'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($btnreply);
        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['authorname'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($authorname);

        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            if ($authoravatar = $this->getAuthoravatarComponent($component)) {
                $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['authoravatar'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($authoravatar);
            }
        }

        if ($content_component = $this->getContentSubcomponent($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['content'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($content_component);
        }

        if ($abovelayout_components = $this->getAbovelayoutLayoutSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['abovelayout'] = array_map(
                \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName(...),
                $abovelayout_components
            );
        }

        return $ret;
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        if ($this->isRuntimeAdded($component, $props)) {
            // The 2 functions below keep them in this order: first must open the collapse, only then can scroll down to that position

            // Also add the collapse if the comment is inside the collapse. Eg: SimpleView Feed
            $this->addJsmethod($ret, 'openParentCollapse');

            // Highlight the comment when the user just adds it
            $this->addJsmethod($ret, 'highlight');
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        if ($this->isRuntimeAdded($component, $props)) {
            $this->appendProp($component, $props, 'class', 'pop-highlight');
        }

        parent::initModelProps($component, $props);
    }
}
