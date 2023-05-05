<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\DummyWPSchema\FieldResolvers\ObjectType;

use DateTime;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateTimeScalarTypeResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\FloatScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;
use WP_Post;

class PostObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?DateTimeScalarTypeResolver $dateTimeScalarTypeResolver = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?FloatScalarTypeResolver $floatScalarTypeResolver = null;
    private ?JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver = null;

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
    final public function setFloatScalarTypeResolver(FloatScalarTypeResolver $floatScalarTypeResolver): void
    {
        $this->floatScalarTypeResolver = $floatScalarTypeResolver;
    }
    final protected function getFloatScalarTypeResolver(): FloatScalarTypeResolver
    {
        /** @var FloatScalarTypeResolver */
        return $this->floatScalarTypeResolver ??= $this->instanceManager->getInstance(FloatScalarTypeResolver::class);
    }
    final public function setJSONObjectScalarTypeResolver(JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver): void
    {
        $this->jsonObjectScalarTypeResolver = $jsonObjectScalarTypeResolver;
    }
    final protected function getJSONObjectScalarTypeResolver(): JSONObjectScalarTypeResolver
    {
        /** @var JSONObjectScalarTypeResolver */
        return $this->jsonObjectScalarTypeResolver ??= $this->instanceManager->getInstance(JSONObjectScalarTypeResolver::class);
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
            'dummyListOfFloats',
            'dummyListOfListsOfFloats',
            'dummyListOfStrings',
            'dummyListOfListsOfStrings',
            'dummyListOfDates',
            'dummyListOfListsOfDates',
            'dummyJSONObject',
            'dummyListOfJSONObjects',
            'dummyListOfListsOfJSONObjects',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'dummyListOfInts' => $this->__('Dummy field that returns a list of integers: [Int]', 'dummy-wp-schema'),
            'dummyListOfListsOfInts' => $this->__('Dummy field that returns a list of lists of integers: [[Int]]', 'dummy-wp-schema'),
            'dummyListOfFloats' => $this->__('Dummy field that returns a list of floats: [Float]', 'dummy-wp-schema'),
            'dummyListOfListsOfFloats' => $this->__('Dummy field that returns a list of lists of floats: [[Float]]', 'dummy-wp-schema'),
            'dummyListOfStrings' => $this->__('Dummy field that returns a list of strings: [String]', 'dummy-wp-schema'),
            'dummyListOfListsOfStrings' => $this->__('Dummy field that returns a list of lists of strings: [[String]]', 'dummy-wp-schema'),
            'dummyListOfDates' => $this->__('Dummy field that returns a list of dates: [Date]', 'dummy-wp-schema'),
            'dummyListOfListsOfDates' => $this->__('Dummy field that returns a list of lists of dates: [[Date]]', 'dummy-wp-schema'),
            'dummyJSONObject' => $this->__('Dummy field that returns a JSON object: JSONObject', 'dummy-wp-schema'),
            'dummyListOfJSONObjects' => $this->__('Dummy field that returns a list of JSON objects: [JSONObject]', 'dummy-wp-schema'),
            'dummyListOfListsOfJSONObjects' => $this->__('Dummy field that returns a list of lists of JSON objects: [[JSONObject]]', 'dummy-wp-schema'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'dummyListOfInts',
            'dummyListOfListsOfInts'
                => $this->getIntScalarTypeResolver(),
            'dummyListOfFloats',
            'dummyListOfListsOfFloats'
                => $this->getFloatScalarTypeResolver(),
            'dummyListOfStrings',
            'dummyListOfListsOfStrings'
                => $this->getStringScalarTypeResolver(),
            'dummyListOfDates',
            'dummyListOfListsOfDates'
                => $this->getDateTimeScalarTypeResolver(),
            'dummyJSONObject',
            'dummyListOfJSONObjects',
            'dummyListOfListsOfJSONObjects'
                => $this->getJSONObjectScalarTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'dummyJSONObject'
                => SchemaTypeModifiers::NON_NULLABLE,
            'dummyListOfInts',
            'dummyListOfFloats',
            'dummyListOfStrings',
            'dummyListOfDates',
            'dummyListOfJSONObjects'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            'dummyListOfListsOfInts',
            'dummyListOfListsOfFloats',
            'dummyListOfListsOfStrings',
            'dummyListOfListsOfDates',
            'dummyListOfListsOfJSONObjects'
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
        $fieldName = $fieldDataAccessor->getFieldName();
        switch ($fieldName) {
            case 'dummyListOfInts':
                return [
                    (int)$post->comment_count + 1,
                    (int)$post->comment_count,
                    (int)$post->comment_count + 3,
                ];
            case 'dummyListOfListsOfInts':
                return [
                    [
                        (int)$post->comment_count + 1,
                        (int)$post->comment_count,
                        (int)$post->comment_count + 3,
                    ],
                    [
                        (int)$post->comment_count + 7,
                        (int)$post->comment_count + 13,
                    ],
                    [
                        (int)$post->comment_count + 33,
                    ],
                ];
            case 'dummyListOfFloats':
                return [
                    (float)$post->comment_count + 1.5,
                    (float)$post->comment_count + 0.5,
                    (float)$post->comment_count + 3.5,
                ];
            case 'dummyListOfListsOfFloats':
                return [
                    [
                        (float)$post->comment_count + 1.5,
                        (float)$post->comment_count + 0.5,
                        (float)$post->comment_count + 3.5,
                    ],
                    [
                        (float)$post->comment_count + 7.5,
                        (float)$post->comment_count + 13.5,
                    ],
                    [
                        (float)$post->comment_count + 33.5,
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
                    new DateTime(date('Y-m-d H:i:s', (int)strtotime($post->post_date . ' +1 day'))),
                    new DateTime(date('Y-m-d H:i:s', (int)strtotime($post->post_date . ' +7 day'))),
                ];
            case 'dummyListOfListsOfDates':
                return [
                    [
                        new DateTime($post->post_date),
                        new DateTime(date('Y-m-d H:i:s', (int)strtotime($post->post_date . ' +1 day'))),
                        new DateTime(date('Y-m-d H:i:s', (int)strtotime($post->post_date . ' +7 day'))),
                    ],
                    [
                        new DateTime(date('Y-m-d H:i:s', (int)strtotime($post->post_date . ' +9 day'))),
                        new DateTime(date('Y-m-d H:i:s', (int)strtotime($post->post_date . ' +28 day'))),
                    ],
                    [
                        new DateTime(date('Y-m-d H:i:s', (int)strtotime($post->post_date . ' +66 day'))),
                    ],
                ];
            case 'dummyJSONObject':
                return (object) [
                    'title' => (object) [
                        'rendered' => $post->post_title,
                    ],
                    'date' => new DateTime($post->post_date),
                    'commentCount' => (int)$post->comment_count,
                ];
            case 'dummyListOfJSONObjects':
                return [
                    (object) [
                        'title' => (object) [
                            'rendered' => $post->post_title,
                        ],
                        'date' => new DateTime($post->post_date),
                        'commentCount' => (int)$post->comment_count,
                    ],
                    (object) [
                        'excerpt' => $post->post_excerpt,
                        'type' => $post->post_type,
                    ],
                    (object) [
                        'hasComments' => ((int)$post->comment_count > 0),
                    ],
                ];
            case 'dummyListOfListsOfJSONObjects':
                return [
                    [
                        (object) [
                            'title' => (object) [
                                'rendered' => $post->post_title,
                            ],
                            'date' => new DateTime($post->post_date),
                            'commentCount' => (int)$post->comment_count,
                        ],
                        (object) [
                            'excerpt' => $post->post_excerpt,
                            'type' => $post->post_type,
                        ],
                        (object) [
                            'hasComments' => ((int)$post->comment_count > 0),
                        ],
                    ],
                    [
                        (object) [
                            'mimeType' => $post->post_mime_type,
                            'content' => $post->post_content,
                        ],
                    ],
                ];
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
