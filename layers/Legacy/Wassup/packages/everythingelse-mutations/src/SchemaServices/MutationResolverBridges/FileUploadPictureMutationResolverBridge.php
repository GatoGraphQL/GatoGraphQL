<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\FileUploadPictureMutationResolver;

class FileUploadPictureMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    protected FileUploadPictureMutationResolver $fileUploadPictureMutationResolver;
    public function __construct(
        FileUploadPictureMutationResolver $fileUploadPictureMutationResolver,
    ) {
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
