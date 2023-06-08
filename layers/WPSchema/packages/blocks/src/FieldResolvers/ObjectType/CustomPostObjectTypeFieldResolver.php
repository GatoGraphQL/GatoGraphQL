<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\FieldResolvers\ObjectType;

use GatoGraphQL\GatoGraphQL\App;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPWPSchema\BlockContentParser\BlockContentParserInterface;
use PoPWPSchema\BlockContentParser\Exception\BlockContentParserException;
use PoPWPSchema\Blocks\Constants\HookNames;
use PoPWPSchema\Blocks\ObjectModels\BlockInterface;
use PoPWPSchema\Blocks\ObjectModels\GeneralBlock;
use PoPWPSchema\Blocks\TypeHelpers\BlockUnionTypeHelpers;
use PoPWPSchema\Blocks\TypeResolvers\InputObjectType\BlockFilterByInputObjectTypeResolver;
use PoP\ComponentModel\FeedbackItemProviders\GenericFeedbackItemProvider;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\FeedbackItemProviders\ErrorFeedbackItemProvider as EngineErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use WP_Post;
use stdClass;

use function serialize_block;

class CustomPostObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?BlockContentParserInterface $blockContentParser = null;
    private ?BlockFilterByInputObjectTypeResolver $blockFilterByInputObjectTypeResolver = null;

    final public function setBlockContentParser(BlockContentParserInterface $blockContentParser): void
    {
        $this->blockContentParser = $blockContentParser;
    }
    final protected function getBlockContentParser(): BlockContentParserInterface
    {
        /** @var BlockContentParserInterface */
        return $this->blockContentParser ??= $this->instanceManager->getInstance(BlockContentParserInterface::class);
    }
    final public function setBlockFilterByInputObjectTypeResolver(BlockFilterByInputObjectTypeResolver $blockFilterByInputObjectTypeResolver): void
    {
        $this->blockFilterByInputObjectTypeResolver = $blockFilterByInputObjectTypeResolver;
    }
    final protected function getBlockFilterByInputObjectTypeResolver(): BlockFilterByInputObjectTypeResolver
    {
        /** @var BlockFilterByInputObjectTypeResolver */
        return $this->blockFilterByInputObjectTypeResolver ??= $this->instanceManager->getInstance(BlockFilterByInputObjectTypeResolver::class);
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'blocks',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'blocks' => $this->__('(Gutenberg) Blocks in a custom post', 'blocks'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'blocks' => BlockUnionTypeHelpers::getBlockUnionOrTargetObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'blocks' => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
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
            'blocks' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filterBy' => $this->getBlockFilterByInputObjectTypeResolver(),
                ]
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
        /** @var WP_Post */
        $customPost = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'blocks':
                /** @var stdClass|null */
                $filterBy = $fieldDataAccessor->getValue('filterBy');
                $filterOptions = [];
                if (isset($filterBy->include)) {
                    $filterOptions['include'] = $filterBy->include;
                } elseif (isset($filterBy->exclude)) {
                    $filterOptions['exclude'] = $filterBy->exclude;
                }

                $blockContentParserPayload = null;
                try {
                    $blockContentParserPayload = $this->getBlockContentParser()->parseCustomPostIntoBlockDataItems($customPost, $filterOptions);
                } catch (BlockContentParserException $e) {
                    $objectTypeFieldResolutionFeedbackStore->addError(
                        new ObjectTypeFieldResolutionFeedback(
                            new FeedbackItemResolution(
                                EngineErrorFeedbackItemProvider::class,
                                EngineErrorFeedbackItemProvider::E7,
                                [
                                    $e->getMessage(),
                                ]
                            ),
                            $fieldDataAccessor->getField(),
                        )
                    );
                    return null;
                }

                if ($blockContentParserPayload === null) {
                    return $blockContentParserPayload;
                }

                if ($blockContentParserPayload->warnings !== null) {
                    foreach ($blockContentParserPayload->warnings as $warning) {
                        $objectTypeFieldResolutionFeedbackStore->addWarning(
                            new ObjectTypeFieldResolutionFeedback(
                                new FeedbackItemResolution(
                                    GenericFeedbackItemProvider::class,
                                    GenericFeedbackItemProvider::W1,
                                    [
                                        $warning,
                                    ]
                                ),
                                $fieldDataAccessor->getField(),
                            )
                        );
                    }
                }

                /** @var BlockInterface[] */
                $blocks = array_map(
                    $this->createBlock(...),
                    $blockContentParserPayload->blocks
                );
                return array_map(
                    fn (BlockInterface $block) => $block->getID(),
                    $blocks
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * Given the name, attributes, and inner block data for a block,
     * create a Block object.
     */
    protected function createBlock(stdClass $blockItem): BlockInterface
    {
        /** @var string */
        $name = $blockItem->name;
        /** @var stdClass|null */
        $attributes = $blockItem->attributes ?? null;
        /** @var array<string|null> */
        $innerContent = $blockItem->innerContent;

        /** @var BlockInterface[]|null */
        $innerBlocks = null;
        if (isset($blockItem->innerBlocks)) {
            /** @var array<stdClass> */
            $blockInnerBlocks = $blockItem->innerBlocks;
            $innerBlocks = array_map(
                $this->createBlock(...),
                $blockInnerBlocks
            );
        }

        /**
         * Regenerate the original content source.
         *
         * Please notice that it will not be exactly the same!
         * Because:
         *
         * - the default attributes should not be included,
         *   but they are
         * - attributes stored inside the innerHTML are also
         *   stored within the attributes
         *
         * A better solution is to retrieve the HTML content as is
         * already when parsing the blocks in class
         * `WP_Block_Parser_Block` (but this is currently not supported!)
         *
         * @see wp-includes/class-wp-block-parser.php
         *
         * @todo If `WP_Block_Parser_Block` ever retrieves the original HTML source, then improve this solution
         *
         * @see https://github.com/leoloso/PoP/issues/2346
         */
        $contentSource = serialize_block($this->getSerializeBlockData($blockItem));

        return $this->createBlockObject(
            $name,
            $attributes,
            $innerBlocks,
            $innerContent,
            $contentSource,
        );
    }

    /**
     * Retrieve the properties that, passed to `serialize_block`,
     * recreates the Block HTML.
     *
     * @return array<string,mixed>
     */
    protected function getSerializeBlockData(stdClass $blockItem): array
    {
        /** @var string */
        $name = $blockItem->name;
        /** @var stdClass|null */
        $attributes = $blockItem->attributes ?? null;
        /** @var array<string|null> */
        $innerContent = $blockItem->innerContent;

        $serializeBlockData = [
            'blockName' => $name,
            'attrs' => $attributes !== null ? (array) $attributes : [],
            'innerContent' => $innerContent,
        ];

        if (isset($blockItem->innerBlocks)) {
            /** @var array<stdClass> */
            $blockInnerBlocks = $blockItem->innerBlocks;
            $serializeBlockData['innerBlocks'] = array_map(
                $this->getSerializeBlockData(...),
                $blockInnerBlocks
            );
        }
        return $serializeBlockData;
    }

    /**
     * Allow to inject more specific blocks:
     *
     * - CoreParagraphBlock
     * - CoreMediaBlock
     * - CoreHeadingBlock
     * - etc
     *
     * By default, it creates a `GeneralBlock`.
     *
     * @param array<string|null> $innerContent
     * @param BlockInterface[]|null $innerBlocks
     */
    protected function createBlockObject(
        string $name,
        ?stdClass $attributes,
        ?array $innerBlocks,
        array $innerContent,
        string $contentSource,
    ): BlockInterface {
        /** @var BlockInterface|null */
        $injectedBlockObject = App::applyFilters(
            HookNames::BLOCK_TYPE,
            null,
            $name,
            $attributes,
            $innerBlocks,
            $innerContent,
            $contentSource,
        );
        if ($injectedBlockObject !== null) {
            return $injectedBlockObject;
        }
        return new GeneralBlock(
            $name,
            $attributes,
            $innerBlocks,
            $innerContent,
            $contentSource,
        );
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
