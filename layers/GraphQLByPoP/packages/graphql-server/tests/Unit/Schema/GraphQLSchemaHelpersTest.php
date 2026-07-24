<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Unit\Schema;

use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaHelpers;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class GraphQLSchemaHelpersTest extends TestCase
{
    #[DataProvider('provideValuesToEncodeUsingGraphQLLanguage')]
    public function testEncodeValueUsingGraphQLLanguage(
        string $expectedEncodedValue,
        mixed $value,
        bool $isEnumType,
    ): void {
        $this->assertEquals(
            $expectedEncodedValue,
            GraphQLSchemaHelpers::encodeValueUsingGraphQLLanguage($value, $isEnumType)
        );
    }

    /**
     * @return array<array{0:string,1:mixed,2:bool}>
     */
    public static function provideValuesToEncodeUsingGraphQLLanguage(): array
    {
        return [
            ['96', 96, false],
            ['-1', -1, false],
            ['1.5', 1.5, false],
            ['true', true, false],
            ['false', false, false],
            ['"F j, Y"', 'F j, Y', false],
            ['["post", "page"]', ['post', 'page'], false],
            ['[]', [], false],
            ['approve', 'approve', true],
            ['[approve]', ['approve'], true],
            ['[approve, hold]', ['approve', 'hold'], true],
            ['{key: 1, anotherKey: "value"}', ['key' => 1, 'anotherKey' => 'value'], false],
            ['[{key: 1}]', [['key' => 1]], false],
        ];
    }
}
