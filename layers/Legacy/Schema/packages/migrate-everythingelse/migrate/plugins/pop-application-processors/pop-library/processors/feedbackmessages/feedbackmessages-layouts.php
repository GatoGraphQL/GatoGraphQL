<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_DomainFeedbackMessageLayouts extends PoP_Module_Processor_FormFeedbackMessageLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST = 'layout-feedbackmessage-itemlist';
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGE_EMPTY = 'layout-feedbackmessage-empty';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST],
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_EMPTY],
        );
    }

    public function getMessages(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getMessages($component, $props);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST:
                $names = $this->getProp($component, $props, 'pluralname');
                $ret['noresults'] = sprintf(
                    TranslationAPIFacade::getInstance()->__('No %s found.', 'poptheme-wassup'),
                    $names
                );
                $ret['nomore'] = sprintf(
                    TranslationAPIFacade::getInstance()->__('No more %s found.', 'poptheme-wassup'),
                    $names
                );
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ITEMLIST:
                $this->setProp($component, $props, 'pluralname', TranslationAPIFacade::getInstance()->__('results', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}



