<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\ModuleProcessors\CommonCustomPostFilterInputContainerModuleProcessor;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\ModuleProcessors\CommonFilterInputContainerModuleProcessor;

/**
 * Add the Custom Post fields to the Root
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class RootCustomPostListFieldResolver extends AbstractCustomPostListFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return array_merge(
            parent::getFieldNamesToResolve(),
            [
                'customPost',
                'customPostBySlug',
                'unrestrictedCustomPost',
                'unrestrictedCustomPostBySlug',
            ]
        );
    }

    public function getAdminFieldNames(): array
    {
        return array_merge(
            parent::getAdminFieldNames(),
            [
                'unrestrictedCustomPost',
                'unrestrictedCustomPostBySlug',
            ]
        );
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'customPost' => $this->translationAPI->__('Custom post with a specific ID', 'customposts'),
            'customPostBySlug' => $this->translationAPI->__('Custom post with a specific slug', 'customposts'),
            'unrestrictedCustomPost' => $this->translationAPI->__('[Unrestricted] Custom post with a specific ID', 'customposts'),
            'unrestrictedCustomPostBySlug' => $this->translationAPI->__('[Unrestricted] Custom post with a specific slug', 'customposts'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'customPost' => SchemaDefinition::TYPE_ID,
            'customPostBySlug' => SchemaDefinition::TYPE_ID,
            'unrestrictedCustomPost' => SchemaDefinition::TYPE_ID,
            'unrestrictedCustomPostBySlug' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getFieldDataFilteringModule(TypeResolverInterface $typeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'customPost' => [CommonFilterInputContainerModuleProcessor::class, CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_ID],
            'unrestrictedCustomPost' => [CommonCustomPostFilterInputContainerModuleProcessor::class, CommonCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS],
            'customPostBySlug' => [CommonFilterInputContainerModuleProcessor::class, CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_SLUG],
            'unrestrictedCustomPostBySlug' => [CommonCustomPostFilterInputContainerModuleProcessor::class, CommonCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS],
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
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        switch ($fieldName) {
            case 'customPost':
            case 'customPostBySlug':
            case 'unrestrictedCustomPost':
            case 'unrestrictedCustomPostBySlug':
                $query = [
                    'types-from-union-resolver-class' => CustomPostUnionTypeResolver::class,
                ];
                $options = $this->getFilterDataloadQueryArgsOptions($typeResolver, $fieldName, $fieldArgs);
                $options['return-type'] = ReturnTypes::IDS;
                if ($posts = $customPostTypeAPI->getCustomPosts($query, $options)) {
                    return $posts[0];
                }
                return null;
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'customPost':
            case 'customPostBySlug':
            case 'unrestrictedCustomPost':
            case 'unrestrictedCustomPostBySlug':
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetTypeResolverClass(CustomPostUnionTypeResolver::class);
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
