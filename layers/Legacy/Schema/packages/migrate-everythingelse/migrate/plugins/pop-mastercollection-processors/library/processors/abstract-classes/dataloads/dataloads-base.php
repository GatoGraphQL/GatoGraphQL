<?php
use PoP\Application\ComponentProcessors\DataloadingConstants;
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Feedback\FeedbackItemResolution;

abstract class PoP_Module_Processor_DataloadsBase extends PoP_Engine_Module_Processor_DataloadsBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_DATALOAD];
    }

    protected function getStatusSubmodule(array $componentVariation)
    {
        return [PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS];
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($this->getStatusSubmodule($componentVariation)) {
            $ret[] = $this->getStatusSubmodule($componentVariation);
        }

        if ($feedbackmessages = $this->getFeedbackmessageSubmodules($componentVariation)) {
            $ret = array_merge(
                $ret,
                $feedbackmessages
            );
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($subComponentVariations = $this->getInnerSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['inners'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $subComponentVariations
            );
        }

        if ($status = $this->getStatusSubmodule($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['status'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($status);
        }

        if ($feedbackmessages = $this->getFeedbackmessageSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['feedbackmessages'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $feedbackmessages
            );

            $feedbackmessages_pos = $this->getFeedbackmessagesPosition($componentVariation);
            if ($feedbackmessages_pos == 'top') {
                $ret['feedbackmessages-top'] = true;
            } elseif ($feedbackmessages_pos == 'bottom') {
                $ret['feedbackmessages-bottom'] = true;
            }
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        // Allow Skeleton Screens: if setting $att 'use-skeletonscreen' then do not validate if the content is loaded
        // Then, the content will be loaded nevertheless, and this content will be used for the skeleton screen effect,
        // simply adding some extra styles together with style '.pop-block.pop-loadingcontent'
        if ($this->queriesExternalDomain($componentVariation, $props) && PoP_BaseCollectionProcessors_Utils::useSkeletonscreenForExternalDomain()) {
            // If proxy => Content not loaded => Make it use the Skeleton screen
            $this->setProp($componentVariation, $props, 'use-skeletonscreen', true);

            // Inform pop-engine to use mock data, needed for the Skeleton Screen effect
            $this->setProp($componentVariation, $props, 'use-mock-dbobject-data', true);
        }

        $this->setProp($componentVariation, $props, 'use-skeletonscreen', false);
        if ($this->getProp($componentVariation, $props, 'use-skeletonscreen')) {
            // Add extra class to the block
            $this->appendProp($componentVariation, $props, 'class', 'pop-skeletonscreen');
        }

        parent::initModelProps($componentVariation, $props);
    }

    protected function getFeedbackmessageSubmodules(array $componentVariation)
    {
        $ret = array();
        if ($feedbackmessage = $this->getFeedbackmessageModule($componentVariation)) {
            $ret[] = $feedbackmessage;
        }
        if ($checkpointmessage = $this->getCheckpointmessageModule($componentVariation)) {
            $ret[] = $checkpointmessage;
        }
        return $ret;
    }
    protected function getFeedbackmessageModule(array $componentVariation)
    {
        return null;
    }
    protected function getCheckpointmessageModule(array $componentVariation)
    {
        return null;
    }

    protected function getFeedbackmessagesPosition(array $componentVariation)
    {
        return 'top';
    }

    //-------------------------------------------------
    // Feedback
    //-------------------------------------------------

    public function getDataFeedback(array $componentVariation, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array
    {
        $ret = parent::getDataFeedback($componentVariation, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

        if ($this->getProp($componentVariation, $props, 'do-not-render-if-no-results') && !(($data_properties[DataloadingConstants::LAZYLOAD] ?? null) || ($data_properties[DataloadingConstants::EXTERNALLOAD] ?? null)) && !$dbobjectids) {
            $ret['do-not-render'] = true;
        }

        // Add class "pop-loadingcontent" if doing lazy-load
        if (($data_properties[DataloadingConstants::LAZYLOAD] ?? null) || ($data_properties[DataloadingConstants::EXTERNALLOAD] ?? null)) {
            $ret['class'] = POP_CLASS_LOADINGCONTENT;
        }

        return $ret;
    }
}
