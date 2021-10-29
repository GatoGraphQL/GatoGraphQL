<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\Users\Constants\InputNames;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\EditMembershipMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class EditMembershipMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    protected ?EditMembershipMutationResolver $editMembershipMutationResolver = null;
    
    public function setEditMembershipMutationResolver(EditMembershipMutationResolver $editMembershipMutationResolver): void
    {
        $this->editMembershipMutationResolver = $editMembershipMutationResolver;
    }
    protected function getEditMembershipMutationResolver(): EditMembershipMutationResolver
    {
        return $this->editMembershipMutationResolver ??= $this->getInstanceManager()->getInstance(EditMembershipMutationResolver::class);
    }

    //#[Required]
    final public function autowireEditMembershipMutationResolverBridge(
        EditMembershipMutationResolver $editMembershipMutationResolver,
    ): void {
        $this->editMembershipMutationResolver = $editMembershipMutationResolver;
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getEditMembershipMutationResolver();
    }

    public function getFormData(): array
    {
        $vars = ApplicationState::getVars();
        $community = $vars['global-userstate']['is-user-logged-in'] ? $vars['global-userstate']['current-user-id'] : '';
        $privileges = $this->getModuleProcessorManager()->getProcessor([\GD_URE_Module_Processor_ProfileMultiSelectFormInputs::class, \GD_URE_Module_Processor_ProfileMultiSelectFormInputs::MODULE_URE_FORMINPUT_MEMBERPRIVILEGES])->getValue([\GD_URE_Module_Processor_ProfileMultiSelectFormInputs::class, \GD_URE_Module_Processor_ProfileMultiSelectFormInputs::MODULE_URE_FORMINPUT_MEMBERPRIVILEGES]);
        $tags = $this->getModuleProcessorManager()->getProcessor([\GD_URE_Module_Processor_ProfileMultiSelectFormInputs::class, \GD_URE_Module_Processor_ProfileMultiSelectFormInputs::MODULE_URE_FORMINPUT_MEMBERTAGS])->getValue([\GD_URE_Module_Processor_ProfileMultiSelectFormInputs::class, \GD_URE_Module_Processor_ProfileMultiSelectFormInputs::MODULE_URE_FORMINPUT_MEMBERTAGS]);
        $form_data = array(
            'community' => $community,
            'user_id' => $_REQUEST[InputNames::USER_ID] ?? null,
            // 'nonce' => $_REQUEST[POP_INPUTNAME_NONCE],
            'status' => trim($this->getModuleProcessorManager()->getProcessor([\GD_URE_Module_Processor_SelectFormInputs::class, \GD_URE_Module_Processor_SelectFormInputs::MODULE_URE_FORMINPUT_MEMBERSTATUS])->getValue([\GD_URE_Module_Processor_SelectFormInputs::class, \GD_URE_Module_Processor_SelectFormInputs::MODULE_URE_FORMINPUT_MEMBERSTATUS])),
            'privileges' => $privileges ?? array(),
            'tags' => $tags ?? array(),
        );

        return $form_data;
    }
}
