<?php
use PoP\Application\ComponentProcessors\DataloadingConstants;
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Feedback\FeedbackItemResolution;

abstract class PoP_Module_Processor_DataloadsBase extends PoP_Engine_Module_Processor_DataloadsBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_DATALOAD];
    }

    protected function getStatusSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::COMPONENT_STATUS];
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($this->getStatusSubcomponent($component)) {
            $ret[] = $this->getStatusSubcomponent($component);
        }

        if ($feedbackmessages = $this->getFeedbackmessageSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $feedbackmessages
            );
        }

        return $ret;
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($subcomponents = $this->getInnerSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['inners'] = array_map(
                \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName(...),
                $subcomponents
            );
        }

        if ($status = $this->getStatusSubcomponent($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['status'] = \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($status);
        }

        if ($feedbackmessages = $this->getFeedbackmessageSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['feedbackmessages'] = array_map(
                \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName(...),
                $feedbackmessages
            );

            $feedbackmessages_pos = $this->getFeedbackmessagesPosition($component);
            if ($feedbackmessages_pos == 'top') {
                $ret['feedbackmessages-top'] = true;
            } elseif ($feedbackmessages_pos == 'bottom') {
                $ret['feedbackmessages-bottom'] = true;
            }
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        // Allow Skeleton Screens: if setting $att 'use-skeletonscreen' then do not validate if the content is loaded
        // Then, the content will be loaded nevertheless, and this content will be used for the skeleton screen effect,
        // simply adding some extra styles together with style '.pop-block.pop-loadingcontent'
        if ($this->queriesExternalDomain($component, $props) && PoP_BaseCollectionProcessors_Utils::useSkeletonscreenForExternalDomain()) {
            // If proxy => Content not loaded => Make it use the Skeleton screen
            $this->setProp($component, $props, 'use-skeletonscreen', true);

            // Inform pop-engine to use mock data, needed for the Skeleton Screen effect
            $this->setProp($component, $props, 'use-mock-dbobject-data', true);
        }

        $this->setProp($component, $props, 'use-skeletonscreen', false);
        if ($this->getProp($component, $props, 'use-skeletonscreen')) {
            // Add extra class to the block
            $this->appendProp($component, $props, 'class', 'pop-skeletonscreen');
        }

        parent::initModelProps($component, $props);
    }

    protected function getFeedbackmessageSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = array();
        if ($feedbackmessage = $this->getFeedbackMessageComponent($component)) {
            $ret[] = $feedbackmessage;
        }
        if ($checkpointmessage = $this->getCheckpointMessageComponent($component)) {
            $ret[] = $checkpointmessage;
        }
        return $ret;
    }
    protected function getFeedbackMessageComponent(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }
    protected function getCheckpointMessageComponent(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }

    protected function getFeedbackmessagesPosition(\PoP\ComponentModel\Component\Component $component)
    {
        return 'top';
    }

    //-------------------------------------------------
    // Feedback
    //-------------------------------------------------

    public function getDataFeedback(\PoP\ComponentModel\Component\Component $component, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $objectIDs): array
    {
        $ret = parent::getDataFeedback($component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs);

        if ($this->getProp($component, $props, 'do-not-render-if-no-results') && !(($data_properties[DataloadingConstants::LAZYLOAD] ?? null) || ($data_properties[DataloadingConstants::EXTERNALLOAD] ?? null)) && !$objectIDs) {
            $ret['do-not-render'] = true;
        }

        // Add class "pop-loadingcontent" if doing lazy-load
        if (($data_properties[DataloadingConstants::LAZYLOAD] ?? null) || ($data_properties[DataloadingConstants::EXTERNALLOAD] ?? null)) {
            $ret['class'] = POP_CLASS_LOADINGCONTENT;
        }

        return $ret;
    }
}
