<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostCategoryMetaMutations\Module;
use PoPCMSSchema\CustomPostCategoryMetaMutations\ModuleConfiguration;
use PoPCMSSchema\SchemaCommons\FieldResolvers\ObjectType\BulkOperationDecoratorObjectTypeFieldResolverTrait;
use PoPCMSSchema\SchemaCommons\FieldResolvers\ObjectType\MutationPayloadObjectsObjectTypeFieldResolverTrait;
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

abstract class AbstractRootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver implements SetMetaOnCategoryObjectTypeFieldResolverInterface
{
    use MutationPayloadObjectsObjectTypeFieldResolverTrait;
    use BulkOperationDecoratorObjectTypeFieldResolverTrait;
    use SetMetaOnCategoryObjectTypeFieldResolverTrait;

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
        $addFieldsToQueryPayloadableCustomPostCategoryMetaMutations = $moduleConfiguration->addFieldsToQueryPayloadableCustomPostCategoryMetaMutations();
        return array_merge(
            [
                $this->getSetMetaFieldName(),
                $this->getBulkOperationSetMetaFieldName(),
            ],
            $addFieldsToQueryPayloadableCustomPostCategoryMetaMutations ? [
                $this->getSetMetaFieldName() . 'MutationPayloadObjects',
            ] : [],
        );
    }

    abstract protected function getSetMetaFieldName(): string;
    abstract protected function getBulkOperationSetMetaFieldName(): string;

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            $this->getSetMetaFieldName() => sprintf(
                $this->__('Set categories on a %s', 'custompost-categorymeta-mutations'),
                $this->getEntityName()
            ),
            $this->getBulkOperationSetMetaFieldName() => sprintf(
                $this->__('Set categories on a %s in bulk', 'custompost-categorymeta-mutations'),
                $this->getEntityName()
            ),
            $this->getSetMetaFieldName() . 'MutationPayloadObjects' => sprintf(
                $this->__('Retrieve the payload objects from a recently-executed `%s` mutation', 'post-mutations'),
                $this->getSetMetaFieldName()
            ),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostCategoryMetaMutations = $moduleConfiguration->usePayloadableCustomPostCategoryMetaMutations();
        if (!$usePayloadableCustomPostCategoryMetaMutations) {
            return match ($fieldName) {
                $this->getSetMetaFieldName() => SchemaTypeModifiers::NONE,
                $this->getBulkOperationSetMetaFieldName() => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
                default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
            };
        }

        if (
            in_array($fieldName, [
            $this->getSetMetaFieldName() . 'MutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldTypeModifiers();
        }

        return match ($fieldName) {
            $this->getSetMetaFieldName() => SchemaTypeModifiers::NON_NULLABLE,
            $this->getBulkOperationSetMetaFieldName() => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            $this->getSetMetaFieldName() => [
                'input' => $this->getCategorySetMetaInputObjectTypeResolver(),
            ],
            $this->getBulkOperationSetMetaFieldName() => $this->getBulkOperationFieldArgNameTypeResolvers($this->getCategorySetMetaInputObjectTypeResolver()),
            $this->getSetMetaFieldName() . 'MutationPayloadObjects' => $this->getMutationPayloadObjectsFieldArgNameTypeResolvers(),
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        if (
            in_array($fieldName, [
            $this->getSetMetaFieldName() . 'MutationPayloadObjects',
            ])
        ) {
            return $this->getMutationPayloadObjectsFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        if (
            in_array($fieldName, [
            $this->getBulkOperationSetMetaFieldName(),
            ])
        ) {
            return $this->getBulkOperationFieldArgTypeModifiers($fieldArgName)
                ?? parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return match ([$fieldName => $fieldArgName]) {
            [$this->getSetMetaFieldName() => 'input']
                => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        if (
            in_array($fieldName, [
            $this->getBulkOperationSetMetaFieldName(),
            ])
        ) {
            return $this->getBulkOperationFieldArgDefaultValue($fieldArgName)
                ?? parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
        }

        return parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostCategoryMetaMutations = $moduleConfiguration->usePayloadableCustomPostCategoryMetaMutations();
        return match ($fieldName) {
            $this->getSetMetaFieldName() => $usePayloadableCustomPostCategoryMetaMutations
                ? $this->getPayloadableSetMetaMutationResolver()
                : $this->getSetMetaMutationResolver(),
            $this->getBulkOperationSetMetaFieldName() => $usePayloadableCustomPostCategoryMetaMutations
                ? $this->getPayloadableSetMetaBulkOperationMutationResolver()
                : $this->getSetMetaBulkOperationMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostCategoryMetaMutations = $moduleConfiguration->usePayloadableCustomPostCategoryMetaMutations();
        if ($usePayloadableCustomPostCategoryMetaMutations) {
            return match ($fieldName) {
                $this->getSetMetaFieldName(),
                $this->getBulkOperationSetMetaFieldName(),
                $this->getSetMetaFieldName() . 'MutationPayloadObjects'
                    => $this->getRootSetMetaMutationPayloadObjectTypeResolver(),
                default
                    => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            $this->getSetMetaFieldName(),
            $this->getBulkOperationSetMetaFieldName()
                => $this->getCategoryObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    abstract protected function getRootSetMetaMutationPayloadObjectTypeResolver(): ConcreteTypeResolverInterface;

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $fieldName = $fieldDataAccessor->getFieldName();
        switch ($fieldName) {
            case $this->getSetMetaFieldName() . 'MutationPayloadObjects':
                return $this->resolveMutationPayloadObjectsValue(
                    $objectTypeResolver,
                    $fieldDataAccessor,
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
