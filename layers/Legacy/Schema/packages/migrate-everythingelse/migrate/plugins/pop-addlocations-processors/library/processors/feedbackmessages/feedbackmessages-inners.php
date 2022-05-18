<?php

class PoP_Module_Processor_CreateLocationFeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public final const MODULE_FEEDBACKMESSAGEINNER_CREATELOCATION = 'feedbackmessageinner-createlocation';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_CREATELOCATION],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        $layouts = array(
            self::MODULE_FEEDBACKMESSAGEINNER_CREATELOCATION => [PoP_Module_Processor_CreateLocationFeedbackMessageAlertLayouts::class, PoP_Module_Processor_CreateLocationFeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_CREATELOCATION],
        );

        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



