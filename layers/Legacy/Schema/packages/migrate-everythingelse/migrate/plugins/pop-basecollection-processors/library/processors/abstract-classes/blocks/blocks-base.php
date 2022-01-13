<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Modules\ModuleUtils;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

abstract class PoP_Module_Processor_BlocksBase extends PoP_Module_Processor_BasicBlocksBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_BLOCK];
    }

    public function getSubmenuSubmodule(array $module)
    {
        return null;
    }

    public function getLatestcountSubmodule(array $module)
    {
        return null;
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($controlgroup_top = $this->getControlgroupTopSubmodule($module)) {
            $ret[] = $controlgroup_top;
        }
        if ($controlgroup_bottom = $this->getControlgroupBottomSubmodule($module)) {
            $ret[] = $controlgroup_bottom;
        }

        if ($submenu = $this->getSubmenuSubmodule($module)) {
            $ret[] = $submenu;
        }

        if ($latestcount = $this->getLatestcountSubmodule($module)) {
            $ret[] = $latestcount;
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        if ($this->showDisabledLayer($module, $props)) {
            $ret['show-disabled-layer'] = true;
        }

        if ($this->getProp($module, $props, 'show-controls-top')) {
            if ($controlgroup_top = $this->getControlgroupTopSubmodule($module)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['controlgroup-top'] = ModuleUtils::getModuleOutputName($controlgroup_top);
            }
        }

        if ($latestcount = $this->getLatestcountSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['latestcount'] = ModuleUtils::getModuleOutputName($latestcount);
        }

        if ($this->addClearfixdiv($module)) {
            $ret['add-clearfixdiv'] = true;
        }

        if ($description_bottom = $this->getProp($module, $props, 'description-bottom')) {
            $ret['description-bottom'] = $description_bottom;
        }
        if ($description_top = $this->getProp($module, $props, 'description-top')) {
            $ret['description-top'] = $description_top;
        }
        if ($description_abovetitle = $this->getProp($module, $props, 'description-abovetitle')) {
            $ret['description-abovetitle'] = $description_abovetitle;
        }

        return $ret;
    }

    public function getMutableonrequestConfiguration(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        // Only add the submenu if this is the main block! That way, both blockgroups and blocks can define the submenu, but only the main of them will show it
        // Also, only add submenu if single post is published, hence this goes under mutableonrequest
        if ($this->getProp($module, $props, 'show-submenu')) {
            if ($submenu = $this->getSubmenuSubmodule($module)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['submenu'] = ModuleUtils::getModuleOutputName($submenu);
            }
        }

        if ($this->getProp($module, $props, 'show-controls-bottom')) {
            if ($controlgroup_bottom = $this->getControlgroupBottomSubmodule($module)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['controlgroup-bottom'] = ModuleUtils::getModuleOutputName($controlgroup_bottom);
            }
        }

        return $ret;
    }

    protected function showDisabledLayer(array $module, array &$props)
    {
        return true;
    }
    protected function showDisabledLayerIfCheckpointFailed(array $module, array &$props)
    {
        return false;
    }

    public function getJsdataFeedback(array $module, array &$props, array $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids): array
    {
        $ret = parent::getJsdataFeedback($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

        if ($this->showDisabledLayer($module, $props) && $this->showDisabledLayerIfCheckpointFailed($module, $props)) {
            $ret['blockHandleDisabledLayer']['checkpoint-failed'] = GeneralUtils::isError($dataaccess_checkpoint_validation) || GeneralUtils::isError($actionexecution_checkpoint_validation);
        }

        return $ret;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        // Add the Disabled Layer on top of the block after the checkpoint fails
        if ($this->showDisabledLayer($module, $props) && $this->showDisabledLayerIfCheckpointFailed($module, $props)) {
            $this->addJsmethod($ret, 'blockHandleDisabledLayer');
        }

        return $ret;
    }

    public function initWebPlatformRequestProps(array $module, array &$props)
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        if ($submenu = $this->getSubmenuSubmodule($module)) {
            $moduleFullName = ModuleUtils::getModuleFullName($module);
            $submenu_id = $moduleprocessor_manager->getProcessor($submenu)->getFrontendId($submenu, $props[$moduleFullName][\PoP\ComponentModel\Constants\Props::SUBMODULES]);
            $submenu_target = '#'.$submenu_id.'-xs';
            $this->setProp([PoP_Module_Processor_AnchorControls::class, PoP_Module_Processor_AnchorControls::MODULE_ANCHORCONTROL_SUBMENUTOGGLE_XS], $props, 'submenu-target', $submenu_target);
        }

        parent::initWebPlatformRequestProps($module, $props);
    }

    public function initRequestProps(array $module, array &$props): void
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        // // Only add the submenu if this is the main block! That way, both blockgroups and blocks can define the submenu, but only the main of them will show it
        // $this->setProp($module, $props, 'is-mainblock', false);
        // if ($this->getProp($module, $props, 'is-mainblock')) {

        //     $this->setProp($module, $props, 'show-submenu', true);
        // }
        $this->setProp($module, $props, 'show-submenu', true);
        if ($this->getProp($module, $props, 'show-submenu')) {
            // Needed to hide a nested submenu (eg: blockgroup and block both have submenu) through CSS
            $this->appendProp($module, $props, 'runtime-class', 'withsubmenu');
        }

        if ($showControls = $this->getProp($module, $props, 'show-controls')) {
            $this->setProp($module, $props, 'show-controls-bottom', true);
        }

        parent::initRequestProps($module, $props);
    }

    public function initWebPlatformModelProps(array $module, array &$props)
    {

        // Block target for the controls. This is set in advance by the blockgroup (panelbootstrapjavascript-base) or,
        // whenever the page access the block directly (eg: opening Stance in the quickview) then here
        // $blocktarget = '#'.$props['block-id'];
        $blocktarget = '#'.$this->getFrontendId($module, $props);
        if ($controlgroup_top = $this->getControlgroupTopSubmodule($module)) {
            $this->setProp($controlgroup_top, $props, 'control-target', $blocktarget);
        }
        if ($controlgroup_bottom = $this->getControlgroupBottomSubmodule($module)) {
            $this->setProp($controlgroup_bottom, $props, 'control-target', $blocktarget);
        }

        parent::initWebPlatformModelProps($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        $this->setProp($module, $props, 'show-controls', true);
        if ($showControls = $this->getProp($module, $props, 'show-controls')) {
            $this->setProp($module, $props, 'show-controls-top', true);
        }

        if ($description_bottom = $this->getDescriptionBottom($module, $props)) {
            $this->setProp($module, $props, 'description-bottom', $description_bottom);
        }
        if ($description_top = $this->getDescriptionTop($module, $props)) {
            $this->setProp($module, $props, 'description-top', $description_top);
        }
        if ($description_abovetitle = $this->getDescriptionAbovetitle($module, $props)) {
            $this->setProp($module, $props, 'description-abovetitle', $description_abovetitle);
        }

        /**
         * Allow to add more stuff
         */
        HooksAPIFacade::getInstance()->doAction(
            'PoP_Module_Processor_BlocksBase:initModelProps',
            array(&$props),
            $module,
            $this
        );

        parent::initModelProps($module, $props);
    }

    protected function getControlgroupTopSubmodule(array $module)
    {
        return null;
    }
    protected function getControlgroupBottomSubmodule(array $module)
    {
        return null;
    }
    protected function addClearfixdiv(array $module)
    {
        return true;
    }
    protected function getDescriptionBottom(array $module, array &$props)
    {
        return null;
    }
    protected function getDescriptionTop(array $module, array &$props)
    {
        return null;
    }
    protected function getDescriptionAbovetitle(array $module, array &$props)
    {
        return null;
    }

    protected function getBlocksectionsClasses(array $module)
    {
        $ret = parent::getBlocksectionsClasses($module);

        $ret['controlgroup-top'] = 'right pull-right';
        $ret['controlgroup-bottom'] = 'bottom pull-right';

        return $ret;
    }

    //-------------------------------------------------
    // Feedback
    //-------------------------------------------------

    public function getDataFeedback(array $module, array &$props, array $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids): array
    {
        $ret = parent::getDataFeedback($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);
        if (GeneralUtils::isError($dataaccess_checkpoint_validation) || GeneralUtils::isError($actionexecution_checkpoint_validation)) {
            $ret['checkpoint-failed'] = true;
        }

        return $ret;
    }
}
