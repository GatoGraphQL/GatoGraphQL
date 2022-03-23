<?php
use PoP\Application\ModuleProcessors\DataloadingConstants;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\Root\Feedback\FeedbackItemResolution;

abstract class PoP_Module_Processor_DataloadsBase extends PoP_Engine_Module_Processor_DataloadsBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_DATALOAD];
    }

    protected function getStatusSubmodule(array $module)
    {
        return [PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS];
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($this->getStatusSubmodule($module)) {
            $ret[] = $this->getStatusSubmodule($module);
        }

        if ($feedbackmessages = $this->getFeedbackmessageSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $feedbackmessages
            );
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        if ($submodules = $this->getInnerSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['inners'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $submodules
            );
        }

        if ($status = $this->getStatusSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['status'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($status);
        }

        if ($feedbackmessages = $this->getFeedbackmessageSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['feedbackmessages'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $feedbackmessages
            );

            $feedbackmessages_pos = $this->getFeedbackmessagesPosition($module);
            if ($feedbackmessages_pos == 'top') {
                $ret['feedbackmessages-top'] = true;
            } elseif ($feedbackmessages_pos == 'bottom') {
                $ret['feedbackmessages-bottom'] = true;
            }
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        // Allow Skeleton Screens: if setting $att 'use-skeletonscreen' then do not validate if the content is loaded
        // Then, the content will be loaded nevertheless, and this content will be used for the skeleton screen effect,
        // simply adding some extra styles together with style '.pop-block.pop-loadingcontent'
        if ($this->queriesExternalDomain($module, $props) && PoP_BaseCollectionProcessors_Utils::useSkeletonscreenForExternalDomain()) {
            // If proxy => Content not loaded => Make it use the Skeleton screen
            $this->setProp($module, $props, 'use-skeletonscreen', true);

            // Inform pop-engine to use mock data, needed for the Skeleton Screen effect
            $this->setProp($module, $props, 'use-mock-dbobject-data', true);
        }

        $this->setProp($module, $props, 'use-skeletonscreen', false);
        if ($this->getProp($module, $props, 'use-skeletonscreen')) {
            // Add extra class to the block
            $this->appendProp($module, $props, 'class', 'pop-skeletonscreen');
        }

        parent::initModelProps($module, $props);
    }

    protected function getFeedbackmessageSubmodules(array $module)
    {
        $ret = array();
        if ($feedbackmessage = $this->getFeedbackmessageModule($module)) {
            $ret[] = $feedbackmessage;
        }
        if ($checkpointmessage = $this->getCheckpointmessageModule($module)) {
            $ret[] = $checkpointmessage;
        }
        return $ret;
    }
    protected function getFeedbackmessageModule(array $module)
    {
        return null;
    }
    protected function getCheckpointmessageModule(array $module)
    {
        return null;
    }

    protected function getFeedbackmessagesPosition(array $module)
    {
        return 'top';
    }

    //-------------------------------------------------
    // Feedback
    //-------------------------------------------------

    public function getDataFeedback(array $module, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbobjectids): array
    {
        $ret = parent::getDataFeedback($module, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

        if ($this->getProp($module, $props, 'do-not-render-if-no-results') && !(($data_properties[DataloadingConstants::LAZYLOAD] ?? null) || ($data_properties[DataloadingConstants::EXTERNALLOAD] ?? null)) && !$dbobjectids) {
            $ret['do-not-render'] = true;
        }

        // Add class "pop-loadingcontent" if doing lazy-load
        if (($data_properties[DataloadingConstants::LAZYLOAD] ?? null) || ($data_properties[DataloadingConstants::EXTERNALLOAD] ?? null)) {
            $ret['class'] = POP_CLASS_LOADINGCONTENT;
        }

        return $ret;
    }
}
