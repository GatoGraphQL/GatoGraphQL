<?php

declare(strict_types=1);

namespace PoPWPSchema\Pages\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;
use WP_Post;

class RootPageObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    protected PageObjectTypeResolver $pageObjectTypeResolver;
    protected StringScalarTypeResolver $stringScalarTypeResolver;

    #[Required]
    public function autowireRootPageObjectTypeFieldResolver(
        PageObjectTypeResolver $pageObjectTypeResolver,
        StringScalarTypeResolver $stringScalarTypeResolver,
    ): void {
        $this->pageObjectTypeResolver = $pageObjectTypeResolver;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
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

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'pageByPath' => $this->translationAPI->__('Page with a specific URL path', 'pages'),
            'pageByPathForAdmin' => $this->translationAPI->__('[Unrestricted] Page with a specific URL path', 'pages'),
            default => parent::getSchemaFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldArgNameResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'pageByPath',
            'pageByPathForAdmin' => [
                'path' => $this->stringScalarTypeResolver,
            ],
            default => parent::getSchemaFieldArgNameResolvers($objectTypeResolver, $fieldName),
        };
    }
    
    public function getSchemaFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ($fieldArgName) {
            'path' => $this->translationAPI->__('The page URL path', 'pages'),
            default => parent::getSchemaFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }
    
    public function getSchemaFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?int
    {
        return match ($fieldArgName) {
            'path' => SchemaTypeModifiers::MANDATORY,
            default => parent::getSchemaFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
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

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'pageByPath':
            case 'pageByPathForAdmin':
                return $this->pageObjectTypeResolver;
        }

        return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }
}
