<?php
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Facades\HelperServices\RequestHelperServiceFacade;
use PoP\ComponentModel\ComponentFiltering\ComponentFilterManager;
use PoP\Engine\ComponentFilters\MainContentComponent;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

define('GD_SUBMITFORMTYPE_DELEGATE', 'delegate');

abstract class PoP_Module_Processor_DelegatorFiltersBase extends PoP_Module_Processor_FiltersBase
{
    public function getAction(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        // The delegator filter will simply point to the current page, adding ?componentFilter=mainContentComponent so that is the module that gets filtered
        $requestHelperService = RequestHelperServiceFacade::getInstance();
        return $requestHelperService->getCurrentURL();
    }

    public function initWebPlatformModelProps(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var MainContentComponent */
        $mainContentComponent = $instanceManager->getInstance(MainContentComponent::class);
        $this->mergeImmutableJsconfigurationProp(
            $component,
            $props,
            array(
                'fetchparams' => array(
                    Params::COMPONENTFILTER => $mainContentComponent->getName(),
                ),
            )
        );
        parent::initWebPlatformModelProps($component, $props);
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        // Depending on the form type, execute a js method or another
        $form_type = $this->getFormType($component, $props);
        if ($form_type == GD_SUBMITFORMTYPE_DELEGATE) {
            $this->addJsmethod($ret, 'initDelegatorFilter');
        }

        return $ret;
    }

    public function getFormType(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return GD_SUBMITFORMTYPE_DELEGATE;
    }

    // Method to override, giving the jQuery selector to the proxied form
    public function getBlockTarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return null;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
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
