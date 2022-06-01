<?php
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\QueryInputOutputHandlers\ResponseConstants;
use PoP\Root\App;

class PoP_ContentCreation_Module_Processor_FeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public final const COMPONENT_FEEDBACKMESSAGEINNER_FLAG = 'feedbackmessageinner-flag';
    public final const COMPONENT_FEEDBACKMESSAGEINNER_CREATECONTENT = 'feedbackmessageinner-createcontent';
    public final const COMPONENT_FEEDBACKMESSAGEINNER_UPDATECONTENT = 'feedbackmessageinner-updatecontent';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FEEDBACKMESSAGEINNER_FLAG,
            self::COMPONENT_FEEDBACKMESSAGEINNER_CREATECONTENT,
            self::COMPONENT_FEEDBACKMESSAGEINNER_UPDATECONTENT,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        $layouts = array(
            self::COMPONENT_FEEDBACKMESSAGEINNER_FLAG => [PoP_ContentCreation_Module_Processor_FeedbackMessageAlertLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_FLAG],
            self::COMPONENT_FEEDBACKMESSAGEINNER_CREATECONTENT => [PoP_ContentCreation_Module_Processor_FeedbackMessageAlertLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_CREATECONTENT],
            self::COMPONENT_FEEDBACKMESSAGEINNER_UPDATECONTENT => [PoP_ContentCreation_Module_Processor_FeedbackMessageAlertLayouts::class, PoP_ContentCreation_Module_Processor_FeedbackMessageAlertLayouts::COMPONENT_LAYOUT_FEEDBACKMESSAGEALERT_UPDATECONTENT],
        );

        if ($layout = $layouts[$component->name] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }

    //-------------------------------------------------
    // Feedback
    //-------------------------------------------------

    public function getDataFeedback(\PoP\ComponentModel\Component\Component $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array
    {
        $ret = parent::getDataFeedback($component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

        switch ($component->name) {
            case self::COMPONENT_FEEDBACKMESSAGEINNER_CREATECONTENT:
                // If $executed != null, then $checkpoint succeded, no need to ask for this condition before printing the messages
                if ($executed) {
                     // Check if there are errors or if it was successful, and add corresponding messages.
                    if ($executed[ResponseConstants::SUCCESS]) {
                        // If the post was not just created but actually updated (created first and then on that same page updated it)
                        // then change the success code
                        $pid = $dbobjectids[0];
                        if ($pid === App::query(\PoPCMSSchema\Posts\Constants\InputNames::POST_ID)) {
                            $ret['msgs'][0]['header']['code'] = 'update-success-header';
                        }
                    }
                }
                break;
        }

        return $ret;
    }
}



