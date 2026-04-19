<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;
use PoPWPSchema\Blocks\Module;
use PoPWPSchema\Blocks\ModuleConfiguration;
use PoPWPSchema\Blocks\ObjectModels\BlockType;
use PoPWPSchema\Blocks\TypeAPIs\BlockTypeTypeAPIInterface;
use PoPWPSchema\Blocks\TypeResolvers\InputObjectType\RootBlockTypesFilterInputObjectTypeResolver;
use PoPWPSchema\Blocks\TypeResolvers\ObjectType\BlockTypeObjectTypeResolver;

class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?BlockTypeObjectTypeResolver $blockTypeObjectTypeResolver = null;
    private ?RootBlockTypesFilterInputObjectTypeResolver $rootBlockTypesFilterInputObjectTypeResolver = null;
    private ?BlockTypeTypeAPIInterface $blockTypeTypeAPI = null;

    final protected function getBlockTypeObjectTypeResolver(): BlockTypeObjectTypeResolver
    {
        if ($this->blockTypeObjectTypeResolver === null) {
            /** @var BlockTypeObjectTypeResolver */
            $blockTypeObjectTypeResolver = $this->instanceManager->getInstance(BlockTypeObjectTypeResolver::class);
            $this->blockTypeObjectTypeResolver = $blockTypeObjectTypeResolver;
        }
        return $this->blockTypeObjectTypeResolver;
    }
    final protected function getRootBlockTypesFilterInputObjectTypeResolver(): RootBlockTypesFilterInputObjectTypeResolver
    {
        if ($this->rootBlockTypesFilterInputObjectTypeResolver === null) {
            /** @var RootBlockTypesFilterInputObjectTypeResolver */
            $rootBlockTypesFilterInputObjectTypeResolver = $this->instanceManager->getInstance(RootBlockTypesFilterInputObjectTypeResolver::class);
            $this->rootBlockTypesFilterInputObjectTypeResolver = $rootBlockTypesFilterInputObjectTypeResolver;
        }
        return $this->rootBlockTypesFilterInputObjectTypeResolver;
    }
    final protected function getBlockTypeTypeAPI(): BlockTypeTypeAPIInterface
    {
        if ($this->blockTypeTypeAPI === null) {
            /** @var BlockTypeTypeAPIInterface */
            $blockTypeTypeAPI = $this->instanceManager->getInstance(BlockTypeTypeAPIInterface::class);
            $this->blockTypeTypeAPI = $blockTypeTypeAPI;
        }
        return $this->blockTypeTypeAPI;
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
            'blockTypes',
        ];
    }

    /**
     * @return string[]
     */
    public function getSensitiveFieldNames(): array
    {
        $sensitiveFieldNames = parent::getSensitiveFieldNames();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->treatBlockTypesAsSensitiveData()) {
            $sensitiveFieldNames[] = 'blockTypes';
        }
        return $sensitiveFieldNames;
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'blockTypes' => $this->__('All registered block types in the WordPress block registry', 'blocks'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'blockTypes' => $this->getBlockTypeObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'blockTypes' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        return match ($fieldName) {
            'blockTypes' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootBlockTypesFilterInputObjectTypeResolver(),
                ],
            ),
            default => $fieldArgNameTypeResolvers,
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        switch ($fieldDataAccessor->getFieldName()) {
            case 'blockTypes':
                $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor);
                $blockTypes = $this->getBlockTypeTypeAPI()->getBlockTypes($query);
                /**
                 * BlockType is a Transient Object: instances are auto-registered
                 * in the Object Dictionary upon construction (done by the TypeAPI),
                 * so here we only return their IDs.
                 */
                return array_map(
                    static fn (BlockType $blockType): string|int => $blockType->getID(),
                    $blockTypes,
                );
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
