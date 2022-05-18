<?php
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Facades\HelperServices\RequestHelperServiceFacade;
use PoP\ComponentModel\ComponentFiltering\ComponentFilterManager;
use PoP\Engine\ComponentFilters\MainContentModule;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

define('GD_SUBMITFORMTYPE_DELEGATE', 'delegate');

abstract class PoP_Module_Processor_DelegatorFiltersBase extends PoP_Module_Processor_FiltersBase
{
    public function getAction(array $module, array &$props)
    {
        // The delegator filter will simply point to the current page, adding ?modulefilter=maincontentmodule so that is the module that gets filtered
        $requestHelperService = RequestHelperServiceFacade::getInstance();
        return $requestHelperService->getCurrentURL();
    }

    public function initWebPlatformModelProps(array $module, array &$props)
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var MainContentModule */
        $mainContentModule = $instanceManager->getInstance(MainContentModule::class);
        $this->mergeImmutableJsconfigurationProp(
            $module,
            $props,
            array(
                'fetchparams' => array(
                    Params::MODULEFILTER => $mainContentModule->getName(),
                ),
            )
        );
        parent::initWebPlatformModelProps($module, $props);
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        // Depending on the form type, execute a js method or another
        $form_type = $this->getFormType($module, $props);
        if ($form_type == GD_SUBMITFORMTYPE_DELEGATE) {
            $this->addJsmethod($ret, 'initDelegatorFilter');
        }

        return $ret;
    }

    public function getFormType(array $module, array &$props)
    {
        return GD_SUBMITFORMTYPE_DELEGATE;
    }

    // Method to override, giving the jQuery selector to the proxied form
    public function getBlockTarget(array $module, array &$props)
    {
        return null;
    }

    public function initModelProps(array $module, array &$props): void
    {

        // Specify the block target
        if ($block_target = $this->getBlockTarget($module, $props)) {
            $this->mergeProp(
                $module,
                $props,
                'params',
                array(
                    'data-blocktarget' => $block_target
                )
            );
        }

        parent::initModelProps($module, $props);
    }
}
