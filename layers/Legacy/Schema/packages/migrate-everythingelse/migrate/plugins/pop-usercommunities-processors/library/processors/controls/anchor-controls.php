<?php
use PoP\ComponentModel\Facades\HelperServices\RequestHelperServiceFacade;
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_AnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_URE_ANCHORCONTROL_CONTENTSOURCECOMMUNITY = 'ure-anchorcontrol-contentsourcecommunity';
    public final const MODULE_URE_ANCHORCONTROL_CONTENTSOURCEUSER = 'ure-anchorcontrol-contentsourceuser';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_ANCHORCONTROL_CONTENTSOURCECOMMUNITY],
            [self::class, self::MODULE_URE_ANCHORCONTROL_CONTENTSOURCEUSER],
        );
    }

    public function getLabel(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_URE_ANCHORCONTROL_CONTENTSOURCECOMMUNITY:
                return TranslationAPIFacade::getInstance()->__('Show Content from: Community + Members', 'ure-popprocessors');

            case self::MODULE_URE_ANCHORCONTROL_CONTENTSOURCEUSER:
                return TranslationAPIFacade::getInstance()->__('Show Content from: Community', 'ure-popprocessors');
        }

        return parent::getLabel($component, $props);
    }
    public function getText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_URE_ANCHORCONTROL_CONTENTSOURCECOMMUNITY:
                return
            '<i class="fa fa-fw fa-user-circle"></i>'.
            '+'.
            '<i class="fa fa-fw fa-users"></i>';

            case self::MODULE_URE_ANCHORCONTROL_CONTENTSOURCEUSER:
                return '<i class="fa fa-fw fa-user-circle"></i>';
        }

        return parent::getText($component, $props);
    }
    public function getHref(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_URE_ANCHORCONTROL_CONTENTSOURCECOMMUNITY:
            case self::MODULE_URE_ANCHORCONTROL_CONTENTSOURCEUSER:
                $sources = array(
                    self::MODULE_URE_ANCHORCONTROL_CONTENTSOURCECOMMUNITY => GD_URLPARAM_URECONTENTSOURCE_COMMUNITY,
                    self::MODULE_URE_ANCHORCONTROL_CONTENTSOURCEUSER => GD_URLPARAM_URECONTENTSOURCE_USER,
                );
                $source = $sources[$component[1]];

                $requestHelperService = RequestHelperServiceFacade::getInstance();
                $url = $requestHelperService->getCurrentURL();
                // Remove the 'source' param if it exists on the current url
                $url = GeneralUtils::removeQueryArgs([GD_URLPARAM_URECONTENTSOURCE], $url);

                return PoP_URE_ModuleManager_Utils::addSource($url, $source);
        }

        return parent::getHref($component, $props);
    }
    public function initModelProps(array $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::MODULE_URE_ANCHORCONTROL_CONTENTSOURCECOMMUNITY:
            case self::MODULE_URE_ANCHORCONTROL_CONTENTSOURCEUSER:
                $sources = array(
                    self::MODULE_URE_ANCHORCONTROL_CONTENTSOURCECOMMUNITY => GD_URLPARAM_URECONTENTSOURCE_COMMUNITY,
                    self::MODULE_URE_ANCHORCONTROL_CONTENTSOURCEUSER => GD_URLPARAM_URECONTENTSOURCE_USER,
                );
                $source = $sources[$component[1]];

                $this->appendProp($component, $props, 'class', 'btn btn-sm btn-default');
                if ($source == \PoP\Root\App::getState('source')) {
                    $this->appendProp($component, $props, 'class', 'active');
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}


