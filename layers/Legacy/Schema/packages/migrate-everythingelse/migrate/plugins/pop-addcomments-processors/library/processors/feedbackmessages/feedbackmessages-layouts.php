<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CommentsFeedbackMessageLayouts extends PoP_Module_Processor_FeedbackMessageLayoutsBase
{
    public final const MODULE_LAYOUT_FEEDBACKMESSAGE_COMMENTS = 'layout-feedbackmessage-comments';
    public final const MODULE_LAYOUT_FEEDBACKMESSAGE_ADDCOMMENT = 'layout-feedbackmessage-addcomment';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGE_COMMENTS],
            [self::class, self::MODULE_LAYOUT_FEEDBACKMESSAGE_ADDCOMMENT],
        );
    }

    public function getMessages(array $module, array &$props)
    {
        $ret = parent::getMessages($module, $props);

        switch ($module[1]) {
            case self::MODULE_LAYOUT_FEEDBACKMESSAGE_COMMENTS:
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

            case self::MODULE_LAYOUT_FEEDBACKMESSAGE_ADDCOMMENT:
                $ret['error-header'] = TranslationAPIFacade::getInstance()->__('Oops, there were some problems:', 'pop-coreprocessors');
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Comment added successfully!', 'pop-coreprocessors');
                $ret['empty-content'] = TranslationAPIFacade::getInstance()->__('Comment is missing.', 'pop-coreprocessors');
                $ret['success'] = TranslationAPIFacade::getInstance()->__('Your comment was added.', 'pop-coreprocessors');
                break;
        }

        return $ret;
    }
}



