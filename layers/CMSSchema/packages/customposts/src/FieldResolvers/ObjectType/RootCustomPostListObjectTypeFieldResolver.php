<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\Root\App;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoPCMSSchema\CustomPosts\Module;
use PoPCMSSchema\CustomPosts\ModuleConfiguration;
use PoPCMSSchema\CustomPosts\ComponentProcessors\CommonCustomPostFilterInputContainerComponentProcessor;
use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostByInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\RootCustomPostsFilterInputObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;

/**
 * Add the Custom Post fields to the Root
 */
class RootCustomPostListObjectTypeFieldResolver extends AbstractCustomPostListObjectTypeFieldResolver
{
    private ?CustomPostByInputObjectTypeResolver $customPostByInputObjectTypeResolver = null;
    private ?RootCustomPostsFilterInputObjectTypeResolver $rootCustomPostsFilterInputObjectTypeResolver = null;

    final public function setCustomPostByInputObjectTypeResolver(CustomPostByInputObjectTypeResolver $customPostByInputObjectTypeResolver): void
    {
        $this->customPostByInputObjectTypeResolver = $customPostByInputObjectTypeResolver;
    }
    final protected function getCustomPostByInputObjectTypeResolver(): CustomPostByInputObjectTypeResolver
    {
        return $this->customPostByInputObjectTypeResolver ??= $this->instanceManager->getInstance(CustomPostByInputObjectTypeResolver::class);
    }
    final public function setRootCustomPostsFilterInputObjectTypeResolver(RootCustomPostsFilterInputObjectTypeResolver $rootCustomPostsFilterInputObjectTypeResolver): void
    {
        $this->rootCustomPostsFilterInputObjectTypeResolver = $rootCustomPostsFilterInputObjectTypeResolver;
    }
    final protected function getRootCustomPostsFilterInputObjectTypeResolver(): RootCustomPostsFilterInputObjectTypeResolver
    {
        return $this->rootCustomPostsFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(RootCustomPostsFilterInputObjectTypeResolver::class);
    }

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
                'customPost',
            ]
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'customPost' => $this->__('Query a custom post by different properties', 'customposts'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldFilterInputContainerModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'customPost' => [CommonCustomPostFilterInputContainerComponentProcessor::class, CommonCustomPostFilterInputContainerComponentProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_STATUS_UNIONTYPE],
            default => parent::getFieldFilterInputContainerModule($objectTypeResolver, $fieldName),
        };
    }

    protected function getCustomPostsFilterInputObjectTypeResolver(): AbstractCustomPostsFilterInputObjectTypeResolver
    {
        return $this->getRootCustomPostsFilterInputObjectTypeResolver();
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        return match ($fieldName) {
            'customPost' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'by' => $this->getCustomPostByInputObjectTypeResolver(),
                ]
            ),
            default => $fieldArgNameTypeResolvers,
        };
    }

    public function getAdminFieldArgNames(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $adminFieldArgNames = parent::getAdminFieldArgNames($objectTypeResolver, $fieldName);
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        switch ($fieldName) {
            case 'customPost':
                if ($moduleConfiguration->treatCustomPostStatusAsAdminData()) {
                    $customPostStatusFilterInputName = FilterInputHelper::getFilterInputName([
                        FilterInputComponentProcessor::class,
                        FilterInputComponentProcessor::MODULE_FILTERINPUT_CUSTOMPOSTSTATUS
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
            ['customPost' => 'by'] => SchemaTypeModifiers::MANDATORY,
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
        switch ($fieldName) {
            case 'customPost':
                $query = array_merge(
                    $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs),
                    $this->getQuery($objectTypeResolver, $object, $fieldName, $fieldArgs)
                );
                if ($posts = $this->getCustomPostTypeAPI()->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $posts[0];
                }
                return null;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'customPost' => CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
