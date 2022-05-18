<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_EventsCreation_Module_Processor_MySectionDataloadsBase extends PoP_Module_Processor_MySectionDataloadsBase
{
    protected function getImmutableDataloadQueryArgs(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($componentVariation, $props);

        // Any post status
        $ret['status'] = 'all';
        // unset($ret['custom-post-status']);

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('events', 'poptheme-wassup'));
        $this->setProp([GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN], $props, 'action', TranslationAPIFacade::getInstance()->__('access your events', 'poptheme-wassup'));

        parent::initModelProps($componentVariation, $props);
    }
}
