<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;
use PoP\Engine\ModuleProcessors\DBObjectIDFromURLParamModuleProcessorTrait;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostTypeResolver;
use PoP\ComponentModel\Facades\MutationResolution\MutationResolutionManagerFacade;

abstract class PoP_Module_Processor_AddEditContentDataloadsBase extends PoP_Module_Processor_DataloadsBase
{
    use DBObjectIDFromURLParamModuleProcessorTrait;

    protected function isCreate(array $module)
    {
        return null;
    }
    protected function isUpdate(array $module)
    {
        return null;
    }

    public function getDBObjectIDOrIDs(array $module, array &$props, &$data_properties)
    {
        if ($this->isUpdate($module)) {
            return $this->getDBObjectIDFromURLParam($module, $props, $data_properties);
        }
        // The parent obtains a list of IDs. Return it as a single ID
        $ids = parent::getDBObjectIDOrIDs($module, $props, $data_properties);
        if (is_array($ids) && count($ids) == 1) {
            return $ids[0];
        }
        return $ids;
    }

    protected function getDBObjectIDParamName(array $module, array &$props, &$data_properties)
    {
        if ($this->isUpdate($module)) {
            return POP_INPUTNAME_POSTID;
        }
        return null;
    }

    public function getTypeResolverClass(array $module): ?string
    {
        return CustomPostTypeResolver::class;
    }

    public function getQueryInputOutputHandlerClass(array $module): ?string
    {
        if ($this->isUpdate($module)) {
            return GD_DataLoad_QueryInputOutputHandler_EditPost::class;
        } elseif ($this->isCreate($module)) {
            return GD_DataLoad_QueryInputOutputHandler_AddPost::class;
        }

        return parent::getQueryInputOutputHandlerClass($module);
    }

    public function prepareDataPropertiesAfterActionexecution(array $module, array &$props, &$data_properties)
    {
        parent::prepareDataPropertiesAfterActionexecution($module, $props, $data_properties);

        if ($this->isCreate($module)) {
            $gd_dataload_actionexecution_manager = MutationResolutionManagerFacade::getInstance();
            if ($target_id = $gd_dataload_actionexecution_manager->getResult($this->getComponentMutationResolverBridgeClass($module))) {
                $data_properties[DataloadingConstants::QUERYARGS]['include'] = array($target_id);
            } else {
                $data_properties[DataloadingConstants::SKIPDATALOAD] = true;
            }
        }
    }

    protected function getCheckpointmessageModule(array $module)
    {
        if ($this->isCreate($module)) {
            return [GD_UserLogin_Module_Processor_UserCheckpointMessages::class, GD_UserLogin_Module_Processor_UserCheckpointMessages::MODULE_CHECKPOINTMESSAGE_LOGGEDIN];
        } elseif ($this->isUpdate($module)) {
            return [GD_UserLogin_Module_Processor_UserCheckpointMessages::class, GD_UserLogin_Module_Processor_UserCheckpointMessages::MODULE_CHECKPOINTMESSAGE_LOGGEDINCANEDIT];
        }

        return parent::getCheckpointmessageModule($module);
    }

    protected function getFeedbackmessageModule(array $module)
    {
        if ($this->isCreate($module)) {
            return [PoP_ContentCreation_Module_Processor_FeedbackMessages::class, PoP_ContentCreation_Module_Processor_FeedbackMessages::MODULE_FEEDBACKMESSAGE_CREATECONTENT];
        } elseif ($this->isUpdate($module)) {
            return [PoP_ContentCreation_Module_Processor_FeedbackMessages::class, PoP_ContentCreation_Module_Processor_FeedbackMessages::MODULE_FEEDBACKMESSAGE_UPDATECONTENT];
        }

        return parent::getFeedbackmessageModule($module);
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        if ($this->isCreate($module)) {
            $this->addJsmethod($ret, 'formCreatePostBlock');
        } elseif ($this->isUpdate($module)) {
            $this->addJsmethod($ret, 'destroyPageOnUserLoggedOut');
            $this->addJsmethod($ret, 'refetchBlockOnUserLoggedIn');
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props)
    {
        $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Submitting...', 'pop-application-processors'));

        if ($this->isCreate($module)) {
            $this->setProp([GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN], $props, 'action', TranslationAPIFacade::getInstance()->__('create content', 'poptheme-wassup'));
        } elseif ($this->isUpdate($module)) {
            $this->setProp([GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDINCANEDIT], $props, 'action', TranslationAPIFacade::getInstance()->__('edit this content', 'poptheme-wassup'));
        }

        parent::initModelProps($module, $props);
    }
}
