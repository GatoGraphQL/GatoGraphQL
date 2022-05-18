<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Misc\GeneralUtils;

abstract class PoP_Module_Processor_BlocksBase extends PoP_Module_Processor_BasicBlocksBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_BLOCK];
    }

    public function getSubmenuSubmodule(array $component)
    {
        return null;
    }

    public function getLatestcountSubmodule(array $component)
    {
        return null;
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        if ($controlgroup_top = $this->getControlgroupTopSubmodule($component)) {
            $ret[] = $controlgroup_top;
        }
        if ($controlgroup_bottom = $this->getControlgroupBottomSubmodule($component)) {
            $ret[] = $controlgroup_bottom;
        }

        if ($submenu = $this->getSubmenuSubmodule($component)) {
            $ret[] = $submenu;
        }

        if ($latestcount = $this->getLatestcountSubmodule($component)) {
            $ret[] = $latestcount;
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($this->showDisabledLayer($component, $props)) {
            $ret['show-disabled-layer'] = true;
        }

        if ($this->getProp($component, $props, 'show-controls-top')) {
            if ($controlgroup_top = $this->getControlgroupTopSubmodule($component)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['controlgroup-top'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($controlgroup_top);
            }
        }

        if ($latestcount = $this->getLatestcountSubmodule($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['latestcount'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($latestcount);
        }

        if ($this->addClearfixdiv($component)) {
            $ret['add-clearfixdiv'] = true;
        }

        if ($description_bottom = $this->getProp($component, $props, 'description-bottom')) {
            $ret['description-bottom'] = $description_bottom;
        }
        if ($description_top = $this->getProp($component, $props, 'description-top')) {
            $ret['description-top'] = $description_top;
        }
        if ($description_abovetitle = $this->getProp($component, $props, 'description-abovetitle')) {
            $ret['description-abovetitle'] = $description_abovetitle;
        }

        return $ret;
    }

    public function getMutableonrequestConfiguration(array $component, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        // Only add the submenu if this is the main block! That way, both blockgroups and blocks can define the submenu, but only the main of them will show it
        // Also, only add submenu if single post is published, hence this goes under mutableonrequest
        if ($this->getProp($component, $props, 'show-submenu')) {
            if ($submenu = $this->getSubmenuSubmodule($component)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['submenu'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($submenu);
            }
        }

        if ($this->getProp($component, $props, 'show-controls-bottom')) {
            if ($controlgroup_bottom = $this->getControlgroupBottomSubmodule($component)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['controlgroup-bottom'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($controlgroup_bottom);
            }
        }

        return $ret;
    }

    protected function showDisabledLayer(array $component, array &$props)
    {
        return true;
    }
    protected function showDisabledLayerIfCheckpointFailed(array $component, array &$props)
    {
        return false;
    }

    public function getJsdataFeedback(array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array
    {
        $ret = parent::getJsdataFeedback($component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

        if ($this->showDisabledLayer($component, $props) && $this->showDisabledLayerIfCheckpointFailed($component, $props)) {
            $ret['blockHandleDisabledLayer']['checkpoint-failed'] = $dataaccess_checkpoint_validation !== null || $actionexecution_checkpoint_validation !== null;
        }

        return $ret;
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        // Add the Disabled Layer on top of the block after the checkpoint fails
        if ($this->showDisabledLayer($component, $props) && $this->showDisabledLayerIfCheckpointFailed($component, $props)) {
            $this->addJsmethod($ret, 'blockHandleDisabledLayer');
        }

        return $ret;
    }

    public function initWebPlatformRequestProps(array $component, array &$props)
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($submenu = $this->getSubmenuSubmodule($component)) {
            $moduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($component);
            $submenu_id = $componentprocessor_manager->getProcessor($submenu)->getFrontendId($submenu, $props[$moduleFullName][\PoP\ComponentModel\Constants\Props::SUBMODULES]);
            $submenu_target = '#'.$submenu_id.'-xs';
            $this->setProp([PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_SUBMENUTOGGLE_XS], $props, 'submenu-target', $submenu_target);
        }

        parent::initWebPlatformRequestProps($component, $props);
    }

    public function initRequestProps(array $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        // // Only add the submenu if this is the main block! That way, both blockgroups and blocks can define the submenu, but only the main of them will show it
        // $this->setProp($component, $props, 'is-mainblock', false);
        // if ($this->getProp($component, $props, 'is-mainblock')) {

        //     $this->setProp($component, $props, 'show-submenu', true);
        // }
        $this->setProp($component, $props, 'show-submenu', true);
        if ($this->getProp($component, $props, 'show-submenu')) {
            // Needed to hide a nested submenu (eg: blockgroup and block both have submenu) through CSS
            $this->appendProp($component, $props, 'runtime-class', 'withsubmenu');
        }

        if ($showControls = $this->getProp($component, $props, 'show-controls')) {
            $this->setProp($component, $props, 'show-controls-bottom', true);
        }

        parent::initRequestProps($component, $props);
    }

    public function initWebPlatformModelProps(array $component, array &$props)
    {

        // Block target for the controls. This is set in advance by the blockgroup (panelbootstrapjavascript-base) or,
        // whenever the page access the block directly (eg: opening Stance in the quickview) then here
        // $blocktarget = '#'.$props['block-id'];
        $blocktarget = '#'.$this->getFrontendId($component, $props);
        if ($controlgroup_top = $this->getControlgroupTopSubmodule($component)) {
            $this->setProp($controlgroup_top, $props, 'control-target', $blocktarget);
        }
        if ($controlgroup_bottom = $this->getControlgroupBottomSubmodule($component)) {
            $this->setProp($controlgroup_bottom, $props, 'control-target', $blocktarget);
        }

        parent::initWebPlatformModelProps($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        $this->setProp($component, $props, 'show-controls', true);
        if ($showControls = $this->getProp($component, $props, 'show-controls')) {
            $this->setProp($component, $props, 'show-controls-top', true);
        }

        if ($description_bottom = $this->getDescriptionBottom($component, $props)) {
            $this->setProp($component, $props, 'description-bottom', $description_bottom);
        }
        if ($description_top = $this->getDescriptionTop($component, $props)) {
            $this->setProp($component, $props, 'description-top', $description_top);
        }
        if ($description_abovetitle = $this->getDescriptionAbovetitle($component, $props)) {
            $this->setProp($component, $props, 'description-abovetitle', $description_abovetitle);
        }

        /**
         * Allow to add more stuff
         */
        \PoP\Root\App::doAction(
            'PoP_Module_Processor_BlocksBase:initModelProps',
            array(&$props),
            $component,
            $this
        );

        parent::initModelProps($component, $props);
    }

    protected function getControlgroupTopSubmodule(array $component)
    {
        return null;
    }
    protected function getControlgroupBottomSubmodule(array $component)
    {
        return null;
    }
    protected function addClearfixdiv(array $component)
    {
        return true;
    }
    protected function getDescriptionBottom(array $component, array &$props)
    {
        return null;
    }
    protected function getDescriptionTop(array $component, array &$props)
    {
        return null;
    }
    protected function getDescriptionAbovetitle(array $component, array &$props)
    {
        return null;
    }

    protected function getBlocksectionsClasses(array $component)
    {
        $ret = parent::getBlocksectionsClasses($component);

        $ret['controlgroup-top'] = 'right pull-right';
        $ret['controlgroup-bottom'] = 'bottom pull-right';

        return $ret;
    }

    //-------------------------------------------------
    // Feedback
    //-------------------------------------------------

    public function getDataFeedback(array $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array
    {
        $ret = parent::getDataFeedback($component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);
        if ($dataaccess_checkpoint_validation !== null || $actionexecution_checkpoint_validation !== null) {
            $ret['checkpoint-failed'] = true;
        }

        return $ret;
    }
}
