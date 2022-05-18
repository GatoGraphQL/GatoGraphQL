<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_DomainFeedbackMessageLayouts extends PoP_Module_Processor_FormFeedbackMessageLayoutsBase
{
    public final const MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST = 'layout-feedbackmessage-itemlist';
    public final const MODULE_LAYOUT_FEEDBACKMESSAGE_EMPTY = 'layout-feedbackmessage-empty';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST],
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGE_EMPTY],
        );
    }

    public function getMessages(array $componentVariation, array &$props)
    {
        $ret = parent::getMessages($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST:
                $names = $this->getProp($componentVariation, $props, 'pluralname');
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

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST:
                $this->setProp($componentVariation, $props, 'pluralname', TranslationAPIFacade::getInstance()->__('results', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



