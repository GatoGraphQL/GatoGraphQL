<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;

abstract class PoP_Module_Processor_CommentLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);
        $ret[] = $this->getBtnreplyModule($componentVariation);

        if ($abovelayout_modules = $this->getAbovelayoutLayoutSubmodules($componentVariation)) {
            $ret = array_merge(
                $ret,
                $abovelayout_modules
            );
        }
        if ($content_module = $this->getContentSubmodule($componentVariation)) {
            $ret[] = $content_module;
        }
        return $ret;
    }

    public function getContentSubmodule(array $componentVariation)
    {
        return [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_COMMENT];
    }

    public function getAbovelayoutLayoutSubmodules(array $componentVariation)
    {
        return array();
    }

    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_COMMENT];
    }

    public function getBtnreplyModule(array $componentVariation)
    {
        return [PoP_Module_Processor_CommentViewComponentButtons::class, PoP_Module_Processor_CommentViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY];
    }

    public function getAuthornameModule(array $componentVariation)
    {
        return [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::MODULE_LAYOUT_MULTIPLEUSER_CONTEXTUALPOSTAUTHOR];
    }

    public function getAuthoravatarModule(array $componentVariation)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            return [PoP_Module_Processor_PostAuthorAvatarLayouts::class, PoP_Module_Processor_PostAuthorAvatarLayouts::MODULE_LAYOUTPOST_AUTHORAVATAR];
        }

        return null;
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getRelationalSubmodules(array $componentVariation): array
    {
        $ret = parent::getRelationalSubmodules($componentVariation);

        $componentVariations = array(
            $this->getAuthornameModule($componentVariation),
        );

        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            if ($authoravatar = $this->getAuthoravatarModule($componentVariation)) {
                $componentVariations[] = $authoravatar;
            }
        }

        $ret[] = new RelationalModuleField(
            'author',
            $componentVariations
        );

        return $ret;
    }

    public function isRuntimeAdded(array $componentVariation, array &$props)
    {
        return false;
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        $ret = parent::getDataFields($componentVariation, $props);

        $ret = array_merge(
            $ret,
            array('date')
        );

        return $ret;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $btnreply = $this->getBtnreplyModule($componentVariation);
        $authorname = $this->getAuthornameModule($componentVariation);

        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['btn-replycomment'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($btnreply);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['authorname'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($authorname);

        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            if ($authoravatar = $this->getAuthoravatarModule($componentVariation)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['authoravatar'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($authoravatar);
            }
        }

        if ($content_module = $this->getContentSubmodule($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['content'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($content_module);
        }

        if ($abovelayout_modules = $this->getAbovelayoutLayoutSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['abovelayout'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $abovelayout_modules
            );
        }

        return $ret;
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        if ($this->isRuntimeAdded($componentVariation, $props)) {
            // The 2 functions below keep them in this order: first must open the collapse, only then can scroll down to that position

            // Also add the collapse if the comment is inside the collapse. Eg: SimpleView Feed
            $this->addJsmethod($ret, 'openParentCollapse');

            // Highlight the comment when the user just adds it
            $this->addJsmethod($ret, 'highlight');
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        if ($this->isRuntimeAdded($componentVariation, $props)) {
            $this->appendProp($componentVariation, $props, 'class', 'pop-highlight');
        }

        parent::initModelProps($componentVariation, $props);
    }
}
