<?php

class PoP_Module_Processor_DomainFeedbackMessageInners extends PoP_Module_Processor_ActionExecutionFeedbackMessageInnersBase
{
    public const MODULE_FEEDBACKMESSAGEINNER_EMPTY = 'feedbackmessageinner-empty';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FEEDBACKMESSAGEINNER_EMPTY],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        $layouts = array(
            self::MODULE_FEEDBACKMESSAGEINNER_EMPTY => [PoP_Module_Processor_DomainFeedbackMessageAlertLayouts::class, PoP_Module_Processor_DomainFeedbackMessageAlertLayouts::MODULE_LAYOUT_FEEDBACKMESSAGEALERT_EMPTY],
        );

        if ($layout = $layouts[$module[1]]) {
            $ret[] = $layout;
        }

        return $ret;
    }
}



