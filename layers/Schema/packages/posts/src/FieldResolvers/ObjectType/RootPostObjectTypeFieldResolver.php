<?php

declare(strict_types=1);

namespace PoPSchema\Posts\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoPSchema\CustomPosts\ModuleProcessors\CommonCustomPostFilterInputContainerModuleProcessor;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\Posts\FieldResolvers\ObjectType\AbstractPostObjectTypeFieldResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\ModuleProcessors\CommonFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\Constants\QueryOptions;

class RootPostObjectTypeFieldResolver extends AbstractPostObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return array_merge(
            parent::getFieldNamesToResolve(),
            [
                'post',
                'postForAdmin',
                'postBySlug',
                'postBySlugForAdmin',
            ]
        );
    }

    public function getAdminFieldNames(): array
    {
        return array_merge(
            parent::getAdminFieldNames(),
            [
                'postForAdmin',
                'postBySlugForAdmin',
            ]
        );
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'post' => $this->translationAPI->__('Post with a specific ID', 'posts'),
            'postBySlug' => $this->translationAPI->__('Post with a specific slug', 'posts'),
            'postForAdmin' => $this->translationAPI->__('[Unrestricted] Post with a specific ID', 'posts'),
            'postBySlugForAdmin' => $this->translationAPI->__('[Unrestricted] Post with a specific slug', 'posts'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getFieldFilterInputContainerModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'post' => [
                CommonFilterInputContainerModuleProcessor::class,
                CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_ID
            ],
            'postForAdmin' => [
                CommonCustomPostFilterInputContainerModuleProcessor::class,
                CommonCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS
            ],
            'postBySlug' => [
                CommonFilterInputContainerModuleProcessor::class,
                CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_SLUG
            ],
            'postBySlugForAdmin' => [
                CommonCustomPostFilterInputContainerModuleProcessor::class,
                CommonCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS
            ],
            default => parent::getFieldFilterInputContainerModule($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs);
        switch ($fieldName) {
            case 'post':
            case 'postBySlug':
            case 'postForAdmin':
            case 'postBySlugForAdmin':
                if ($posts = $this->postTypeAPI->getPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $posts[0];
                }
                return null;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'post':
            case 'postForAdmin':
            case 'postBySlug':
            case 'postBySlugForAdmin':
                return $this->postObjectTypeResolver;
        }

        return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }
}
