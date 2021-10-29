<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SystemMutations\MutationResolvers\SaveDefinitionFileMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class SaveDefinitionFileMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    private ?SaveDefinitionFileMutationResolver $saveDefinitionFileMutationResolver = null;

    public function setSaveDefinitionFileMutationResolver(SaveDefinitionFileMutationResolver $saveDefinitionFileMutationResolver): void
    {
        $this->saveDefinitionFileMutationResolver = $saveDefinitionFileMutationResolver;
    }
    protected function getSaveDefinitionFileMutationResolver(): SaveDefinitionFileMutationResolver
    {
        return $this->saveDefinitionFileMutationResolver ??= $this->instanceManager->getInstance(SaveDefinitionFileMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getSaveDefinitionFileMutationResolver();
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        return $this->translationAPI->__('System action "save definition file" executed successfully.', 'pop-system');
    }
}
