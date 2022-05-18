<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\FieldResolvers\InterfaceType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractQueryableSchemaInterfaceTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostEnumTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostStatusEnumTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InterfaceType\IsCustomPostInterfaceTypeResolver;
use PoPCMSSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\CommonFilterInputContainerComponentProcessor;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateTimeScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\HTMLScalarTypeResolver;

class IsCustomPostInterfaceTypeFieldResolver extends AbstractQueryableSchemaInterfaceTypeFieldResolver
{
    private ?CustomPostStatusEnumTypeResolver $customPostStatusEnumTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?DateTimeScalarTypeResolver $dateTimeScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?HTMLScalarTypeResolver $htmlScalarTypeResolver = null;
    private ?QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver = null;
    private ?CustomPostEnumTypeResolver $customPostEnumTypeResolver = null;

    final public function setCustomPostStatusEnumTypeResolver(CustomPostStatusEnumTypeResolver $customPostStatusEnumTypeResolver): void
    {
        $this->customPostStatusEnumTypeResolver = $customPostStatusEnumTypeResolver;
    }
    final protected function getCustomPostStatusEnumTypeResolver(): CustomPostStatusEnumTypeResolver
    {
        return $this->customPostStatusEnumTypeResolver ??= $this->instanceManager->getInstance(CustomPostStatusEnumTypeResolver::class);
    }
    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }
    final public function setDateTimeScalarTypeResolver(DateTimeScalarTypeResolver $dateTimeScalarTypeResolver): void
    {
        $this->dateTimeScalarTypeResolver = $dateTimeScalarTypeResolver;
    }
    final protected function getDateTimeScalarTypeResolver(): DateTimeScalarTypeResolver
    {
        return $this->dateTimeScalarTypeResolver ??= $this->instanceManager->getInstance(DateTimeScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setHTMLScalarTypeResolver(HTMLScalarTypeResolver $htmlScalarTypeResolver): void
    {
        $this->htmlScalarTypeResolver = $htmlScalarTypeResolver;
    }
    final protected function getHTMLScalarTypeResolver(): HTMLScalarTypeResolver
    {
        return $this->htmlScalarTypeResolver ??= $this->instanceManager->getInstance(HTMLScalarTypeResolver::class);
    }
    final public function setQueryableInterfaceTypeFieldResolver(QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver): void
    {
        $this->queryableInterfaceTypeFieldResolver = $queryableInterfaceTypeFieldResolver;
    }
    final protected function getQueryableInterfaceTypeFieldResolver(): QueryableInterfaceTypeFieldResolver
    {
        return $this->queryableInterfaceTypeFieldResolver ??= $this->instanceManager->getInstance(QueryableInterfaceTypeFieldResolver::class);
    }
    final public function setCustomPostEnumTypeResolver(CustomPostEnumTypeResolver $customPostEnumTypeResolver): void
    {
        $this->customPostEnumTypeResolver = $customPostEnumTypeResolver;
    }
    final protected function getCustomPostEnumTypeResolver(): CustomPostEnumTypeResolver
    {
        return $this->customPostEnumTypeResolver ??= $this->instanceManager->getInstance(CustomPostEnumTypeResolver::class);
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
            'urlAbsolutePath',
            'slug',
            'content',
            'rawContent',
            'status',
            'isStatus',
            'date',
            'dateStr',
            'modifiedDate',
            'modifiedDateStr',
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
            'modifiedDate'
                => $this->getDateTimeScalarTypeResolver(),
            'rawContent',
            'title',
            'excerpt',
            'dateStr',
            'modifiedDateStr'
                => $this->getStringScalarTypeResolver(),
            'content'
                => $this->getHTMLScalarTypeResolver(),
            'customPostType'
                => $this->getCustomPostEnumTypeResolver(),
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
            case 'rawContent':
            case 'status':
            case 'isStatus':
            case 'date':
            case 'dateStr':
            case 'modifiedDate':
            case 'modifiedDateStr':
            case 'customPostType':
                return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getFieldTypeModifiers($fieldName);
    }

    public function getFieldDescription(string $fieldName): ?string
    {
        return match ($fieldName) {
            'url' => $this->__('Custom post URL', 'customposts'),
            'urlAbsolutePath' => $this->__('Custom post URL path', 'customposts'),
            'slug' => $this->__('Custom post slug', 'customposts'),
            'content' => $this->__('Custom post content, in HTML format', 'customposts'),
            'rawContent' => $this->__('Custom post content, in raw format', 'customposts'),
            'status' => $this->__('Custom post status', 'customposts'),
            'isStatus' => $this->__('Is the custom post in the given status?', 'customposts'),
            'date' => $this->__('Custom post published date', 'customposts'),
            'dateStr' => $this->__('Custom post published date, in String format', 'customposts'),
            'modifiedDate' => $this->__('Custom post modified date', 'customposts'),
            'modifiedDateStr' => $this->__('Custom post modified date, in String format', 'customposts'),
            'title' => $this->__('Custom post title', 'customposts'),
            'excerpt' => $this->__('Custom post excerpt', 'customposts'),
            'customPostType' => $this->__('Custom post type', 'customposts'),
            default => parent::getFieldDescription($fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(string $fieldName): array
    {
        return match ($fieldName) {
            'isStatus' => [
                'status' => $this->getCustomPostStatusEnumTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($fieldName),
        };
    }

    public function getFieldArgDescription(string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['isStatus' => 'status'] => $this->__('The status to check if the post has', 'customposts'),
            default => parent::getFieldArgDescription($fieldName, $fieldArgName),
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
            'date' => [CommonFilterInputContainerComponentProcessor::class, CommonFilterInputContainerComponentProcessor::MODULE_FILTERINPUTCONTAINER_GMTDATE],
            'dateStr' => [CommonFilterInputContainerComponentProcessor::class, CommonFilterInputContainerComponentProcessor::MODULE_FILTERINPUTCONTAINER_GMTDATE_AS_STRING],
            'modifiedDate' => [CommonFilterInputContainerComponentProcessor::class, CommonFilterInputContainerComponentProcessor::MODULE_FILTERINPUTCONTAINER_GMTDATE],
            'modifiedDateStr' => [CommonFilterInputContainerComponentProcessor::class, CommonFilterInputContainerComponentProcessor::MODULE_FILTERINPUTCONTAINER_GMTDATE_AS_STRING],
            default => parent::getFieldFilterInputContainerModule($fieldName),
        };
    }
}
