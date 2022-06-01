<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CommentsFeedbackMessageLayouts extends PoP_Module_Processor_FeedbackMessageLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGE_COMMENTS = 'layout-feedbackmessage-comments';
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGE_ADDCOMMENT = 'layout-feedbackmessage-addcomment';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_COMMENTS,
            self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ADDCOMMENT,
        );
    }

    public function getMessages(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getMessages($component, $props);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_COMMENTS:
                $names = TranslationAPIFacade::getInstance()->__('comments', 'pop-coreprocessors');
                $ret['noresults'] = sprintf(
                    TranslationAPIFacade::getInstance()->__('No %s yet.', 'pop-coreprocessors'),
                    $names
                );
                $ret['nomore'] = sprintf(
                    TranslationAPIFacade::getInstance()->__('That\'s it, no more %s found.', 'pop-coreprocessors'),
                    $names
                );
                break;

            case self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_ADDCOMMENT:
                $ret['error-header'] = TranslationAPIFacade::getInstance()->__('Oops, there were some problems:', 'pop-coreprocessors');
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Comment added successfully!', 'pop-coreprocessors');
                $ret['empty-content'] = TranslationAPIFacade::getInstance()->__('Comment is missing.', 'pop-coreprocessors');
                $ret['success'] = TranslationAPIFacade::getInstance()->__('Your comment was added.', 'pop-coreprocessors');
                break;
        }

        return $ret;
    }
}



