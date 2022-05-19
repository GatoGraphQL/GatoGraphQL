<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;

abstract class PoP_Module_Processor_CommentLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);
        $ret[] = $this->getBtnreplyModule($component);

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

    public function getContentSubcomponent(array $component)
    {
        return [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::COMPONENT_LAYOUT_CONTENT_COMMENT];
    }

    public function getAbovelayoutLayoutSubcomponents(array $component)
    {
        return array();
    }

    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_COMMENT];
    }

    public function getBtnreplyModule(array $component)
    {
        return [PoP_Module_Processor_CommentViewComponentButtons::class, PoP_Module_Processor_CommentViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_COMMENT_REPLY];
    }

    public function getAuthornameModule(array $component)
    {
        return [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::COMPONENT_LAYOUT_MULTIPLEUSER_CONTEXTUALPOSTAUTHOR];
    }

    public function getAuthoravatarModule(array $component)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            return [PoP_Module_Processor_PostAuthorAvatarLayouts::class, PoP_Module_Processor_PostAuthorAvatarLayouts::COMPONENT_LAYOUTPOST_AUTHORAVATAR];
        }

        return null;
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getRelationalSubcomponents(array $component): array
    {
        $ret = parent::getRelationalSubcomponents($component);

        $components = array(
            $this->getAuthornameModule($component),
        );

        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            if ($authoravatar = $this->getAuthoravatarModule($component)) {
                $components[] = $authoravatar;
            }
        }

        $ret[] = new RelationalModuleField(
            'author',
            $components
        );

        return $ret;
    }

    public function isRuntimeAdded(array $component, array &$props)
    {
        return false;
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        $ret = parent::getDataFields($component, $props);

        $ret = array_merge(
            $ret,
            array('date')
        );

        return $ret;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $btnreply = $this->getBtnreplyModule($component);
        $authorname = $this->getAuthornameModule($component);

        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['btn-replycomment'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName($btnreply);
        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['authorname'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName($authorname);

        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            if ($authoravatar = $this->getAuthoravatarModule($component)) {
                $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['authoravatar'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName($authoravatar);
            }
        }

        if ($content_component = $this->getContentSubcomponent($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['content'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName($content_component);
        }

        if ($abovelayout_components = $this->getAbovelayoutLayoutSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['abovelayout'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance(), 'getComponentOutputName'],
                $abovelayout_components
            );
        }

        return $ret;
    }

    public function getJsmethods(array $component, array &$props)
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

    public function initModelProps(array $component, array &$props): void
    {
        if ($this->isRuntimeAdded($component, $props)) {
            $this->appendProp($component, $props, 'class', 'pop-highlight');
        }

        parent::initModelProps($component, $props);
    }
}
