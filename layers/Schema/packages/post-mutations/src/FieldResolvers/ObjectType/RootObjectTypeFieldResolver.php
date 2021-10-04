<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\ComponentConfiguration as EngineComponentConfiguration;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoPSchema\CustomPostMutations\FieldResolvers\ObjectType\CreateOrUpdateCustomPostObjectTypeFieldResolverTrait;
use PoPSchema\PostMutations\MutationResolvers\CreatePostMutationResolver;
use PoPSchema\PostMutations\MutationResolvers\UpdatePostMutationResolver;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class RootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use CreateOrUpdateCustomPostObjectTypeFieldResolverTrait;

    protected PostObjectTypeResolver $postObjectTypeResolver;
    protected CreatePostMutationResolver $createPostMutationResolver;
    protected UpdatePostMutationResolver $updatePostMutationResolver;

    #[Required]
    final public function autowireRootObjectTypeFieldResolver(
        PostObjectTypeResolver $postObjectTypeResolver,
        CreatePostMutationResolver $createPostMutationResolver,
        UpdatePostMutationResolver $updatePostMutationResolver,
    ): void {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
        $this->createPostMutationResolver = $createPostMutationResolver;
        $this->updatePostMutationResolver = $updatePostMutationResolver;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return array_merge(
            [
                'createPost',
            ],
            !EngineComponentConfiguration::disableRedundantRootTypeMutationFields() ?
                [
                    'updatePost',
                ] : []
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'createPost' => $this->translationAPI->__('Create a post', 'post-mutations'),
            'updatePost' => $this->translationAPI->__('Update a post', 'post-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'createPost' => $this->getCreateOrUpdateCustomPostSchemaFieldArgNameResolvers(false),
            'updatePost' => $this->getCreateOrUpdateCustomPostSchemaFieldArgNameResolvers(true),
            default => parent::getFieldArgNameResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ($fieldName) {
            'createPost',
            'updatePost'
                => $this->getCreateOrUpdateCustomPostSchemaFieldArgDescription($fieldArgName),
            default
                => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ($fieldName) {
            'createPost',
            'updatePost'
                => $this->getCreateOrUpdateCustomPostSchemaFieldArgTypeModifiers($fieldArgName),
            default
                => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldMutationResolver(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName
    ): ?MutationResolverInterface {
        switch ($fieldName) {
            case 'createPost':
                return $this->createPostMutationResolver;
            case 'updatePost':
                return $this->updatePostMutationResolver;
        }

        return parent::getFieldMutationResolver($objectTypeResolver, $fieldName);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'createPost':
            case 'updatePost':
                return $this->postObjectTypeResolver;
        }

        return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }
}
