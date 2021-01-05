<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\MutationResolverBridges;

use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoPSitesWassup\EverythingElseMutations\MutationResolvers\CreateUpdateProfileMutationResolver;

class CreateUpdateProfileMutationResolverBridge extends AbstractCreateUpdateUserMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return CreateUpdateProfileMutationResolver::class;
    }

    public function getFormData(): array
    {
        $form_data = parent::getFormData();

        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $inputs = $this->getFormInputs();
        $form_data = array_merge(
            $form_data,
            array(
                'short_description' => trim($moduleprocessor_manager->getProcessor($inputs['short_description'])->getValue($inputs['short_description'])),
                'display_email' => $moduleprocessor_manager->getProcessor($inputs['display_email'])->getValue($inputs['display_email']),
                'facebook' => trim($moduleprocessor_manager->getProcessor($inputs['facebook'])->getValue($inputs['facebook'])),
                'twitter' => trim($moduleprocessor_manager->getProcessor($inputs['twitter'])->getValue($inputs['twitter'])),
                'linkedin' => trim($moduleprocessor_manager->getProcessor($inputs['linkedin'])->getValue($inputs['linkedin'])),
                'youtube' => trim($moduleprocessor_manager->getProcessor($inputs['youtube'])->getValue($inputs['youtube'])),
                'instagram' => trim($moduleprocessor_manager->getProcessor($inputs['instagram'])->getValue($inputs['instagram'])),
                // 'blog' => trim($moduleprocessor_manager->getProcessor($inputs['blog'])->getValue($inputs['blog'])),
            )
        );

        // Allow to add extra inputs
        $form_data = HooksAPIFacade::getInstance()->applyFilters('gd_createupdate_profile:form_data', $form_data);

        return $form_data;
    }

    private function getFormInputs()
    {
        $inputs = HooksAPIFacade::getInstance()->applyFilters(
            'GD_CreateUpdate_Profile:form-inputs',
            array(
                'short_description' => null,
                'display_email' => null,
                'facebook' => null,
                'twitter' => null,
                'linkedin' => null,
                'youtube' => null,
                'instagram' => null,
                // 'blog' => null,
            )
        );

        // If any input is null, throw an exception
        $null_inputs = array_filter($inputs, 'is_null');
        if ($null_inputs) {
            throw new Exception(
                sprintf(
                    'No form inputs defined for: %s',
                    '"' . implode('", "', array_keys($null_inputs)) . '"'
                )
            );
        }

        return $inputs;
    }
}
