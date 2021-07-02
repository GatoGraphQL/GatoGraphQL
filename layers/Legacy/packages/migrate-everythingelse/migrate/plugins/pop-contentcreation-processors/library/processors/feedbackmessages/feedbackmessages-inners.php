<?php
use PoP\ComponentModel\QueryInputOutputHandlers\ResponseConstants;

class PoP_ContentCreation_Module_Processor_FeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public const MODULE_FEEDBACKMESSAGEINNER_FLAG = 'feedbackmessageinner-flag';
    public const MODULE_FEEDBACKMESSAGEINNER_CREATECONTENT = 'feedbackmessageinner-createcontent';
    public const MODULE_FEEDBACKMESSAGEINNER_UPDATECONTENT = 'feedbackmessageinner-updatecontent';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_FLAG],
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_CREATECONTENT],
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_UPDATECONTENT],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        $layouts = array(
            self::MODULE_FEEDBACKMESSAGEINNER_FLAG => [PoP_ContentCreation_Module_Processor_FeedbackMessageAlertLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_FLAG],
            self::MODULE_FEEDBACKMESSAGEINNER_CREATECONTENT => [PoP_ContentCreation_Module_Processor_FeedbackMessageAlertLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_CREATECONTENT],
            self::MODULE_FEEDBACKMESSAGEINNER_UPDATECONTENT => [PoP_ContentCreation_Module_Processor_FeedbackMessageAlertLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_UPDATECONTENT],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }

    //-------------------------------------------------
    // Feedback
    //-------------------------------------------------

    public function getDataFeedback(array $module, array &$props, array $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids): array
    {
        $ret = parent::getDataFeedback($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

        switch ($module[1]) {
            case self::MODULE_FEEDBACKMESSAGEINNER_CREATECONTENT:
                // If $executed != null, then $checkpoint succeded, no need to ask for this condition before printing the messages
                if ($executed) {
                     // Check if there are errors or if it was successful, and add corresponding messages.
                    if ($executed[ResponseConstants::SUCCESS]) {
                        // If the post was not just created but actually updated (created first and then on that same page updated it)
                        // then change the success code
                        $pid = $dbobjectids[0];
                        if (isset($_REQUEST[\PoPSchema\Posts\Constants\InputNames::POST_ID]) && $pid == $_REQUEST[\PoPSchema\Posts\Constants\InputNames::POST_ID]) {
                            $ret['msgs'][0]['header']['code'] = 'update-success-header';
                        }
                    }
                }
                break;
        }

        return $ret;
    }
}



