<?php

class PoP_Module_Processor_AutomatedEmailsPreviewNotificationLayouts extends PoP_Module_Processor_AutomatedEmailsPreviewNotificationLayoutsBase
{
    public final const COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWNOTIFICATION_DETAILS = 'layout-automatedemails-previewnotification-details';
    public final const COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWNOTIFICATION_LIST = 'layout-automatedemails-previewnotification-list';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWNOTIFICATION_DETAILS,
            self::COMPONENT_LAYOUT_AUTOMATEDEMAILS_PREVIEWNOTIFICATION_LIST,
        );
    }
}


