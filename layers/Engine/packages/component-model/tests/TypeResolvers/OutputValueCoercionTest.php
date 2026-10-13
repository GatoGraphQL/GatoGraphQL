<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use PHPUnit\Framework\Attributes\DataProvider;
use PoP\ComponentModel\AbstractTestCase;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\TypeResolvers\EnumType\TestBackedEnum;
use PoP\ComponentModel\TypeResolvers\EnumType\TestBackedEnumTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\FloatScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Module\ModuleInterface;
use stdClass;

use const INF;
use const NAN;

/**
 * @see https://spec.graphql.org/draft/#sec-Scalars.Result-Coercion-and-Serialization
 */
class OutputValueCoercionTest extends AbstractTestCase
{
    /**
     * @return class-string<ModuleInterface>
     */
    protected static function getModuleClass(): string
    {
        return Module::class;
    }

    /**
     * @param class-string<OutputValueCoercingLeafTypeResolverInterface> $typeResolverClass
     */
    private function createTypeResolver(string $typeResolverClass): OutputValueCoercingLeafTypeResolverInterface
    {
        /** @var OutputValueCoercingLeafTypeResolverInterface&AbstractTypeResolver */
        $typeResolver = new $typeResolverClass();
        $typeResolver->setInstanceManager(InstanceManagerFacade::getInstance());
        return $typeResolver;
    }

    /**
     * @return array<string,array{0:class-string<OutputValueCoercingLeafTypeResolverInterface>,1:string|int|float|bool|mixed[]|stdClass,2:string|int|float|bool|null}>
     */
    public static function coercionProvider(): array
    {
        return [
            'Int: int' => [IntScalarTypeResolver::class, 42, 42],
            'Int: negative int' => [IntScalarTypeResolver::class, -42, -42],
            'Int: integral float' => [IntScalarTypeResolver::class, 1.0, 1],
            'Int: fractional float' => [IntScalarTypeResolver::class, 1.2, null],
            'Int: numeric string' => [IntScalarTypeResolver::class, '123', 123],
            'Int: non-integral string' => [IntScalarTypeResolver::class, '1.5', null],
            'Int: non-numeric string' => [IntScalarTypeResolver::class, 'abc', null],
            'Int: bool' => [IntScalarTypeResolver::class, true, 1],
            'Int: max 32-bit' => [IntScalarTypeResolver::class, 2147483647, 2147483647],
            'Int: min 32-bit' => [IntScalarTypeResolver::class, -2147483648, -2147483648],
            'Int: above 32-bit' => [IntScalarTypeResolver::class, 2147483648, null],
            'Int: below 32-bit' => [IntScalarTypeResolver::class, -2147483649, null],
            'Int: infinity' => [IntScalarTypeResolver::class, INF, null],
            'Int: array' => [IntScalarTypeResolver::class, [1], null],
            'Float: float' => [FloatScalarTypeResolver::class, 1.5, 1.5],
            'Float: int' => [FloatScalarTypeResolver::class, 1, 1.0],
            'Float: numeric string' => [FloatScalarTypeResolver::class, '123', 123.0],
            'Float: non-numeric string' => [FloatScalarTypeResolver::class, 'abc', null],
            'Float: NaN' => [FloatScalarTypeResolver::class, NAN, null],
            'Float: infinity' => [FloatScalarTypeResolver::class, INF, null],
            'String: string' => [StringScalarTypeResolver::class, 'hello', 'hello'],
            'String: int' => [StringScalarTypeResolver::class, 1, '1'],
            'String: float' => [StringScalarTypeResolver::class, 1.5, '1.5'],
            'String: bool' => [StringScalarTypeResolver::class, true, 'true'],
            'String: array' => [StringScalarTypeResolver::class, ['a'], null],
            'String: object' => [StringScalarTypeResolver::class, new stdClass(), null],
            'Boolean: bool' => [BooleanScalarTypeResolver::class, false, false],
            'Boolean: non-zero number' => [BooleanScalarTypeResolver::class, 3, true],
            'Boolean: zero' => [BooleanScalarTypeResolver::class, 0, false],
            'Boolean: "1"' => [BooleanScalarTypeResolver::class, '1', true],
            'Boolean: ""' => [BooleanScalarTypeResolver::class, '', false],
            'Boolean: "true"' => [BooleanScalarTypeResolver::class, 'true', true],
            'Boolean: other string' => [BooleanScalarTypeResolver::class, 'yes', null],
            'ID: string' => [IDScalarTypeResolver::class, 'abc', 'abc'],
            'ID: int' => [IDScalarTypeResolver::class, 4, '4'],
            'ID: float' => [IDScalarTypeResolver::class, 4.0, null],
            'ID: bool' => [IDScalarTypeResolver::class, true, null],
            'Enum: valid value' => [TestBackedEnumTypeResolver::class, 'FIRST', 'FIRST'],
            'Enum: invalid value' => [TestBackedEnumTypeResolver::class, 'THIRD', null],
            'Enum: non-string value' => [TestBackedEnumTypeResolver::class, 1.5, null],
        ];
    }

    /**
     * @param class-string<OutputValueCoercingLeafTypeResolverInterface> $typeResolverClass
     * @param string|int|float|bool|mixed[]|stdClass $serializedValue
     */
    #[DataProvider('coercionProvider')]
    public function testCoerceOutputValue(
        string $typeResolverClass,
        string|int|float|bool|array|stdClass $serializedValue,
        string|int|float|bool|null $expectedCoercedValue,
    ): void {
        $typeResolver = $this->createTypeResolver($typeResolverClass);
        $this->assertSame(
            $expectedCoercedValue,
            $typeResolver->coerceOutputValue($serializedValue)
        );
    }

    public function testEnumCoercesTheSerializedBackedEnumCase(): void
    {
        $typeResolver = $this->createTypeResolver(TestBackedEnumTypeResolver::class);
        $serializedValue = $typeResolver->serialize(TestBackedEnum::SECOND);
        $this->assertSame('SECOND', $typeResolver->coerceOutputValue($serializedValue));
    }
}
