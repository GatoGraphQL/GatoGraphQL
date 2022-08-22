<?php

class GD_URE_Module_Processor_ProfileFeedbackMessages extends PoP_Module_Processor_FeedbackMessagesBase
{
    public final const COMPONENT_FEEDBACKMESSAGE_UPDATEMYCOMMUNITIES = 'feedbackmessage-updatemycommunities';
    public final const COMPONENT_FEEDBACKMESSAGE_INVITENEWMEMBERS = 'feedbackmessage-invitemembers';
    public final const COMPONENT_FEEDBACKMESSAGE_EDITMEMBERSHIP = 'feedbackmessage-editmembership';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FEEDBACKMESSAGE_UPDATEMYCOMMUNITIES,
            self::COMPONENT_FEEDBACKMESSAGE_INVITENEWMEMBERS,
            self::COMPONENT_FEEDBACKMESSAGE_EDITMEMBERSHIP,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_FEEDBACKMESSAGE_UPDATEMYCOMMUNITIES => [GD_URE_Module_Processor_ProfileFeedbackMessageInners::class, GD_URE_Module_Processor_ProfileFeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_UPDATEMYCOMMUNITIES],
            self::COMPONENT_FEEDBACKMESSAGE_INVITENEWMEMBERS => [GD_URE_Module_Processor_ProfileFeedbackMessageInners::class, GD_URE_Module_Processor_ProfileFeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_INVITENEWMEMBERS],
            self::COMPONENT_FEEDBACKMESSAGE_EDITMEMBERSHIP => [GD_URE_Module_Processor_ProfileFeedbackMessageInners::class, GD_URE_Module_Processor_ProfileFeedbackMessageInners::COMPONENT_FEEDBACKMESSAGEINNER_EDITMEMBERSHIP],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }
}



