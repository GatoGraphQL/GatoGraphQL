<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Misc\GeneralUtils;

abstract class PoP_Module_Processor_BlocksBase extends PoP_Module_Processor_BasicBlocksBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_BLOCK];
    }

    public function getSubmenuSubmodule(array $componentVariation)
    {
        return null;
    }

    public function getLatestcountSubmodule(array $componentVariation)
    {
        return null;
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($controlgroup_top = $this->getControlgroupTopSubmodule($componentVariation)) {
            $ret[] = $controlgroup_top;
        }
        if ($controlgroup_bottom = $this->getControlgroupBottomSubmodule($componentVariation)) {
            $ret[] = $controlgroup_bottom;
        }

        if ($submenu = $this->getSubmenuSubmodule($componentVariation)) {
            $ret[] = $submenu;
        }

        if ($latestcount = $this->getLatestcountSubmodule($componentVariation)) {
            $ret[] = $latestcount;
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($this->showDisabledLayer($componentVariation, $props)) {
            $ret['show-disabled-layer'] = true;
        }

        if ($this->getProp($componentVariation, $props, 'show-controls-top')) {
            if ($controlgroup_top = $this->getControlgroupTopSubmodule($componentVariation)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['controlgroup-top'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($controlgroup_top);
            }
        }

        if ($latestcount = $this->getLatestcountSubmodule($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['latestcount'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($latestcount);
        }

        if ($this->addClearfixdiv($componentVariation)) {
            $ret['add-clearfixdiv'] = true;
        }

        if ($description_bottom = $this->getProp($componentVariation, $props, 'description-bottom')) {
            $ret['description-bottom'] = $description_bottom;
        }
        if ($description_top = $this->getProp($componentVariation, $props, 'description-top')) {
            $ret['description-top'] = $description_top;
        }
        if ($description_abovetitle = $this->getProp($componentVariation, $props, 'description-abovetitle')) {
            $ret['description-abovetitle'] = $description_abovetitle;
        }

        return $ret;
    }

    public function getMutableonrequestConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        // Only add the submenu if this is the main block! That way, both blockgroups and blocks can define the submenu, but only the main of them will show it
        // Also, only add submenu if single post is published, hence this goes under mutableonrequest
        if ($this->getProp($componentVariation, $props, 'show-submenu')) {
            if ($submenu = $this->getSubmenuSubmodule($componentVariation)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['submenu'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($submenu);
            }
        }

        if ($this->getProp($componentVariation, $props, 'show-controls-bottom')) {
            if ($controlgroup_bottom = $this->getControlgroupBottomSubmodule($componentVariation)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['controlgroup-bottom'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($controlgroup_bottom);
            }
        }

        return $ret;
    }

    protected function showDisabledLayer(array $componentVariation, array &$props)
    {
        return true;
    }
    protected function showDisabledLayerIfCheckpointFailed(array $componentVariation, array &$props)
    {
        return false;
    }

    public function getJsdataFeedback(array $componentVariation, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array
    {
        $ret = parent::getJsdataFeedback($componentVariation, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

        if ($this->showDisabledLayer($componentVariation, $props) && $this->showDisabledLayerIfCheckpointFailed($componentVariation, $props)) {
            $ret['blockHandleDisabledLayer']['checkpoint-failed'] = $dataaccess_checkpoint_validation !== null || $actionexecution_checkpoint_validation !== null;
        }

        return $ret;
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        // Add the Disabled Layer on top of the block after the checkpoint fails
        if ($this->showDisabledLayer($componentVariation, $props) && $this->showDisabledLayerIfCheckpointFailed($componentVariation, $props)) {
            $this->addJsmethod($ret, 'blockHandleDisabledLayer');
        }

        return $ret;
    }

    public function initWebPlatformRequestProps(array $componentVariation, array &$props)
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($submenu = $this->getSubmenuSubmodule($componentVariation)) {
            $moduleFullName = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleFullName($componentVariation);
            $submenu_id = $componentprocessor_manager->getProcessor($submenu)->getFrontendId($submenu, $props[$moduleFullName][\PoP\ComponentModel\Constants\Props::SUBMODULES]);
            $submenu_target = '#'.$submenu_id.'-xs';
            $this->setProp([PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_SUBMENUTOGGLE_XS], $props, 'submenu-target', $submenu_target);
        }

        parent::initWebPlatformRequestProps($componentVariation, $props);
    }

    public function initRequestProps(array $componentVariation, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        // // Only add the submenu if this is the main block! That way, both blockgroups and blocks can define the submenu, but only the main of them will show it
        // $this->setProp($componentVariation, $props, 'is-mainblock', false);
        // if ($this->getProp($componentVariation, $props, 'is-mainblock')) {

        //     $this->setProp($componentVariation, $props, 'show-submenu', true);
        // }
        $this->setProp($componentVariation, $props, 'show-submenu', true);
        if ($this->getProp($componentVariation, $props, 'show-submenu')) {
            // Needed to hide a nested submenu (eg: blockgroup and block both have submenu) through CSS
            $this->appendProp($componentVariation, $props, 'runtime-class', 'withsubmenu');
        }

        if ($showControls = $this->getProp($componentVariation, $props, 'show-controls')) {
            $this->setProp($componentVariation, $props, 'show-controls-bottom', true);
        }

        parent::initRequestProps($componentVariation, $props);
    }

    public function initWebPlatformModelProps(array $componentVariation, array &$props)
    {

        // Block target for the controls. This is set in advance by the blockgroup (panelbootstrapjavascript-base) or,
        // whenever the page access the block directly (eg: opening Stance in the quickview) then here
        // $blocktarget = '#'.$props['block-id'];
        $blocktarget = '#'.$this->getFrontendId($componentVariation, $props);
        if ($controlgroup_top = $this->getControlgroupTopSubmodule($componentVariation)) {
            $this->setProp($controlgroup_top, $props, 'control-target', $blocktarget);
        }
        if ($controlgroup_bottom = $this->getControlgroupBottomSubmodule($componentVariation)) {
            $this->setProp($controlgroup_bottom, $props, 'control-target', $blocktarget);
        }

        parent::initWebPlatformModelProps($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->setProp($componentVariation, $props, 'show-controls', true);
        if ($showControls = $this->getProp($componentVariation, $props, 'show-controls')) {
            $this->setProp($componentVariation, $props, 'show-controls-top', true);
        }

        if ($description_bottom = $this->getDescriptionBottom($componentVariation, $props)) {
            $this->setProp($componentVariation, $props, 'description-bottom', $description_bottom);
        }
        if ($description_top = $this->getDescriptionTop($componentVariation, $props)) {
            $this->setProp($componentVariation, $props, 'description-top', $description_top);
        }
        if ($description_abovetitle = $this->getDescriptionAbovetitle($componentVariation, $props)) {
            $this->setProp($componentVariation, $props, 'description-abovetitle', $description_abovetitle);
        }

        /**
         * Allow to add more stuff
         */
        \PoP\Root\App::doAction(
            'PoP_Module_Processor_BlocksBase:initModelProps',
            array(&$props),
            $componentVariation,
            $this
        );

        parent::initModelProps($componentVariation, $props);
    }

    protected function getControlgroupTopSubmodule(array $componentVariation)
    {
        return null;
    }
    protected function getControlgroupBottomSubmodule(array $componentVariation)
    {
        return null;
    }
    protected function addClearfixdiv(array $componentVariation)
    {
        return true;
    }
    protected function getDescriptionBottom(array $componentVariation, array &$props)
    {
        return null;
    }
    protected function getDescriptionTop(array $componentVariation, array &$props)
    {
        return null;
    }
    protected function getDescriptionAbovetitle(array $componentVariation, array &$props)
    {
        return null;
    }

    protected function getBlocksectionsClasses(array $componentVariation)
    {
        $ret = parent::getBlocksectionsClasses($componentVariation);

        $ret['controlgroup-top'] = 'right pull-right';
        $ret['controlgroup-bottom'] = 'bottom pull-right';

        return $ret;
    }

    //-------------------------------------------------
    // Feedback
    //-------------------------------------------------

    public function getDataFeedback(array $componentVariation, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array
    {
        $ret = parent::getDataFeedback($componentVariation, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);
        if ($dataaccess_checkpoint_validation !== null || $actionexecution_checkpoint_validation !== null) {
            $ret['checkpoint-failed'] = true;
        }

        return $ret;
    }
}
