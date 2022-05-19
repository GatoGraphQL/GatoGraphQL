<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Newsletter_Module_Processor_FeedbackMessageLayouts extends PoP_Module_Processor_FormFeedbackMessageLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGE_NEWSLETTER = 'layout-feedbackmessage-newsletter';
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGE_NEWSLETTERUNSUBSCRIPTION = 'layout-feedbackmessage-newsletterunsubscription';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_NEWSLETTER],
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_NEWSLETTERUNSUBSCRIPTION],
        );
    }

    public function getMessages(array $component, array &$props)
    {
        $ret = parent::getMessages($component, $props);
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_NEWSLETTER:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Subscription Successful!', 'pop-genericforms');
                $ret['success'] = sprintf(
                    TranslationAPIFacade::getInstance()->__("To make sure you get the newsletter, please add <strong>%s</strong> in your contact list. Thanks!", 'pop-genericforms'),
                    PoP_Newsletter_EmailUtils::getNewsletterEmail()
                );
                $ret['empty-name'] = TranslationAPIFacade::getInstance()->__('Name is missing.', 'pop-genericforms');
                $ret['empty-email'] = TranslationAPIFacade::getInstance()->__('Email is missing or format is incorrect.', 'pop-genericforms');
                break;

            case self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_NEWSLETTERUNSUBSCRIPTION:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Unsubscription successful', 'pop-genericforms');
                $ret['success'] = sprintf(
                    '%s<br/>%s',
                    TranslationAPIFacade::getInstance()->__('It is a pity to see you go. Hopefully you will keep visiting our website.', 'pop-genericforms'),
                    sprintf(
                        TranslationAPIFacade::getInstance()->__('And if anything, you can always <a href="%s">contact us</a>.', 'pop-genericforms'),
                        RouteUtils::getRouteURL(POP_CONTACTUS_ROUTE_CONTACTUS)
                    )
                );
                break;
        }

        return $ret;
    }
}



