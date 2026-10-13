<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use PoP\ComponentModel\Enums\OutputValueNullabilityViolations;

class OutputValueNullabilityServiceTest extends TestCase
{
    /**
     * @return array<string,array{0:int,1:mixed,2:OutputValueNullabilityViolations|null}>
     */
    public static function nullabilityViolationProvider(): array
    {
        $nonNull = SchemaTypeModifiers::NON_NULLABLE;
        $list = SchemaTypeModifiers::IS_ARRAY;
        $nonNullItems = SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
        $listOfLists = SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS;
        $listOfListsNonNullItems = SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS;
        return [
            'String: null' => [SchemaTypeModifiers::NONE, null, null],
            'String!: null' => [$nonNull, null, OutputValueNullabilityViolations::NULL_VALUE],
            'String!: value' => [$nonNull, 'hello', null],
            '[String]: null item' => [$list, ['a', null], null],
            '[String!]: null item' => [$nonNullItems, ['a', null], OutputValueNullabilityViolations::NULL_ARRAY_ITEM],
            '[String!]: no null item' => [$nonNullItems, ['a', 'b'], null],
            '[String!]: null' => [$nonNullItems, null, null],
            '[String!]!: null' => [$nonNullItems | $nonNull, null, OutputValueNullabilityViolations::NULL_VALUE],
            '[String!]: empty string is not null' => [$nonNullItems, ['', '0'], null],
            'items modifier without list modifier is ignored' => [SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY, ['a', null], null],
            '[[String]]: null inner item' => [$listOfLists, [['a', null]], null],
            '[[String!]]: null inner item' => [$listOfListsNonNullItems, [['a'], ['b', null]], OutputValueNullabilityViolations::NULL_ARRAY_OF_ARRAYS_ITEM],
            '[[String!]]: null inner list' => [$listOfListsNonNullItems, [['a'], null], null],
            '[[String!]!]: null inner list' => [$listOfListsNonNullItems | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY, [['a'], null], OutputValueNullabilityViolations::NULL_ARRAY_ITEM],
        ];
    }

    #[DataProvider('nullabilityViolationProvider')]
    public function testGetNullabilityViolation(
        int $fieldTypeModifiers,
        mixed $value,
        ?OutputValueNullabilityViolations $expectedNullabilityViolation,
    ): void {
        $outputValueNullabilityService = new OutputValueNullabilityService();
        $this->assertSame(
            $expectedNullabilityViolation,
            $outputValueNullabilityService->getNullabilityViolation($fieldTypeModifiers, $value)
        );
    }

    public function testNullabilityFlags(): void
    {
        $outputValueNullabilityService = new OutputValueNullabilityService();
        $this->assertTrue($outputValueNullabilityService->isNonNullable(SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY));
        $this->assertFalse($outputValueNullabilityService->isNonNullable(SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY));
        $this->assertTrue($outputValueNullabilityService->hasNonNullableArrayItems(SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY));
        $this->assertTrue($outputValueNullabilityService->hasNonNullableArrayItems(SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY));
        $this->assertFalse($outputValueNullabilityService->hasNonNullableArrayItems(SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY));
        $this->assertTrue($outputValueNullabilityService->hasNonNullableArrayOfArraysItems(SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS));
        $this->assertFalse($outputValueNullabilityService->hasNonNullableArrayOfArraysItems(SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS));
    }
}
