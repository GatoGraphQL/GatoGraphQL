<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\ComponentConfiguration as EngineComponentConfiguration;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoPSchema\CustomPostMutations\Schema\SchemaDefinitionHelpers;
use PoPSchema\PostMutations\MutationResolvers\CreatePostMutationResolver;
use PoPSchema\PostMutations\MutationResolvers\UpdatePostMutationResolver;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;

class RootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    public function __construct(
        \PoP\Translation\TranslationAPIInterface $translationAPI,
        \PoP\Hooks\HooksAPIInterface $hooksAPI,
        \PoP\ComponentModel\Instances\InstanceManagerInterface $instanceManager,
        \PoP\ComponentModel\Schema\FieldQueryInterpreterInterface $fieldQueryInterpreter,
        \PoP\LooseContracts\NameResolverInterface $nameResolver,
        \PoP\Engine\CMS\CMSServiceInterface $cmsService,
        \PoP\ComponentModel\HelperServices\SemverHelperServiceInterface $semverHelperService,
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $fieldQueryInterpreter,
            $nameResolver,
            $cmsService,
            $semverHelperService,
        );
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
        $descriptions = [
            'createPost' => $this->translationAPI->__('Create a post', 'post-mutations'),
            'updatePost' => $this->translationAPI->__('Update a post', 'post-mutations'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'createPost':
            case 'updatePost':
                $addCustomPostIDConfig = [
                    'createPost' => false,
                    'updatePost' => true,
                ];
                return SchemaDefinitionHelpers::getCreateUpdateCustomPostSchemaFieldArgs(
                    $objectTypeResolver,
                    $fieldName,
                    $addCustomPostIDConfig[$fieldName],
                    $this->instanceManager->getInstance(PostObjectTypeResolver::class)
                );
        }
        return parent::getSchemaFieldArgs($objectTypeResolver, $fieldName);
    }

    public function getFieldMutationResolverClass(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'createPost':
                return CreatePostMutationResolver::class;
            case 'updatePost':
                return UpdatePostMutationResolver::class;
        }

        return parent::getFieldMutationResolverClass($objectTypeResolver, $fieldName);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'createPost':
            case 'updatePost':
                return $this->instanceManager->getInstance(PostObjectTypeResolver::class);
        }

        return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }
}
