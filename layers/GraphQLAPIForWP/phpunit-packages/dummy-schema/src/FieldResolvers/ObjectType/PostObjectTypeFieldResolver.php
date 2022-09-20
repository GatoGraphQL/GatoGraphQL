<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\DummySchema\FieldResolvers\ObjectType;

use DateTime;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateTimeScalarTypeResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use WP_Post;

class PostObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?DateTimeScalarTypeResolver $dateTimeScalarTypeResolver = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setDateTimeScalarTypeResolver(DateTimeScalarTypeResolver $dateTimeScalarTypeResolver): void
    {
        $this->dateTimeScalarTypeResolver = $dateTimeScalarTypeResolver;
    }
    final protected function getDateTimeScalarTypeResolver(): DateTimeScalarTypeResolver
    {
        /** @var DateTimeScalarTypeResolver */
        return $this->dateTimeScalarTypeResolver ??= $this->instanceManager->getInstance(DateTimeScalarTypeResolver::class);
    }
    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        /** @var IntScalarTypeResolver */
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        /** @var StringScalarTypeResolver */
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'dummyListOfInts',
            'dummyListOfListsOfInts',
            'dummyListOfStrings',
            'dummyListOfListsOfStrings',
            'dummyListOfDates',
            'dummyListOfListsOfDates',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'dummyListOfInts' => $this->__('Dummy field that returns a list of integers: [Int]', 'dummy-schema'),
            'dummyListOfListsOfInts' => $this->__('Dummy field that returns a list of lists of integers: [[Int]]', 'dummy-schema'),
            'dummyListOfStrings' => $this->__('Dummy field that returns a list of strings: [String]', 'dummy-schema'),
            'dummyListOfListsOfStrings' => $this->__('Dummy field that returns a list of lists of strings: [[String]]', 'dummy-schema'),
            'dummyListOfDates' => $this->__('Dummy field that returns a list of dates: [Date]', 'dummy-schema'),
            'dummyListOfListsOfDates' => $this->__('Dummy field that returns a list of lists of dates: [[Date]]', 'dummy-schema'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'dummyListOfInts',
            'dummyListOfListsOfInts'
                => $this->getIntScalarTypeResolver(),
            'dummyListOfStrings',
            'dummyListOfListsOfStrings'
                => $this->getStringScalarTypeResolver(),
            'dummyListOfDates',
            'dummyListOfListsOfDates'
                => $this->getDateTimeScalarTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'dummyListOfInts',
            'dummyListOfStrings',
            'dummyListOfDates'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            'dummyListOfListsOfInts',
            'dummyListOfListsOfStrings',
            'dummyListOfListsOfDates'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        /** @var WP_Post */
        $post = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'dummyListOfInts':
                return [
                    $post->comment_count + 1,
                    $post->comment_count,
                    $post->comment_count + 3,
                ];
            case 'dummyListOfListsOfInts':
                return [
                    [
                        $post->comment_count,
                        $post->comment_count + 1,
                        $post->comment_count + 3,
                    ],
                    [
                        $post->comment_count + 7,
                        $post->comment_count + 13,
                    ],
                    [
                        $post->comment_count + 33,
                    ],
                ];
            case 'dummyListOfStrings':
                return [
                    $post->post_title,
                    $post->post_excerpt,
                    $post->post_mime_type,
                ];
            case 'dummyListOfListsOfStrings':
                return [
                    [
                        $post->post_title,
                        $post->post_excerpt,
                        $post->post_mime_type,
                    ],
                    [
                        $post->post_name,
                        $post->post_type,
                    ],
                    [
                        $post->post_content,
                    ],
                ];
            case 'dummyListOfDates':
                return [
                    new DateTime($post->post_date),
                    new DateTime(date('Y-m-d H:i:s', strtotime($post->post_date . ' +1 day'))),
                    new DateTime(date('Y-m-d H:i:s', strtotime($post->post_date . ' +7 day'))),
                ];
            case 'dummyListOfListsOfDates':
                return [
                    [
                        new DateTime($post->post_date),
                        new DateTime(date('Y-m-d H:i:s', strtotime($post->post_date . ' +1 day'))),
                        new DateTime(date('Y-m-d H:i:s', strtotime($post->post_date . ' +7 day'))),
                    ],
                    [
                        new DateTime(date('Y-m-d H:i:s', strtotime($post->post_date . ' +9 day'))),
                        new DateTime(date('Y-m-d H:i:s', strtotime($post->post_date . ' +28 day'))),
                    ],
                    [
                        new DateTime(date('Y-m-d H:i:s', strtotime($post->post_date . ' +66 day'))),
                    ],
                ];
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
