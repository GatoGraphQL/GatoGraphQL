<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\FileUploadPictureMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class FileUploadPictureMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    protected FileUploadPictureMutationResolver $fileUploadPictureMutationResolver;
    
    #[Required]
    public function autowireFileUploadPictureMutationResolverBridge(
        FileUploadPictureMutationResolver $fileUploadPictureMutationResolver,
    ): void {
        $this->fileUploadPictureMutationResolver = $fileUploadPictureMutationResolver;
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->fileUploadPictureMutationResolver;
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
     * @return array<string, mixed>|null
     */
    public function executeMutation(array &$data_properties): ?array
    {
        parent::executeMutation($data_properties);
        return null;
    }
}
