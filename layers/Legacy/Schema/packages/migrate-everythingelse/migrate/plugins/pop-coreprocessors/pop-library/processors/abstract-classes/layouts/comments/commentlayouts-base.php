<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;

abstract class PoP_Module_Processor_CommentLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);
        $ret[] = $this->getBtnreplyModule($module);

        if ($abovelayout_modules = $this->getAbovelayoutLayoutSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $abovelayout_modules
            );
        }
        if ($content_module = $this->getContentSubmodule($module)) {
            $ret[] = $content_module;
        }
        return $ret;
    }

    public function getContentSubmodule(array $module)
    {
        return [PoP_Module_Processor_ContentLayouts::class, PoP_Module_Processor_ContentLayouts::MODULE_LAYOUT_CONTENT_COMMENT];
    }

    public function getAbovelayoutLayoutSubmodules(array $module)
    {
        return array();
    }

    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_COMMENT];
    }

    public function getBtnreplyModule(array $module)
    {
        return [PoP_Module_Processor_CommentViewComponentButtons::class, PoP_Module_Processor_CommentViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY];
    }

    public function getAuthornameModule(array $module)
    {
        return [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::MODULE_LAYOUT_MULTIPLEUSER_CONTEXTUALPOSTAUTHOR];
    }

    public function getAuthoravatarModule(array $module)
    {
        if (defined('POP_AVATARPROCESSORS_INITIALIZED')) {
            return [PoP_Module_Processor_PostAuthorAvatarLayouts::class, PoP_Module_Processor_PostAuthorAvatarLayouts::MODULE_LAYOUTPOST_AUTHORAVATAR];
        }

        return null;
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getDomainSwitchingSubmodules(array $module): array
    {
        $ret = parent::getDomainSwitchingSubmodules($module);

        $modules = array(
            $this->getAuthornameModule($module),
        );

        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            if ($authoravatar = $this->getAuthoravatarModule($module)) {
                $modules[] = $authoravatar;
            }
        }

        $ret[] = new RelationalModuleField(
            'author',
            $modules
        );

        return $ret;
    }

    public function isRuntimeAdded(array $module, array &$props)
    {
        return false;
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        $ret = parent::getDataFields($module, $props);

        $ret = array_merge(
            $ret,
            array('date')
        );

        return $ret;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $btnreply = $this->getBtnreplyModule($module);
        $authorname = $this->getAuthornameModule($module);

        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['btn-replycomment'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($btnreply);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['authorname'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($authorname);

        if (PoP_Application_ConfigurationUtils::useUseravatar()) {
            if ($authoravatar = $this->getAuthoravatarModule($module)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['authoravatar'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($authoravatar);
            }
        }

        if ($content_module = $this->getContentSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['content'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($content_module);
        }

        if ($abovelayout_modules = $this->getAbovelayoutLayoutSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['abovelayout'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $abovelayout_modules
            );
        }

        return $ret;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        if ($this->isRuntimeAdded($module, $props)) {
            // The 2 functions below keep them in this order: first must open the collapse, only then can scroll down to that position

            // Also add the collapse if the comment is inside the collapse. Eg: SimpleView Feed
            $this->addJsmethod($ret, 'openParentCollapse');

            // Highlight the comment when the user just adds it
            $this->addJsmethod($ret, 'highlight');
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        if ($this->isRuntimeAdded($module, $props)) {
            $this->appendProp($module, $props, 'class', 'pop-highlight');
        }

        parent::initModelProps($module, $props);
    }
}
