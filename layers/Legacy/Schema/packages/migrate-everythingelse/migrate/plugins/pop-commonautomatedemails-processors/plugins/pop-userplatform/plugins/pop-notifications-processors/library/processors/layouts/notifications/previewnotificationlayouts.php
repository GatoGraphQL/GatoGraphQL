<?php

class PoP_Module_Processor_AutomatedEmailsPreviewNotificationLayouts extends PoP_Module_Processor_AutomatedEmailsPreviewNotificationLayoutsBase
{
    public final const MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWNOTIFICATION_DETAILS = 'layout-automatedemails-previewnotification-details';
    public final const MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWNOTIFICATION_LIST = 'layout-automatedemails-previewnotification-list';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWNOTIFICATION_DETAILS],
            [self::class, self::MODULE_LAYOUT_AUTOMATEDEMAILS_PREVIEWNOTIFICATION_LIST],
        );
    }
}


