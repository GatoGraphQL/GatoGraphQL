<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentCreation_Module_Processor_FeedbackMessageLayouts extends PoP_Module_Processor_FormFeedbackMessageLayoutsBase
{
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGE_FLAG = 'layout-feedbackmessage-flag';
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGE_CREATECONTENT = 'layout-feedbackmessage-createcontent';
    public final const COMPONENT_LAYOUT_FEEDBACKMESSAGE_UPDATECONTENT = 'layout-feedbackmessage-updatecontent';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_FLAG],
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_CREATECONTENT],
            [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_UPDATECONTENT],
        );
    }

    public function getMessages(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getMessages($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_FLAG:
                $ret['success-header'] = TranslationAPIFacade::getInstance()->__('Flag received successfully.', 'pop-genericforms');
                $ret['success'] = TranslationAPIFacade::getInstance()->__("Noted, we will evaluate your feedback and take appropriate action.", 'pop-genericforms');
                $ret['empty-whyflag'] = TranslationAPIFacade::getInstance()->__("\"Description on why this post is inappropriate\" is missing.", 'pop-genericforms');
                $ret['empty-name'] = TranslationAPIFacade::getInstance()->__('Name is missing.', 'pop-genericforms');
                $ret['empty-email'] = TranslationAPIFacade::getInstance()->__('Email is missing or format is incorrect.', 'pop-genericforms');
                break;

            case self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_CREATECONTENT:
            case self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_UPDATECONTENT:
                $name = $this->getProp($component, $props, 'objectname');
                if ($component == [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_CREATECONTENT]) {
                    $ret['success-header'] = sprintf(
                        TranslationAPIFacade::getInstance()->__('Your %s was created successfully!', 'pop-userstance-processors'),
                        $name
                    );
                    $ret['update-success-header'] = sprintf(
                        TranslationAPIFacade::getInstance()->__('Your %s was updated successfully!', 'pop-userstance-processors'),
                        $name
                    );
                } elseif ($component == [self::class, self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_UPDATECONTENT]) {
                    $ret['success-header'] = sprintf(
                        TranslationAPIFacade::getInstance()->__('Your %s was updated successfully.', 'pop-userstance-processors'),
                        $name
                    );
                }
                break;
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_CREATECONTENT:
            case self::COMPONENT_LAYOUT_FEEDBACKMESSAGE_UPDATECONTENT:
                $this->setProp($component, $props, 'objectname', TranslationAPIFacade::getInstance()->__('content', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($component, $props);
    }
}



