<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPosts\ComponentProcessors\CommonCustomPostFilterInputContainerComponentProcessor;
use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\CustomPosts\Module;
use PoPCMSSchema\CustomPosts\ModuleConfiguration;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostByOneofInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\RootCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;

/**
 * Add the Custom Post fields to the Root
 */
class RootCustomPostListObjectTypeFieldResolver extends AbstractCustomPostListObjectTypeFieldResolver
{
    private ?CustomPostByOneofInputObjectTypeResolver $customPostByOneofInputObjectTypeResolver = null;
    private ?RootCustomPostsFilterInputObjectTypeResolver $rootCustomPostsFilterInputObjectTypeResolver = null;

    final protected function getCustomPostByOneofInputObjectTypeResolver(): CustomPostByOneofInputObjectTypeResolver
    {
        if ($this->customPostByOneofInputObjectTypeResolver === null) {
            /** @var CustomPostByOneofInputObjectTypeResolver */
            $customPostByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(CustomPostByOneofInputObjectTypeResolver::class);
            $this->customPostByOneofInputObjectTypeResolver = $customPostByOneofInputObjectTypeResolver;
        }
        return $this->customPostByOneofInputObjectTypeResolver;
    }
    final protected function getRootCustomPostsFilterInputObjectTypeResolver(): RootCustomPostsFilterInputObjectTypeResolver
    {
        if ($this->rootCustomPostsFilterInputObjectTypeResolver === null) {
            /** @var RootCustomPostsFilterInputObjectTypeResolver */
            $rootCustomPostsFilterInputObjectTypeResolver = $this->instanceManager->getInstance(RootCustomPostsFilterInputObjectTypeResolver::class);
            $this->rootCustomPostsFilterInputObjectTypeResolver = $rootCustomPostsFilterInputObjectTypeResolver;
        }
        return $this->rootCustomPostsFilterInputObjectTypeResolver;
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

    public function getFieldFilterInputContainerComponent(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?Component
    {
        return match ($fieldName) {
            'customPost' => new Component(CommonCustomPostFilterInputContainerComponentProcessor::class, CommonCustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_BY_STATUS_UNIONTYPE),
            default => parent::getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName),
        };
    }

    protected function getCustomPostsFilterInputObjectTypeResolver(): AbstractCustomPostsFilterInputObjectTypeResolver
    {
        return $this->getRootCustomPostsFilterInputObjectTypeResolver();
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        return match ($fieldName) {
            'customPost' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'by' => $this->getCustomPostByOneofInputObjectTypeResolver(),
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
            case 'customPost':
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
            ['customPost' => 'by'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        switch ($fieldDataAccessor->getFieldName()) {
            case 'customPost':
                $query = array_merge(
                    $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor),
                    $this->getQuery($objectTypeResolver, $object, $fieldDataAccessor)
                );
                if ($posts = $this->getCustomPostTypeAPI()->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $posts[0];
                }
                return null;
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

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'customPost' => CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
