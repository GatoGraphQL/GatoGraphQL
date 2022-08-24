<?php

declare(strict_types=1);

namespace PoPSitesWassup\StanceMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP_Module_Processor_TextareaFormInputs;
use PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues;
use PoP_UserStance_PostNameUtils;
use PoPSitesWassup\StanceMutations\MutationResolvers\CreateOrUpdateStanceMutationResolver;
use UserStance_Module_Processor_ButtonGroupFormInputs;

class CreateOrUpdateStanceMutationResolverBridge extends AbstractCreateUpdateStanceMutationResolverBridge
{
    private ?CreateOrUpdateStanceMutationResolver $createOrUpdateStanceMutationResolver = null;

    final public function setCreateOrUpdateStanceMutationResolver(CreateOrUpdateStanceMutationResolver $createOrUpdateStanceMutationResolver): void
    {
        $this->createOrUpdateStanceMutationResolver = $createOrUpdateStanceMutationResolver;
    }
    final protected function getCreateOrUpdateStanceMutationResolver(): CreateOrUpdateStanceMutationResolver
    {
        /** @var CreateOrUpdateStanceMutationResolver */
        return $this->createOrUpdateStanceMutationResolver ??= $this->instanceManager->getInstance(CreateOrUpdateStanceMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getCreateOrUpdateStanceMutationResolver();
    }

    protected function supportsTitle(): bool
    {
        return false;
    }

    /**
     * @param array<string,mixed> $mutationData
     */
    public function addMutationDataForFieldDataAccessor(array &$mutationData): void
    {
        parent::addMutationDataForFieldDataAccessor($mutationData);

        $target = $this->getComponentProcessorManager()->getComponentProcessor([PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_STANCETARGET])->getValue([PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_STANCETARGET]);
        $mutationData['stancetarget'] = $target;
    }

    protected function isUpdate(): bool
    {
        // If param "?pid" is provided then it's update, otherwise it's create
        return $this->getUpdateCustomPostID() !== null;
    }

    /**
     * @return mixed[]
     */
    protected function getEditorInput(): array
    {
        return [PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_TEXTAREAEDITOR];
    }

    protected function getCategoriesComponent(): ?array
    {
        if ($this->showCategories()) {
            return [UserStance_Module_Processor_ButtonGroupFormInputs::class, UserStance_Module_Processor_ButtonGroupFormInputs::COMPONENT_FORMINPUT_BUTTONGROUP_STANCE];
        }

        return parent::getCategoriesComponent();
    }

    protected function showCategories(): bool
    {
        return true;
    }

    protected function moderate(): bool
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
        $feedback_title = PoP_UserStance_PostNameUtils::getNameUc();
        if ($referenced) {
            return sprintf(
                $this->__('%1$s after reading “%2$s”', 'pop-userstance'),
                $feedback_title,
                $this->getCustomPostTypeAPI()->getTitle($referenced)
            );
        }

        return $feedback_title;
    }
}
