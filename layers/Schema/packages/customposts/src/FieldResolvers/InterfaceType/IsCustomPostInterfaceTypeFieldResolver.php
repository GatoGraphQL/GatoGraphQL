<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\FieldResolvers\InterfaceType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractQueryableSchemaInterfaceTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\CustomPosts\Enums\CustomPostContentFormatEnum;
use PoPSchema\CustomPosts\TypeResolvers\EnumType\CustomPostContentFormatEnumTypeResolver;
use PoPSchema\CustomPosts\TypeResolvers\EnumType\CustomPostStatusEnumTypeResolver;
use PoPSchema\CustomPosts\TypeResolvers\InterfaceType\IsCustomPostInterfaceTypeResolver;
use PoPSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver;
use PoPSchema\SchemaCommons\ModuleProcessors\CommonFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateScalarTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class IsCustomPostInterfaceTypeFieldResolver extends AbstractQueryableSchemaInterfaceTypeFieldResolver
{
    private ?CustomPostStatusEnumTypeResolver $customPostStatusEnumTypeResolver = null;
    private ?CustomPostContentFormatEnumTypeResolver $customPostContentFormatEnumTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?DateScalarTypeResolver $dateScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver = null;

    public function setCustomPostStatusEnumTypeResolver(CustomPostStatusEnumTypeResolver $customPostStatusEnumTypeResolver): void
    {
        $this->customPostStatusEnumTypeResolver = $customPostStatusEnumTypeResolver;
    }
    protected function getCustomPostStatusEnumTypeResolver(): CustomPostStatusEnumTypeResolver
    {
        return $this->customPostStatusEnumTypeResolver ??= $this->instanceManager->getInstance(CustomPostStatusEnumTypeResolver::class);
    }
    public function setCustomPostContentFormatEnumTypeResolver(CustomPostContentFormatEnumTypeResolver $customPostContentFormatEnumTypeResolver): void
    {
        $this->customPostContentFormatEnumTypeResolver = $customPostContentFormatEnumTypeResolver;
    }
    protected function getCustomPostContentFormatEnumTypeResolver(): CustomPostContentFormatEnumTypeResolver
    {
        return $this->customPostContentFormatEnumTypeResolver ??= $this->instanceManager->getInstance(CustomPostContentFormatEnumTypeResolver::class);
    }
    public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }
    public function setDateScalarTypeResolver(DateScalarTypeResolver $dateScalarTypeResolver): void
    {
        $this->dateScalarTypeResolver = $dateScalarTypeResolver;
    }
    protected function getDateScalarTypeResolver(): DateScalarTypeResolver
    {
        return $this->dateScalarTypeResolver ??= $this->instanceManager->getInstance(DateScalarTypeResolver::class);
    }
    public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    public function setQueryableInterfaceTypeFieldResolver(QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver): void
    {
        $this->queryableInterfaceTypeFieldResolver = $queryableInterfaceTypeFieldResolver;
    }
    protected function getQueryableInterfaceTypeFieldResolver(): QueryableInterfaceTypeFieldResolver
    {
        return $this->queryableInterfaceTypeFieldResolver ??= $this->instanceManager->getInstance(QueryableInterfaceTypeFieldResolver::class);
    }

    public function getInterfaceTypeResolverClassesToAttachTo(): array
    {
        return [
            IsCustomPostInterfaceTypeResolver::class,
        ];
    }

    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->getQueryableInterfaceTypeFieldResolver(),
        ];
    }

    public function getFieldNamesToImplement(): array
    {
        return [
            'url',
            'urlPath',
            'slug',
            'content',
            'status',
            'isStatus',
            'date',
            'modified',
            'title',
            'excerpt',
            'customPostType',
        ];
    }

    public function getFieldTypeResolver(string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'isStatus'
                => $this->getBooleanScalarTypeResolver(),
            'date',
            'modified'
                => $this->getDateScalarTypeResolver(),
            'content',
            'title',
            'excerpt',
            'customPostType'
                => $this->getStringScalarTypeResolver(),
            'status'
                => $this->getCustomPostStatusEnumTypeResolver(),
            default
                => parent::getFieldTypeResolver($fieldName),
        };
    }

    public function getFieldTypeModifiers(string $fieldName): int
    {
        /**
         * Please notice that the URL, slug, title and excerpt are nullable,
         * and content is not!
         */
        switch ($fieldName) {
            case 'content':
            case 'status':
            case 'isStatus':
            case 'date':
            case 'modified':
            case 'customPostType':
                return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getFieldTypeModifiers($fieldName);
    }

    public function getFieldDescription(string $fieldName): ?string
    {
        return match ($fieldName) {
            'url' => $this->translationAPI->__('Custom post URL', 'customposts'),
            'urlPath' => $this->translationAPI->__('Custom post URL path', 'customposts'),
            'slug' => $this->translationAPI->__('Custom post slug', 'customposts'),
            'content' => $this->translationAPI->__('Custom post content', 'customposts'),
            'status' => $this->translationAPI->__('Custom post status', 'customposts'),
            'isStatus' => $this->translationAPI->__('Is the custom post in the given status?', 'customposts'),
            'date' => $this->translationAPI->__('Custom post published date', 'customposts'),
            'modified' => $this->translationAPI->__('Custom post modified date', 'customposts'),
            'title' => $this->translationAPI->__('Custom post title', 'customposts'),
            'excerpt' => $this->translationAPI->__('Custom post excerpt', 'customposts'),
            'customPostType' => $this->translationAPI->__('Custom post type', 'customposts'),
            default => parent::getFieldDescription($fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(string $fieldName): array
    {
        return match ($fieldName) {
            'isStatus' => [
                'status' => $this->getCustomPostStatusEnumTypeResolver(),
            ],
            'content' => [
                'format' => $this->getCustomPostContentFormatEnumTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($fieldName),
        };
    }

    public function getFieldArgDescription(string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['isStatus' => 'status'] => $this->translationAPI->__('The status to check if the post has', 'customposts'),
            ['content' => 'format'] => $this->translationAPI->__('The format of the content', 'customposts'),
            default => parent::getFieldArgDescription($fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDefaultValue(string $fieldName, string $fieldArgName): mixed
    {
        return match ([$fieldName => $fieldArgName]) {
            ['content' => 'format'] => $this->getDefaultContentFormatValue(),
            default => parent::getFieldArgDefaultValue($fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['isStatus' => 'status'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($fieldName, $fieldArgName),
        };
    }

    public function getFieldFilterInputContainerModule(string $fieldName): ?array
    {
        return match ($fieldName) {
            'date' => [CommonFilterInputContainerModuleProcessor::class, CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_GMTDATE_AS_STRING],
            'modified' => [CommonFilterInputContainerModuleProcessor::class, CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_GMTDATE_AS_STRING],
            default => parent::getFieldFilterInputContainerModule($fieldName),
        };
    }

    public function getDefaultContentFormatValue(): string
    {
        return CustomPostContentFormatEnum::HTML;
    }
}
