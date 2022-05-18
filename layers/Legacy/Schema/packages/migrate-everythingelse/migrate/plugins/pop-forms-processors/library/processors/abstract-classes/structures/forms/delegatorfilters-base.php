<?php
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Facades\HelperServices\RequestHelperServiceFacade;
use PoP\ComponentModel\ComponentFiltering\ComponentFilterManager;
use PoP\Engine\ComponentFilters\MainContentModule;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

define('GD_SUBMITFORMTYPE_DELEGATE', 'delegate');

abstract class PoP_Module_Processor_DelegatorFiltersBase extends PoP_Module_Processor_FiltersBase
{
    public function getAction(array $component, array &$props)
    {
        // The delegator filter will simply point to the current page, adding ?modulefilter=maincontentmodule so that is the module that gets filtered
        $requestHelperService = RequestHelperServiceFacade::getInstance();
        return $requestHelperService->getCurrentURL();
    }

    public function initWebPlatformModelProps(array $component, array &$props)
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var MainContentModule */
        $mainContentModule = $instanceManager->getInstance(MainContentModule::class);
        $this->mergeImmutableJsconfigurationProp(
            $component,
            $props,
            array(
                'fetchparams' => array(
                    Params::COMPONENTFILTER => $mainContentModule->getName(),
                ),
            )
        );
        parent::initWebPlatformModelProps($component, $props);
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        // Depending on the form type, execute a js method or another
        $form_type = $this->getFormType($component, $props);
        if ($form_type == GD_SUBMITFORMTYPE_DELEGATE) {
            $this->addJsmethod($ret, 'initDelegatorFilter');
        }

        return $ret;
    }

    public function getFormType(array $component, array &$props)
    {
        return GD_SUBMITFORMTYPE_DELEGATE;
    }

    // Method to override, giving the jQuery selector to the proxied form
    public function getBlockTarget(array $component, array &$props)
    {
        return null;
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Specify the block target
        if ($block_target = $this->getBlockTarget($component, $props)) {
            $this->mergeProp(
                $component,
                $props,
                'params',
                array(
                    'data-blocktarget' => $block_target
                )
            );
        }

        parent::initModelProps($component, $props);
    }
}
