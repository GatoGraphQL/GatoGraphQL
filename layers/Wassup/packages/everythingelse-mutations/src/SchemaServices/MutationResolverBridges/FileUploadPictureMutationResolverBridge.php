<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\FileUploadPictureMutationResolver;

class FileUploadPictureMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return FileUploadPictureMutationResolver::class;
    }
    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getFormData(): array
    {
        $vars = ApplicationState::getVars();
        return [
            'user_id' => $vars['global-userstate']['current-user-id'],
        ];
    }
    /**
     * @param array $data_properties
     * @return array<string, mixed>|null
     */
    public function execute(array &$data_properties): ?array
    {
        parent::execute($data_properties);
        return null;
    }
}
