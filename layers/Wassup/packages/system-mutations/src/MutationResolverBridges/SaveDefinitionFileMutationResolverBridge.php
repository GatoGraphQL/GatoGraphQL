<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SystemMutations\MutationResolvers\SaveDefinitionFileMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class SaveDefinitionFileMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    protected SaveDefinitionFileMutationResolver $saveDefinitionFileMutationResolver;

    #[Required]
    final public function autowireSaveDefinitionFileMutationResolverBridge(
        SaveDefinitionFileMutationResolver $saveDefinitionFileMutationResolver,
    ): void {
        $this->saveDefinitionFileMutationResolver = $saveDefinitionFileMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->saveDefinitionFileMutationResolver;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        return $this->translationAPI->__('System action "save definition file" executed successfully.', 'pop-system');
    }
}
