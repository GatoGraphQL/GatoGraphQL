<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostTagMutations\Module;
use PoPCMSSchema\CustomPostTagMutations\ModuleConfiguration;
use PoPCMSSchema\SchemaCommons\Constants\MutationInputProperties as SchemaCommonsMutationInputProperties;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\MutationPayloadObjectsInputObjectTypeResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Module as EngineModule;
use PoP\Engine\ModuleConfiguration as EngineModuleConfiguration;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Root\App;

abstract class AbstractRootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver implements SetTagsOnCustomPostObjectTypeFieldResolverInterface
{
    use SetTagsOnCustomPostObjectTypeFieldResolverTrait;

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
        $addFieldsToQueryPayloadableCustomPostTagMutations = $moduleConfiguration->addFieldsToQueryPayloadableCustomPostTagMutations();
        return array_merge(
            [
                $this->getSetTagsFieldName(),
            ],
            $addFieldsToQueryPayloadableCustomPostTagMutations ? [
                $this->getSetTagsFieldName() . 'MutationPayloadObjects',
            ] : [],
        );
    }

    abstract protected function getSetTagsFieldName(): string;

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            $this->getSetTagsFieldName() => sprintf(
                $this->__('Set tags on a %s', 'custompost-tag-mutations'),
                $this->getEntityName()
            ),
            $this->getSetTagsFieldName() . 'MutationPayloadObjects' => sprintf(
                $this->__('Retrieve the payload objects from a recently-executed `%s` mutation', 'custompost-tag-mutations'),
                $this->getSetTagsFieldName()
            ),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostTagMutations = $moduleConfiguration->usePayloadableCustomPostTagMutations();
        if (!$usePayloadableCustomPostTagMutations) {
            return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
        return match ($fieldName) {
            $this->getSetTagsFieldName() => SchemaTypeModifiers::NON_NULLABLE,
            $this->getSetTagsFieldName() . 'MutationPayloadObjects' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            $this->getSetTagsFieldName() => [
                'input' => $this->getCustomPostSetTagsInputObjectTypeResolver(),
            ],
            $this->getSetTagsFieldName() . 'MutationPayloadObjects' => [
                SchemaCommonsMutationInputProperties::INPUT => $this->getMutationPayloadObjectsInputObjectTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            [$this->getSetTagsFieldName() => 'input'],
            [$this->getSetTagsFieldName() . 'MutationPayloadObjects' => SchemaCommonsMutationInputProperties::INPUT]
                => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldMutationResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?MutationResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostTagMutations = $moduleConfiguration->usePayloadableCustomPostTagMutations();
        return match ($fieldName) {
            $this->getSetTagsFieldName() => $usePayloadableCustomPostTagMutations
                ? $this->getPayloadableSetTagsMutationResolver()
                : $this->getSetTagsMutationResolver(),
            default => parent::getFieldMutationResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostTagMutations = $moduleConfiguration->usePayloadableCustomPostTagMutations();
        if ($usePayloadableCustomPostTagMutations) {
            return match ($fieldName) {
                $this->getSetTagsFieldName() => $this->getRootSetTagsMutationPayloadObjectTypeResolver(),
                default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
            };
        }
        return match ($fieldName) {
            $this->getSetTagsFieldName() => $this->getCustomPostObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    abstract protected function getRootSetTagsMutationPayloadObjectTypeResolver(): ConcreteTypeResolverInterface;
}
