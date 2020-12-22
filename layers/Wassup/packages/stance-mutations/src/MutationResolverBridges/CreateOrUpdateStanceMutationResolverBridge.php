<?php

declare(strict_types=1);

namespace PoPSitesWassup\StanceMutations\MutationResolverBridges;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoPSitesWassup\StanceMutations\MutationResolvers\CreateOrUpdateStanceMutationResolver;

class CreateOrUpdateStanceMutationResolverBridge extends AbstractCreateUpdateStanceMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return CreateOrUpdateStanceMutationResolver::class;
    }

    protected function supportsTitle()
    {
        return false;
    }

    public function getFormData(): array
    {
        $form_data = parent::getFormData();

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $target = $moduleprocessor_manager->getProcessor([PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_STANCETARGET])->getValue([PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_STANCETARGET]);
        $form_data['stancetarget'] = $target;

        return $form_data;
    }

    protected function isUpdate(): bool
    {
        // If param "?pid" is provided then it's update, otherwise it's create
        return $this->getUpdateCustomPostID() != null;
    }

    protected function getEditorInput()
    {
        return [\PoP_Module_Processor_TextareaFormInputs::class, \PoP_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_TEXTAREAEDITOR];
    }

    protected function getCategoriesModule()
    {
        if ($this->showCategories()) {
            return [\UserStance_Module_Processor_ButtonGroupFormInputs::class, \UserStance_Module_Processor_ButtonGroupFormInputs::MODULE_FORMINPUT_BUTTONGROUP_STANCE];
        }

        return parent::getCategoriesModule();
    }

    protected function showCategories()
    {
        return true;
    }

    protected function moderate()
    {
        return false;
    }

    /**
     * Watch out! This functions is called from nowhere!
     * Lost during the migration!
     * @todo: Restore calling this function
     */
    protected function getSuccessTitle($referenced = null)
    {
        $feedback_title = \PoP_UserStance_PostNameUtils::getNameUc();
        if ($referenced) {
            $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
            return sprintf(
                TranslationAPIFacade::getInstance()->__('%1$s after reading “%2$s”', 'pop-userstance'),
                $feedback_title,
                $customPostTypeAPI->getTitle($referenced)
            );
        }

        return $feedback_title;
    }
}
