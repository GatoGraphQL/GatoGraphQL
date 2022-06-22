<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\FieldResolvers\ObjectType;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Root\App;
use PoPCMSSchema\CustomPosts\ComponentProcessors\CommonCustomPostFilterInputContainerComponentProcessor;
use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\CustomPosts\Module;
use PoPCMSSchema\CustomPosts\ModuleConfiguration;
use PoPCMSSchema\Posts\TypeResolvers\InputObjectType\PostByInputObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions;

class RootPostObjectTypeFieldResolver extends AbstractPostObjectTypeFieldResolver
{
    private ?PostByInputObjectTypeResolver $postByInputObjectTypeResolver = null;

    final public function setPostByInputObjectTypeResolver(PostByInputObjectTypeResolver $postByInputObjectTypeResolver): void
    {
        $this->postByInputObjectTypeResolver = $postByInputObjectTypeResolver;
    }
    final protected function getPostByInputObjectTypeResolver(): PostByInputObjectTypeResolver
    {
        return $this->postByInputObjectTypeResolver ??= $this->instanceManager->getInstance(PostByInputObjectTypeResolver::class);
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
                'post',
            ]
        );
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'post' => $this->__('Retrieve a single post', 'posts'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldFilterInputContainerComponent(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?Component
    {
        return match ($fieldName) {
            'post' => new Component(
                CommonCustomPostFilterInputContainerComponentProcessor::class,
                CommonCustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTSTATUS
            ),
            default => parent::getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        return match ($fieldName) {
            'post' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'by' => $this->getPostByInputObjectTypeResolver(),
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
            case 'post':
                if ($moduleConfiguration->treatCustomPostStatusAsAdminData()) {
                    $customPostStatusFilterInputName = FilterInputHelper::getFilterInputName(new Component(
                        FilterInputComponentProcessor::class,
                        FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOSTSTATUS
                    ));
                    $adminFieldArgNames[] = $customPostStatusFilterInputName;
                }
                break;
        }
        return $adminFieldArgNames;
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['post' => 'by'] => SchemaTypeModifiers::MANDATORY,
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
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        array $options = []
    ): mixed {
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs);
        switch ($fieldName) {
            case 'post':
                if ($posts = $this->getPostTypeAPI()->getPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $posts[0];
                }
                return null;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $field, $objectTypeFieldResolutionFeedbackStore, $options);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'post' => $this->getPostObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
