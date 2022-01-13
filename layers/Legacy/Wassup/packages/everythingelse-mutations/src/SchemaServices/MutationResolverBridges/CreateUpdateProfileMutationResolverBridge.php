<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\Root\App;
use Exception;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\CreateUpdateProfileMutationResolver;

class CreateUpdateProfileMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    private ?CreateUpdateProfileMutationResolver $createUpdateProfileMutationResolver = null;
    
    final public function setCreateUpdateProfileMutationResolver(CreateUpdateProfileMutationResolver $createUpdateProfileMutationResolver): void
    {
        $this->createUpdateProfileMutationResolver = $createUpdateProfileMutationResolver;
    }
    final protected function getCreateUpdateProfileMutationResolver(): CreateUpdateProfileMutationResolver
    {
        return $this->createUpdateProfileMutationResolver ??= $this->instanceManager->getInstance(CreateUpdateProfileMutationResolver::class);
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getCreateUpdateProfileMutationResolver();
    }

    public function getFormData(): array
    {
        $form_data = parent::getFormData();

        $inputs = $this->getFormInputs();
        $form_data = array_merge(
            $form_data,
            array(
                'short_description' => trim($this->getModuleProcessorManager()->getProcessor($inputs['short_description'])->getValue($inputs['short_description'])),
                'display_email' => $this->getModuleProcessorManager()->getProcessor($inputs['display_email'])->getValue($inputs['display_email']),
                'facebook' => trim($this->getModuleProcessorManager()->getProcessor($inputs['facebook'])->getValue($inputs['facebook'])),
                'twitter' => trim($this->getModuleProcessorManager()->getProcessor($inputs['twitter'])->getValue($inputs['twitter'])),
                'linkedin' => trim($this->getModuleProcessorManager()->getProcessor($inputs['linkedin'])->getValue($inputs['linkedin'])),
                'youtube' => trim($this->getModuleProcessorManager()->getProcessor($inputs['youtube'])->getValue($inputs['youtube'])),
                'instagram' => trim($this->getModuleProcessorManager()->getProcessor($inputs['instagram'])->getValue($inputs['instagram'])),
                // 'blog' => trim($this->getModuleProcessorManager()->getProcessor($inputs['blog'])->getValue($inputs['blog'])),
            )
        );

        // Allow to add extra inputs
        $form_data = App::getHookManager()->applyFilters('gd_createupdate_profile:form_data', $form_data);

        return $form_data;
    }

    private function getFormInputs()
    {
        $inputs = App::getHookManager()->applyFilters(
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
