<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\FieldResolvers\ObjectType;

use PoPWPSchema\Blocks\FieldResolvers\InterfaceType\BlockInterfaceTypeFieldResolver;
use PoPWPSchema\Blocks\ObjectModels\BlockInterface;
use PoPWPSchema\Blocks\TypeResolvers\ObjectType\AbstractBlockObjectTypeResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class BlockObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?BlockInterfaceTypeFieldResolver $blockInterfaceTypeFieldResolver = null;

    final protected function getBlockInterfaceTypeFieldResolver(): BlockInterfaceTypeFieldResolver
    {
        if ($this->blockInterfaceTypeFieldResolver === null) {
            /** @var BlockInterfaceTypeFieldResolver */
            $blockInterfaceTypeFieldResolver = $this->instanceManager->getInstance(BlockInterfaceTypeFieldResolver::class);
            $this->blockInterfaceTypeFieldResolver = $blockInterfaceTypeFieldResolver;
        }
        return $this->blockInterfaceTypeFieldResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractBlockObjectTypeResolver::class,
        ];
    }

    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->getBlockInterfaceTypeFieldResolver(),
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'name',
            'attributes',
            'innerBlocks',
            // 'innerHTML',
            'contentSource',
        ];
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        /** @var BlockInterface */
        $block = $object;
        $fieldName = $fieldDataAccessor->getFieldName();
        switch ($fieldName) {
            case 'innerBlocks':
                $innerBlocks = $block->getInnerBlocks();
                if ($innerBlocks === null) {
                    return null;
                }
                return array_map(
                    fn (BlockInterface $block) => $block->getID(),
                    $innerBlocks
                );
            case 'contentSource':
                return $block->getContentSource();
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
