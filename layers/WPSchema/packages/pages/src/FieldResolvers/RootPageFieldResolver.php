<?php

declare(strict_types=1);

namespace PoPWPSchema\Pages\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\TypeResolvers\Object\RootTypeResolver;
use PoPSchema\Pages\Facades\PageTypeAPIFacade;
use PoPSchema\Pages\TypeResolvers\Object\PageTypeResolver;
use WP_Post;

class RootPageFieldResolver extends AbstractQueryableFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'pageByPath',
            'pageByPathForAdmin',
        ];
    }

    public function getAdminFieldNames(): array
    {
        return [
            'pageByPathForAdmin',
        ];
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'pageByPath' => $this->translationAPI->__('Page with a specific URL path', 'pages'),
            'pageByPathForAdmin' => $this->translationAPI->__('[Unrestricted] Page with a specific URL path', 'pages'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($relationalTypeResolver, $fieldName);
        switch ($fieldName) {
            case 'pageByPath':
            case 'pageByPathForAdmin':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'path',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The page URL path', 'pages'),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
        }
        return $schemaFieldArgs;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $pageTypeAPI = PageTypeAPIFacade::getInstance();
        switch ($fieldName) {
            case 'pageByPath':
            case 'pageByPathForAdmin':
                /** @var WP_Post|null */
                $page = \get_page_by_path($fieldArgs['path']);
                if ($page === null) {
                    return null;
                }
                // Check the status is allowed
                if ($fieldName === 'pageByPath' && $page->post_status !== "publish") {
                    return null;
                } elseif ($fieldName === 'pageByPathForAdmin') {
                    return null;
                }
                return $page->ID;
        }

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'pageByPath':
            case 'pageByPathForAdmin':
                return PageTypeResolver::class;
        }

        return parent::getFieldTypeResolverClass($relationalTypeResolver, $fieldName);
    }
}
