<?php

class PoP_SocialNetwork_Module_Processor_FeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public final const MODULE_FEEDBACKMESSAGEINNER_CONTACTUSER = 'feedbackmessageinner-contactuser';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_CONTACTUSER],
        );
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        $layouts = array(
            self::MODULE_FEEDBACKMESSAGEINNER_CONTACTUSER => [PoP_SocialNetwork_Module_Processor_FeedbackMessageAlertLayouts::class, PoP_SocialNetwork_Module_Processor_FeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_CONTACTUSER],
        );

        if ($layout = $layouts[$componentVariation[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



