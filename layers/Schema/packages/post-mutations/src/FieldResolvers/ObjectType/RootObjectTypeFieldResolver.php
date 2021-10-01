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
    public function autowireRootObjectTypeFieldResolver(
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

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'createPost' => $this->translationAPI->__('Create a post', 'post-mutations'),
            'updatePost' => $this->translationAPI->__('Update a post', 'post-mutations'),
            default => parent::getSchemaFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldArgNameResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'createPost' => $this->getCreateOrUpdateCustomPostSchemaFieldArgNameResolvers(
                $objectTypeResolver,
                $fieldName,
                false,
                $this->postObjectTypeResolver
            ),
            'updatePost' => $this->getCreateOrUpdateCustomPostSchemaFieldArgNameResolvers(
                $objectTypeResolver,
                $fieldName,
                true,
                $this->postObjectTypeResolver
            ),
            default => parent::getSchemaFieldArgNameResolvers($objectTypeResolver, $fieldName),
        };
    }
    
    public function getSchemaFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ($fieldName) {
            'createPost',
            'updatePost'
                => $this->getCreateOrUpdateCustomPostSchemaFieldArgDescription($fieldArgName),
            default
                => parent::getSchemaFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }
    
    public function getSchemaFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        return match ($fieldName) {
            'createPost',
            'updatePost'
                => $this->getCreateOrUpdateCustomPostSchemaFieldArgDefaultValue($fieldArgName),
            default
                => parent::getSchemaFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }
    
    public function getSchemaFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ($fieldName) {
            'createPost',
            'updatePost'
                => $this->getCreateOrUpdateCustomPostSchemaFieldArgTypeModifiers($fieldArgName),
            default
                => parent::getSchemaFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
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
