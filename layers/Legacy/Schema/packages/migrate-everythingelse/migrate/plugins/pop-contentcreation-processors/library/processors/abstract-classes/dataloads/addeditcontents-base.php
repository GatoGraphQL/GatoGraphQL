<?php
use PoP\ComponentModel\App;
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoP\Engine\ComponentProcessors\ObjectIDFromURLParamComponentProcessorTrait;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolver;

abstract class PoP_Module_Processor_AddEditContentDataloadsBase extends PoP_Module_Processor_DataloadsBase
{
    use ObjectIDFromURLParamComponentProcessorTrait;

    protected function isCreate(array $componentVariation)
    {
        return null;
    }
    protected function isUpdate(array $componentVariation)
    {
        return null;
    }

    public function getObjectIDOrIDs(array $componentVariation, array &$props, &$data_properties): string | int | array
    {
        if ($this->isUpdate($componentVariation)) {
            return $this->getObjectIDFromURLParam($componentVariation, $props, $data_properties);
        }
        // The parent obtains a list of IDs. Return it as a single ID
        $ids = parent::getObjectIDOrIDs($componentVariation, $props, $data_properties);
        if (is_array($ids) && count($ids) == 1) {
            return $ids[0];
        }
        return $ids;
    }

    protected function getObjectIDParamName(array $componentVariation, array &$props, array &$data_properties): ?string
    {
        if ($this->isUpdate($componentVariation)) {
            return \PoPCMSSchema\Posts\Constants\InputNames::POST_ID;
        }
        return null;
    }

    public function getRelationalTypeResolver(array $componentVariation): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        return $this->instanceManager->getInstance(CustomPostObjectTypeResolver::class);
    }

    public function getQueryInputOutputHandler(array $componentVariation): ?QueryInputOutputHandlerInterface
    {
        if ($this->isUpdate($componentVariation)) {
            return $this->instanceManager->getInstance(GD_DataLoad_QueryInputOutputHandler_EditPost::class);
        } elseif ($this->isCreate($componentVariation)) {
            return $this->instanceManager->getInstance(GD_DataLoad_QueryInputOutputHandler_AddPost::class);
        }

        return parent::getQueryInputOutputHandler($componentVariation);
    }

    public function prepareDataPropertiesAfterMutationExecution(array $componentVariation, array &$props, array &$data_properties): void
    {
        parent::prepareDataPropertiesAfterMutationExecution($componentVariation, $props, $data_properties);

        if ($this->isCreate($componentVariation)) {
            if ($target_id = App::getMutationResolutionStore()->getResult($this->getComponentMutationResolverBridge($componentVariation))) {
                $data_properties[DataloadingConstants::QUERYARGS]['include'] = array($target_id);
            } else {
                $data_properties[DataloadingConstants::SKIPDATALOAD] = true;
            }
        }
    }

    protected function getCheckpointmessageModule(array $componentVariation)
    {
        if ($this->isCreate($componentVariation)) {
            return [GD_UserLogin_Module_Processor_UserCheckpointMessages::class, GD_UserLogin_Module_Processor_UserCheckpointMessages::MODULE_CHECKPOINTMESSAGE_LOGGEDIN];
        } elseif ($this->isUpdate($componentVariation)) {
            return [GD_UserLogin_Module_Processor_UserCheckpointMessages::class, GD_UserLogin_Module_Processor_UserCheckpointMessages::MODULE_CHECKPOINTMESSAGE_LOGGEDINCANEDIT];
        }

        return parent::getCheckpointmessageModule($componentVariation);
    }

    protected function getFeedbackmessageModule(array $componentVariation)
    {
        if ($this->isCreate($componentVariation)) {
            return [PoP_ContentCreation_Module_Processor_FeedbackMessages::class, PoP_ContentCreation_Module_Processor_FeedbackMessages::MODULE_FEEDBACKMESSAGE_CREATECONTENT];
        } elseif ($this->isUpdate($componentVariation)) {
            return [PoP_ContentCreation_Module_Processor_FeedbackMessages::class, PoP_ContentCreation_Module_Processor_FeedbackMessages::MODULE_FEEDBACKMESSAGE_UPDATECONTENT];
        }

        return parent::getFeedbackmessageModule($componentVariation);
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        if ($this->isCreate($componentVariation)) {
            $this->addJsmethod($ret, 'formCreatePostBlock');
        } elseif ($this->isUpdate($componentVariation)) {
            $this->addJsmethod($ret, 'destroyPageOnUserLoggedOut');
            $this->addJsmethod($ret, 'refetchBlockOnUserLoggedIn');
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->setProp([[PoP_Module_Processor_Status::class, PoP_Module_Processor_Status::MODULE_STATUS]], $props, 'loading-msg', TranslationAPIFacade::getInstance()->__('Submitting...', 'pop-application-processors'));

        if ($this->isCreate($componentVariation)) {
            $this->setProp([GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN], $props, 'action', TranslationAPIFacade::getInstance()->__('create content', 'poptheme-wassup'));
        } elseif ($this->isUpdate($componentVariation)) {
            $this->setProp([GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDINCANEDIT], $props, 'action', TranslationAPIFacade::getInstance()->__('edit this content', 'poptheme-wassup'));
        }

        parent::initModelProps($componentVariation, $props);
    }
}
