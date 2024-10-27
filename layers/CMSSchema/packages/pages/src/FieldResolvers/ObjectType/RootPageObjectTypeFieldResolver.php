<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPosts\ComponentProcessors\CommonCustomPostFilterInputContainerComponentProcessor;
use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\CustomPosts\Module;
use PoPCMSSchema\CustomPosts\ModuleConfiguration;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostSortInputObjectTypeResolver;
use PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface;
use PoPCMSSchema\Pages\TypeResolvers\InputObjectType\PageByOneofInputObjectTypeResolver;
use PoPCMSSchema\Pages\TypeResolvers\InputObjectType\PagePaginationInputObjectTypeResolver;
use PoPCMSSchema\Pages\TypeResolvers\InputObjectType\RootPagesFilterInputObjectTypeResolver;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class RootPageObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?PageObjectTypeResolver $pageObjectTypeResolver = null;
    private ?PageTypeAPIInterface $pageTypeAPI = null;
    private ?PageByOneofInputObjectTypeResolver $pageByOneofInputObjectTypeResolver = null;
    private ?RootPagesFilterInputObjectTypeResolver $rootPagesFilterInputObjectTypeResolver = null;
    private ?PagePaginationInputObjectTypeResolver $pagePaginationInputObjectTypeResolver = null;
    private ?CustomPostSortInputObjectTypeResolver $customPostSortInputObjectTypeResolver = null;

    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        if ($this->intScalarTypeResolver === null) {
            /** @var IntScalarTypeResolver */
            $intScalarTypeResolver = $this->instanceManager->getInstance(IntScalarTypeResolver::class);
            $this->intScalarTypeResolver = $intScalarTypeResolver;
        }
        return $this->intScalarTypeResolver;
    }
    final protected function getPageObjectTypeResolver(): PageObjectTypeResolver
    {
        if ($this->pageObjectTypeResolver === null) {
            /** @var PageObjectTypeResolver */
            $pageObjectTypeResolver = $this->instanceManager->getInstance(PageObjectTypeResolver::class);
            $this->pageObjectTypeResolver = $pageObjectTypeResolver;
        }
        return $this->pageObjectTypeResolver;
    }
    final protected function getPageTypeAPI(): PageTypeAPIInterface
    {
        if ($this->pageTypeAPI === null) {
            /** @var PageTypeAPIInterface */
            $pageTypeAPI = $this->instanceManager->getInstance(PageTypeAPIInterface::class);
            $this->pageTypeAPI = $pageTypeAPI;
        }
        return $this->pageTypeAPI;
    }
    final protected function getPageByOneofInputObjectTypeResolver(): PageByOneofInputObjectTypeResolver
    {
        if ($this->pageByOneofInputObjectTypeResolver === null) {
            /** @var PageByOneofInputObjectTypeResolver */
            $pageByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(PageByOneofInputObjectTypeResolver::class);
            $this->pageByOneofInputObjectTypeResolver = $pageByOneofInputObjectTypeResolver;
        }
        return $this->pageByOneofInputObjectTypeResolver;
    }
    final protected function getRootPagesFilterInputObjectTypeResolver(): RootPagesFilterInputObjectTypeResolver
    {
        if ($this->rootPagesFilterInputObjectTypeResolver === null) {
            /** @var RootPagesFilterInputObjectTypeResolver */
            $rootPagesFilterInputObjectTypeResolver = $this->instanceManager->getInstance(RootPagesFilterInputObjectTypeResolver::class);
            $this->rootPagesFilterInputObjectTypeResolver = $rootPagesFilterInputObjectTypeResolver;
        }
        return $this->rootPagesFilterInputObjectTypeResolver;
    }
    final protected function getPagePaginationInputObjectTypeResolver(): PagePaginationInputObjectTypeResolver
    {
        if ($this->pagePaginationInputObjectTypeResolver === null) {
            /** @var PagePaginationInputObjectTypeResolver */
            $pagePaginationInputObjectTypeResolver = $this->instanceManager->getInstance(PagePaginationInputObjectTypeResolver::class);
            $this->pagePaginationInputObjectTypeResolver = $pagePaginationInputObjectTypeResolver;
        }
        return $this->pagePaginationInputObjectTypeResolver;
    }
    final protected function getCustomPostSortInputObjectTypeResolver(): CustomPostSortInputObjectTypeResolver
    {
        if ($this->customPostSortInputObjectTypeResolver === null) {
            /** @var CustomPostSortInputObjectTypeResolver */
            $customPostSortInputObjectTypeResolver = $this->instanceManager->getInstance(CustomPostSortInputObjectTypeResolver::class);
            $this->customPostSortInputObjectTypeResolver = $customPostSortInputObjectTypeResolver;
        }
        return $this->customPostSortInputObjectTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'page',
            'pages',
            'pageCount',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'page' => $this->__('Retrieve a single page', 'pages'),
            'pages' => $this->__('Pages', 'pages'),
            'pageCount' => $this->__('Number of pages', 'pages'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'page',
            'pages'
                => $this->getPageObjectTypeResolver(),
            'pageCount'
                => $this->getIntScalarTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'pageCount' => SchemaTypeModifiers::NON_NULLABLE,
            'pages' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldFilterInputContainerComponent(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?Component
    {
        return match ($fieldName) {
            'page' => new Component(
                CommonCustomPostFilterInputContainerComponentProcessor::class,
                CommonCustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTSTATUS
            ),
            default => parent::getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        return match ($fieldName) {
            'page' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'by' => $this->getPageByOneofInputObjectTypeResolver(),
                ]
            ),
            'pages' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootPagesFilterInputObjectTypeResolver(),
                    'pagination' => $this->getPagePaginationInputObjectTypeResolver(),
                    'sort' => $this->getCustomPostSortInputObjectTypeResolver(),
                ]
            ),
            'pageCount' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootPagesFilterInputObjectTypeResolver(),
                ]
            ),
            default => $fieldArgNameTypeResolvers,
        };
    }

    /**
     * @return string[]
     */
    public function getSensitiveFieldArgNames(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $sensitiveFieldArgNames = parent::getSensitiveFieldArgNames($objectTypeResolver, $fieldName);
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        switch ($fieldName) {
            case 'page':
                if ($moduleConfiguration->treatCustomPostStatusAsSensitiveData()) {
                    $customPostStatusFilterInputName = FilterInputHelper::getFilterInputName(new Component(
                        FilterInputComponentProcessor::class,
                        FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS
                    ));
                    $sensitiveFieldArgNames[] = $customPostStatusFilterInputName;
                }
                break;
        }
        return $sensitiveFieldArgNames;
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['page' => 'by'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor);
        switch ($fieldDataAccessor->getFieldName()) {
            case 'page':
                if ($pages = $this->getPageTypeAPI()->getPages($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $pages[0];
                }
                return null;
            case 'pages':
                return $this->getPageTypeAPI()->getPages($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'pageCount':
                return $this->getPageTypeAPI()->getPageCount($query);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * Since the return type is known for all the fields in this
     * FieldResolver, there's no need to validate them
     */
    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        return false;
    }
}
