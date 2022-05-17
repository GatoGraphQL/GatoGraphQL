<?php

declare(strict_types=1);

namespace PoPCMSSchema\GenericCustomPosts\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\Root\App;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPCMSSchema\CustomPosts\Module as CustomPostsModule;
use PoPCMSSchema\CustomPosts\ModuleConfiguration as CustomPostsModuleConfiguration;
use PoPCMSSchema\CustomPosts\ModuleProcessors\FormInputs\FilterInputModuleProcessor;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostByInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostSortInputObjectTypeResolver;
use PoPCMSSchema\GenericCustomPosts\ModuleProcessors\CommonCustomPostFilterInputContainerModuleProcessor;
use PoPCMSSchema\GenericCustomPosts\TypeResolvers\InputObjectType\GenericCustomPostPaginationInputObjectTypeResolver;
use PoPCMSSchema\GenericCustomPosts\TypeResolvers\InputObjectType\RootGenericCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\GenericCustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;

/**
 * Add fields to the Root for querying for generic custom posts
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class RootGenericCustomPostObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver = null;
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;
    private ?CustomPostByInputObjectTypeResolver $customPostByInputObjectTypeResolver = null;
    private ?RootGenericCustomPostsFilterInputObjectTypeResolver $rootGenericCustomPostsFilterInputObjectTypeResolver = null;
    private ?GenericCustomPostPaginationInputObjectTypeResolver $genericCustomPostPaginationInputObjectTypeResolver = null;
    private ?CustomPostSortInputObjectTypeResolver $customPostSortInputObjectTypeResolver = null;

    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    final public function setGenericCustomPostObjectTypeResolver(GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver): void
    {
        $this->genericCustomPostObjectTypeResolver = $genericCustomPostObjectTypeResolver;
    }
    final protected function getGenericCustomPostObjectTypeResolver(): GenericCustomPostObjectTypeResolver
    {
        return $this->genericCustomPostObjectTypeResolver ??= $this->instanceManager->getInstance(GenericCustomPostObjectTypeResolver::class);
    }
    final public function setCustomPostTypeAPI(CustomPostTypeAPIInterface $customPostTypeAPI): void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        return $this->customPostTypeAPI ??= $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
    }
    final public function setCustomPostByInputObjectTypeResolver(CustomPostByInputObjectTypeResolver $customPostByInputObjectTypeResolver): void
    {
        $this->customPostByInputObjectTypeResolver = $customPostByInputObjectTypeResolver;
    }
    final protected function getCustomPostByInputObjectTypeResolver(): CustomPostByInputObjectTypeResolver
    {
        return $this->customPostByInputObjectTypeResolver ??= $this->instanceManager->getInstance(CustomPostByInputObjectTypeResolver::class);
    }
    final public function setRootGenericCustomPostsFilterInputObjectTypeResolver(RootGenericCustomPostsFilterInputObjectTypeResolver $rootGenericCustomPostsFilterInputObjectTypeResolver): void
    {
        $this->rootGenericCustomPostsFilterInputObjectTypeResolver = $rootGenericCustomPostsFilterInputObjectTypeResolver;
    }
    final protected function getRootGenericCustomPostsFilterInputObjectTypeResolver(): RootGenericCustomPostsFilterInputObjectTypeResolver
    {
        return $this->rootGenericCustomPostsFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(RootGenericCustomPostsFilterInputObjectTypeResolver::class);
    }
    final public function setGenericCustomPostPaginationInputObjectTypeResolver(GenericCustomPostPaginationInputObjectTypeResolver $genericCustomPostPaginationInputObjectTypeResolver): void
    {
        $this->genericCustomPostPaginationInputObjectTypeResolver = $genericCustomPostPaginationInputObjectTypeResolver;
    }
    final protected function getGenericCustomPostPaginationInputObjectTypeResolver(): GenericCustomPostPaginationInputObjectTypeResolver
    {
        return $this->genericCustomPostPaginationInputObjectTypeResolver ??= $this->instanceManager->getInstance(GenericCustomPostPaginationInputObjectTypeResolver::class);
    }
    final public function setCustomPostSortInputObjectTypeResolver(CustomPostSortInputObjectTypeResolver $customPostSortInputObjectTypeResolver): void
    {
        $this->customPostSortInputObjectTypeResolver = $customPostSortInputObjectTypeResolver;
    }
    final protected function getCustomPostSortInputObjectTypeResolver(): CustomPostSortInputObjectTypeResolver
    {
        return $this->customPostSortInputObjectTypeResolver ??= $this->instanceManager->getInstance(CustomPostSortInputObjectTypeResolver::class);
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
            'genericCustomPost',
            'genericCustomPosts',
            'genericCustomPostCount',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'genericCustomPost' => $this->__('Retrieve a single custom post', 'generic-customposts'),
            'genericCustomPosts' => $this->__('Custom posts', 'generic-customposts'),
            'genericCustomPostCount' => $this->__('Number of custom posts', 'generic-customposts'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'genericCustomPost',
            'genericCustomPosts',
                => $this->getGenericCustomPostObjectTypeResolver(),
            'genericCustomPostCount',
                => $this->getIntScalarTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'genericCustomPostCount' => SchemaTypeModifiers::NON_NULLABLE,
            'genericCustomPosts' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldFilterInputContainerModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'genericCustomPost' => [
                CommonCustomPostFilterInputContainerModuleProcessor::class,
                CommonCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_STATUS_GENERICTYPE
            ],
            default => parent::getFieldFilterInputContainerModule($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        return match ($fieldName) {
            'genericCustomPost' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'by' => $this->getCustomPostByInputObjectTypeResolver(),
                ]
            ),
            'genericCustomPosts' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootGenericCustomPostsFilterInputObjectTypeResolver(),
                    'pagination' => $this->getGenericCustomPostPaginationInputObjectTypeResolver(),
                    'sort' => $this->getCustomPostSortInputObjectTypeResolver(),
                ]
            ),
            'genericCustomPostCount' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootGenericCustomPostsFilterInputObjectTypeResolver(),
                ]
            ),
            default => $fieldArgNameTypeResolvers,
        };
    }

    public function getAdminFieldArgNames(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $adminFieldArgNames = parent::getAdminFieldArgNames($objectTypeResolver, $fieldName);
        /** @var CustomPostsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostsModule::class)->getConfiguration();
        switch ($fieldName) {
            case 'genericCustomPost':
                if ($moduleConfiguration->treatCustomPostStatusAsAdminData()) {
                    $customPostStatusFilterInputName = FilterInputHelper::getFilterInputName([
                        FilterInputModuleProcessor::class,
                        FilterInputModuleProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS
                    ]);
                    $adminFieldArgNames[] = $customPostStatusFilterInputName;
                }
                break;
        }
        return $adminFieldArgNames;
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['genericCustomPost' => 'by'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        array $variables,
        array $expressions,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        array $options = []
    ): mixed {
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs);
        switch ($fieldName) {
            case 'genericCustomPost':
                if ($customPosts = $this->getCustomPostTypeAPI()->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $customPosts[0];
                }
                return null;
            case 'genericCustomPosts':
                return $this->getCustomPostTypeAPI()->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'genericCustomPostCount':
                return $this->getCustomPostTypeAPI()->getCustomPostCount($query);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
    }
}
