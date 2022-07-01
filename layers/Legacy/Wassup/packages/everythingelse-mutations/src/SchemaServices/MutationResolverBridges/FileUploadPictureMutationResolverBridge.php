<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\App;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\FileUploadPictureMutationResolver;

class FileUploadPictureMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    private ?FileUploadPictureMutationResolver $fileUploadPictureMutationResolver = null;
    
    final public function setFileUploadPictureMutationResolver(FileUploadPictureMutationResolver $fileUploadPictureMutationResolver): void
    {
        $this->fileUploadPictureMutationResolver = $fileUploadPictureMutationResolver;
    }
    final protected function getFileUploadPictureMutationResolver(): FileUploadPictureMutationResolver
    {
        return $this->fileUploadPictureMutationResolver ??= $this->instanceManager->getInstance(FileUploadPictureMutationResolver::class);
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getFileUploadPictureMutationResolver();
    }
    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function fillMutationDataProvider(\PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): void
    {
        $mutationDataProvider->add('user_id', App::getState('current-user-id'));
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
