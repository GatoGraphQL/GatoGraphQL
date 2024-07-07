<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostCategoryMutations\Module;
use PoPCMSSchema\CustomPostCategoryMutations\ModuleConfiguration;
use PoPCMSSchema\SchemaCommons\Constants\MutationInputProperties as SchemaCommonsMutationInputProperties;
use PoPCMSSchema\SchemaCommons\FieldResolvers\ObjectType\MutationPayloadObjectsObjectTypeFieldResolverTrait;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\MutationPayloadObjectsInputObjectTypeResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Module as EngineModule;
use PoP\Engine\ModuleConfiguration as EngineModuleConfiguration;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Root\App;

abstract class AbstractRootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver implements SetCategoriesOnCustomPostObjectTypeFieldResolverInterface
{
    use MutationPayloadObjectsObjectTypeFieldResolverTrait;
    use SetCategoriesOnCustomPostObjectTypeFieldResolverTrait;

    private ?MutationPayloadObjectsInputObjectTypeResolver $mutationPayloadObjectsInputObjectTypeResolver = null;

    final public function setMutationPayloadObjectsInputObjectTypeResolver(MutationPayloadObjectsInputObjectTypeResolver $mutationPayloadObjectsInputObjectTypeResolver): void
    {
        $this->mutationPayloadObjectsInputObjectTypeResolver = $mutationPayloadObjectsInputObjectTypeResolver;
    }
    final protected function getMutationPayloadObjectsInputObjectTypeResolver(): MutationPayloadObjectsInputObjectTypeResolver
    {
        if ($this->mutationPayloadObjectsInputObjectTypeResolver === null) {
            /** @var MutationPayloadObjectsInputObjectTypeResolver */
            $mutationPayloadObjectsInputObjectTypeResolver = $this->instanceManager->getInstance(MutationPayloadObjectsInputObjectTypeResolver::class);
            $this->mutationPayloadObjectsInputObjectTypeResolver = $mutationPayloadObjectsInputObjectTypeResolver;
        }
        return $this->mutationPayloadObjectsInputObjectTypeResolver;
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
        /** @var EngineModuleConfiguration */
        $engineModuleConfiguration = App::getModule(EngineModule::class)->getConfiguration();
        if ($engineModuleConfiguration->disableRedundantRootTypeMutationFields()) {
            return [];
        }
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $addFieldsToQueryPayloadableCustomPostCategoryMutations = $moduleConfiguration->addFieldsToQueryPayloadableCustomPostCategoryMutations();
        return array_merge(
            [
                $this->getSetCategoriesFieldName(),
            ],
            $addFieldsToQueryPayloadableCustomPostCategoryMutations ? [
                $this->getSetCategoriesFieldName() . 'MutationPayloadObjects',
            ] : [],
        );
    }

    abstract protected function getSetCategoriesFieldName(): string;

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            $this->getSetCategoriesFieldName() => sprintf(
                $this->__('Set categories on a %s', 'custompost-category-mutations'),
                $this->getEntityName()
            ),
            $this->getSetCategoriesFieldName() . 'MutationPayloadObjects' => sprintf(
                $this->__('Retrieve the payload objects from a recently-executed `%s` mutation', 'post-mutations'),
                $this->getSetCategoriesFieldName()
            ),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostCategoryMutations = $moduleConfiguration->usePayloadableCustomPostCategoryMutations();
        if (!$usePayloadableCustomPostCategoryMutations) {
            return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
        return match ($fieldName) {
            $this->getSetCategoriesFieldName() => SchemaTypeModifiers::NON_NULLABLE,
            $this->getSetCategoriesFieldName() . 'MutationPayloadObjects' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            $this->getSetCategoriesFieldName() => [
                'input' => $this->getCustomPostSetCategoriesInputObjectTypeResolver(),
            ],
            $this->getSetCategoriesFieldName() . 'MutationPayloadObjects' => [
                SchemaCommonsMutationInputProperties::INPUT => $this->getMutationPayloadObjectsInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            [$this->getSetCategoriesFieldName() => 'input'],
            [$this->getSetCategoriesFieldName() . 'MutationPayloadObjects' => SchemaCommonsMutationInputProperties::INPUT]
                => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostCategoryMutations = $moduleConfiguration->usePayloadableCustomPostCategoryMutations();
        return match ($fieldName) {
            $this->getSetCategoriesFieldName() => $usePayloadableCustomPostCategoryMutations
                ? $this->getPayloadableSetCategoriesMutationResolver()
                : $this->getSetCategoriesMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostCategoryMutations = $moduleConfiguration->usePayloadableCustomPostCategoryMutations();
        if ($usePayloadableCustomPostCategoryMutations) {
            return match ($fieldName) {
                $this->getSetCategoriesFieldName(),
                $this->getSetCategoriesFieldName() . 'MutationPayloadObjects'
                    => $this->getRootSetCategoriesMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            $this->getSetCategoriesFieldName() => $this->getCustomPostObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    abstract protected function getRootSetCategoriesMutationPayloadObjectTypeResolver(): ConcreteTypeResolverInterface;

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $fieldName = $fieldDataAccessor->getFieldName();
        switch ($fieldName) {
            case $this->getSetCategoriesFieldName() . 'MutationPayloadObjects':
                return $this->resolveMutationPayloadObjectsValue(
                    $objectTypeResolver,
                    $fieldDataAccessor,
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
