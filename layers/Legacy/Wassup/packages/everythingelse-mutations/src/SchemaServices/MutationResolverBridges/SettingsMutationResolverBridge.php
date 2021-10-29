<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryInputOutputHandlers\ResponseConstants;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\SettingsMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class SettingsMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    protected ?SettingsMutationResolver $settingsMutationResolver = null;
    
    public function setSettingsMutationResolver(SettingsMutationResolver $settingsMutationResolver): void
    {
        $this->settingsMutationResolver = $settingsMutationResolver;
    }
    protected function getSettingsMutationResolver(): SettingsMutationResolver
    {
        return $this->settingsMutationResolver ??= $this->getInstanceManager()->getInstance(SettingsMutationResolver::class);
    }

    //#[Required]
    final public function autowireSettingsMutationResolverBridge(
        SettingsMutationResolver $settingsMutationResolver,
    ): void {
        $this->settingsMutationResolver = $settingsMutationResolver;
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getSettingsMutationResolver();
    }

    public function getFormData(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function executeMutation(array &$data_properties): ?array
    {
        $executed = parent::executeMutation($data_properties);
        if ($executed !== null && $executed[ResponseConstants::SUCCESS]) {
            // Add the result from the MutationResolver as hard redirect
            $redirect_to = $this->getMutationResolutionManager()->getResult($this);
            $executed[GD_DATALOAD_QUERYHANDLERRESPONSE_HARDREDIRECT] = $redirect_to;
        }
        return $executed;
    }
}
