<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\FieldResolvers\InterfaceType;

use PoPCMSSchema\CustomPosts\Module;
use PoPCMSSchema\CustomPosts\ModuleConfiguration;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostEnumStringScalarTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostStatusEnumTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InterfaceType\CustomPostInterfaceTypeResolver;
use PoPCMSSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\CommonFilterInputContainerComponentProcessor;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateTimeScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\HTMLScalarTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractQueryableSchemaInterfaceTypeFieldResolver;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;

class CustomPostInterfaceTypeFieldResolver extends AbstractQueryableSchemaInterfaceTypeFieldResolver
{
    private ?CustomPostStatusEnumTypeResolver $customPostStatusEnumTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?DateTimeScalarTypeResolver $dateTimeScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?HTMLScalarTypeResolver $htmlScalarTypeResolver = null;
    private ?QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver = null;
    private ?CustomPostEnumStringScalarTypeResolver $customPostEnumStringScalarTypeResolver = null;

    final protected function getCustomPostStatusEnumTypeResolver(): CustomPostStatusEnumTypeResolver
    {
        if ($this->customPostStatusEnumTypeResolver === null) {
            /** @var CustomPostStatusEnumTypeResolver */
            $customPostStatusEnumTypeResolver = $this->instanceManager->getInstance(CustomPostStatusEnumTypeResolver::class);
            $this->customPostStatusEnumTypeResolver = $customPostStatusEnumTypeResolver;
        }
        return $this->customPostStatusEnumTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        if ($this->booleanScalarTypeResolver === null) {
            /** @var BooleanScalarTypeResolver */
            $booleanScalarTypeResolver = $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
            $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        }
        return $this->booleanScalarTypeResolver;
    }
    final protected function getDateTimeScalarTypeResolver(): DateTimeScalarTypeResolver
    {
        if ($this->dateTimeScalarTypeResolver === null) {
            /** @var DateTimeScalarTypeResolver */
            $dateTimeScalarTypeResolver = $this->instanceManager->getInstance(DateTimeScalarTypeResolver::class);
            $this->dateTimeScalarTypeResolver = $dateTimeScalarTypeResolver;
        }
        return $this->dateTimeScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final protected function getHTMLScalarTypeResolver(): HTMLScalarTypeResolver
    {
        if ($this->htmlScalarTypeResolver === null) {
            /** @var HTMLScalarTypeResolver */
            $htmlScalarTypeResolver = $this->instanceManager->getInstance(HTMLScalarTypeResolver::class);
            $this->htmlScalarTypeResolver = $htmlScalarTypeResolver;
        }
        return $this->htmlScalarTypeResolver;
    }
    final protected function getQueryableInterfaceTypeFieldResolver(): QueryableInterfaceTypeFieldResolver
    {
        if ($this->queryableInterfaceTypeFieldResolver === null) {
            /** @var QueryableInterfaceTypeFieldResolver */
            $queryableInterfaceTypeFieldResolver = $this->instanceManager->getInstance(QueryableInterfaceTypeFieldResolver::class);
            $this->queryableInterfaceTypeFieldResolver = $queryableInterfaceTypeFieldResolver;
        }
        return $this->queryableInterfaceTypeFieldResolver;
    }
    final protected function getCustomPostEnumStringScalarTypeResolver(): CustomPostEnumStringScalarTypeResolver
    {
        if ($this->customPostEnumStringScalarTypeResolver === null) {
            /** @var CustomPostEnumStringScalarTypeResolver */
            $customPostEnumStringScalarTypeResolver = $this->instanceManager->getInstance(CustomPostEnumStringScalarTypeResolver::class);
            $this->customPostEnumStringScalarTypeResolver = $customPostEnumStringScalarTypeResolver;
        }
        return $this->customPostEnumStringScalarTypeResolver;
    }

    /**
     * @return array<class-string<InterfaceTypeResolverInterface>>
     */
    public function getInterfaceTypeResolverClassesToAttachTo(): array
    {
        return [
            CustomPostInterfaceTypeResolver::class,
        ];
    }

    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->getQueryableInterfaceTypeFieldResolver(),
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToImplement(): array
    {
        return [
            'url',
            'urlPath',
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
            'rawTitle',
            'excerpt',
            'rawExcerpt',
            'customPostType',
        ];
    }

    /**
     * @return string[]
     */
    public function getSensitiveFieldNames(): array
    {
        $sensitiveFieldArgNames = parent::getSensitiveFieldNames();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->treatCustomPostRawContentFieldsAsSensitiveData()) {
            $sensitiveFieldArgNames[] = 'rawContent';
            $sensitiveFieldArgNames[] = 'rawTitle';
            $sensitiveFieldArgNames[] = 'rawExcerpt';
        }
        return $sensitiveFieldArgNames;
    }

    public function getFieldTypeResolver(string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'isStatus'
                => $this->getBooleanScalarTypeResolver(),
            'date',
            'modifiedDate'
                => $this->getDateTimeScalarTypeResolver(),
            'title',
            'rawTitle',
            'excerpt',
            'rawExcerpt',
            'dateStr',
            'modifiedDateStr'
                => $this->getStringScalarTypeResolver(),
            'content',
            'rawContent'
                => $this->getHTMLScalarTypeResolver(),
            'customPostType'
                => $this->getCustomPostEnumStringScalarTypeResolver(),
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
            case 'title':
            case 'rawTitle':
            case 'excerpt':
            case 'rawExcerpt':
                return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getFieldTypeModifiers($fieldName);
    }

    public function getFieldDescription(string $fieldName): ?string
    {
        return match ($fieldName) {
            'url' => $this->__('Custom post URL', 'customposts'),
            'urlPath' => $this->__('Custom post URL path', 'customposts'),
            'slug' => $this->__('Custom post slug', 'customposts'),
            'content' => $this->__('Custom post content', 'customposts'),
            'rawContent' => $this->__('Custom post content in raw format (as it exists in the database)', 'customposts'),
            'status' => $this->__('Custom post status', 'customposts'),
            'isStatus' => $this->__('Is the custom post in the given status?', 'customposts'),
            'date' => $this->__('Custom post published date', 'customposts'),
            'dateStr' => $this->__('Custom post published date, in String format', 'customposts'),
            'modifiedDate' => $this->__('Custom post modified date', 'customposts'),
            'modifiedDateStr' => $this->__('Custom post modified date, in String format', 'customposts'),
            'title' => $this->__('Custom post title', 'customposts'),
            'rawTitle' => $this->__('Custom post title in raw format (as it exists in the database)', 'customposts'),
            'excerpt' => $this->__('Custom post excerpt', 'customposts'),
            'rawExcerpt' => $this->__('Custom post excerpt in raw format (as it exists in the database)', 'customposts'),
            'customPostType' => $this->__('Custom post type', 'customposts'),
            default => parent::getFieldDescription($fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
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

    public function getFieldFilterInputContainerComponent(string $fieldName): ?Component
    {
        return match ($fieldName) {
            'date' => new Component(CommonFilterInputContainerComponentProcessor::class, CommonFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_GMTDATE),
            'dateStr' => new Component(CommonFilterInputContainerComponentProcessor::class, CommonFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_GMTDATE_AS_STRING),
            'modifiedDate' => new Component(CommonFilterInputContainerComponentProcessor::class, CommonFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_GMTDATE),
            'modifiedDateStr' => new Component(CommonFilterInputContainerComponentProcessor::class, CommonFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_GMTDATE_AS_STRING),
            default => parent::getFieldFilterInputContainerComponent($fieldName),
        };
    }
}
