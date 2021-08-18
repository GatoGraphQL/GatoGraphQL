<?php

declare(strict_types=1);

namespace PoPSchema\Posts\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoPSchema\CustomPosts\FieldResolvers\CustomPostFieldResolverTrait;
use PoPSchema\CustomPosts\ModuleProcessors\CommonCustomPostFilterInputContainerModuleProcessor;
use PoPSchema\CustomPosts\Types\Status;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\Posts\FieldResolvers\AbstractPostFieldResolver;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\ModuleProcessors\CommonFilterInputContainerModuleProcessor;

class RootPostFieldResolver extends AbstractPostFieldResolver
{
    use CustomPostFieldResolverTrait;

    public function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return array_merge(
            parent::getFieldNamesToResolve(),
            [
                'post',
                'unrestrictedPost',
                'postBySlug',
                'unrestrictedPostBySlug',
            ]
        );
    }

    public function getAdminFieldNames(): array
    {
        return array_merge(
            parent::getAdminFieldNames(),
            [
                'unrestrictedPost',
                'unrestrictedPostBySlug',
            ]
        );
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'post' => $this->translationAPI->__('Post with a specific ID', 'posts'),
            'postBySlug' => $this->translationAPI->__('Post with a specific slug', 'posts'),
            'unrestrictedPost' => $this->translationAPI->__('[Unrestricted] Post with a specific ID', 'posts'),
            'unrestrictedPostBySlug' => $this->translationAPI->__('[Unrestricted] Post with a specific slug', 'posts'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'post' => SchemaDefinition::TYPE_ID,
            'postBySlug' => SchemaDefinition::TYPE_ID,
            'unrestrictedPost' => SchemaDefinition::TYPE_ID,
            'unrestrictedPostBySlug' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getFieldDataFilteringModule(TypeResolverInterface $typeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'post' => [
                CommonFilterInputContainerModuleProcessor::class,
                CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_ID
            ],
            'unrestrictedPost' => [
                CommonCustomPostFilterInputContainerModuleProcessor::class,
                CommonCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_AND_STATUS
            ],
            'postBySlug' => [
                CommonFilterInputContainerModuleProcessor::class,
                CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_SLUG
            ],
            'unrestrictedPostBySlug' => [
                CommonCustomPostFilterInputContainerModuleProcessor::class,
                CommonCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_AND_STATUS
            ],
            default => parent::getFieldDataFilteringModule($typeResolver, $fieldName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        $options = $this->getFilterDataloadQueryArgsOptions($typeResolver, $fieldName, $fieldArgs);
        switch ($fieldName) {
            case 'post':
            case 'postBySlug':
            case 'unrestrictedPost':
            case 'unrestrictedPostBySlug':
                $options['return-type'] = ReturnTypes::IDS;
                if ($posts = $postTypeAPI->getPosts([], $options)) {
                    return $posts[0];
                }
                return null;
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'post':
            case 'unrestrictedPost':
            case 'postBySlug':
            case 'unrestrictedPostBySlug':
                return PostTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
