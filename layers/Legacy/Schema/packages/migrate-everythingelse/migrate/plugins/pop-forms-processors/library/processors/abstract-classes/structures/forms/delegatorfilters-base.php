<?php
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Facades\HelperServices\RequestHelperServiceFacade;
use PoP\ComponentModel\ComponentFiltering\ComponentFilterManager;
use PoP\Engine\ComponentFilters\MainContentModule;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

define('GD_SUBMITFORMTYPE_DELEGATE', 'delegate');

abstract class PoP_Module_Processor_DelegatorFiltersBase extends PoP_Module_Processor_FiltersBase
{
    public function getAction(array $componentVariation, array &$props)
    {
        // The delegator filter will simply point to the current page, adding ?modulefilter=maincontentmodule so that is the module that gets filtered
        $requestHelperService = RequestHelperServiceFacade::getInstance();
        return $requestHelperService->getCurrentURL();
    }

    public function initWebPlatformModelProps(array $componentVariation, array &$props)
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var MainContentModule */
        $mainContentModule = $instanceManager->getInstance(MainContentModule::class);
        $this->mergeImmutableJsconfigurationProp(
            $componentVariation,
            $props,
            array(
                'fetchparams' => array(
                    Params::MODULEFILTER => $mainContentModule->getName(),
                ),
            )
        );
        parent::initWebPlatformModelProps($componentVariation, $props);
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        // Depending on the form type, execute a js method or another
        $form_type = $this->getFormType($componentVariation, $props);
        if ($form_type == GD_SUBMITFORMTYPE_DELEGATE) {
            $this->addJsmethod($ret, 'initDelegatorFilter');
        }

        return $ret;
    }

    public function getFormType(array $componentVariation, array &$props)
    {
        return GD_SUBMITFORMTYPE_DELEGATE;
    }

    // Method to override, giving the jQuery selector to the proxied form
    public function getBlockTarget(array $componentVariation, array &$props)
    {
        return null;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {

        // Specify the block target
        if ($block_target = $this->getBlockTarget($componentVariation, $props)) {
            $this->mergeProp(
                $componentVariation,
                $props,
                'params',
                array(
                    'data-blocktarget' => $block_target
                )
            );
        }

        parent::initModelProps($componentVariation, $props);
    }
}
