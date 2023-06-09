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

    final public function setBlockInterfaceTypeFieldResolver(BlockInterfaceTypeFieldResolver $blockInterfaceTypeFieldResolver): void
    {
        $this->blockInterfaceTypeFieldResolver = $blockInterfaceTypeFieldResolver;
    }
    final protected function getBlockInterfaceTypeFieldResolver(): BlockInterfaceTypeFieldResolver
    {
        /** @var BlockInterfaceTypeFieldResolver */
        return $this->blockInterfaceTypeFieldResolver ??= $this->instanceManager->getInstance(BlockInterfaceTypeFieldResolver::class);
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
            case 'attributes':
                /**
                 * Return a clone to the stdClass object, and not the
                 * object directly, because applying a directive would
                 * modify this object also in its source, and then the
                 * modification will appear even when not requested.
                 *
                 * Eg: only `transformedAttributes` must be modified,
                 * but not `originalAttributes`:
                 * 
                 *   {
                 *     post(by: { id: 19 }) {
                 *       blocks(
                 *         filterBy: {
                 *           include: "core/heading"
                 *         }
                 *       ) {
                 *         originalAttributes: attributes
                 *         transformedAttributes: attributes
                 *           @underJSONObjectProperty(by: { key: "content" })
                 *             @strUpperCase      
                 *       }
                 *     }
                 *   }
                 * 
                 * @see layers/GatoGraphQLForWP/phpunit-packages/gato-graphql-pro/tests/Integration/fixture-directives/success/directive-on-parallel-field-does-not-override-original-value.gql
                 */
                if ($block->getAttributes() === null) {
                    return null;
                }
                return clone $block->getAttributes();
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
