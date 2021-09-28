<?php

declare(strict_types=1);

namespace PoPSitesWassup\FlagMutations\MutationResolverBridges;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\FlagMutations\MutationResolvers\FlagCustomPostMutationResolver;
use PoPSitesWassup\FormMutations\MutationResolverBridges\AbstractFormComponentMutationResolverBridge;

class FlagCustomPostMutationResolverBridge extends AbstractFormComponentMutationResolverBridge
{
    protected FlagCustomPostMutationResolver $flagCustomPostMutationResolver;

    #[Required]
    public function autowireFlagCustomPostMutationResolverBridge(
        FlagCustomPostMutationResolver $flagCustomPostMutationResolver,
    ) {
        $this->flagCustomPostMutationResolver = $flagCustomPostMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->flagCustomPostMutationResolver;
    }

    public function getFormData(): array
    {
        $form_data = array(
            'name' => $this->moduleProcessorManager->getProcessor([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NAME])->getValue([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NAME]),
            'email' => $this->moduleProcessorManager->getProcessor([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_EMAIL])->getValue([\PoP_Forms_Module_Processor_TextFormInputs::class, \PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_EMAIL]),
            'whyflag' => $this->moduleProcessorManager->getProcessor([\PoP_ContentCreation_Module_Processor_TextareaFormInputs::class, \PoP_ContentCreation_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_WHYFLAG])->getValue([\PoP_ContentCreation_Module_Processor_TextareaFormInputs::class, \PoP_ContentCreation_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_WHYFLAG]),
            'target-id' => $this->moduleProcessorManager->getProcessor([\PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, \PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_POST])->getValue([\PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, \PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_POST]),
        );

        return $form_data;
    }
}
