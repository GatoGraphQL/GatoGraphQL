<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

use DateTime;
use DateTimeImmutable;
use PoP\ComponentModel\AbstractTestCase;
use stdClass;

/**
 * The value to serialize is not necessarily a DateTime: a meta directive
 * can override the value of the field while keeping its type (as done by
 * `@underDynamicVariable` or `@applyField(setResultInResponse: true)`).
 *
 * Then serializing must not assume the value implements DateTimeInterface.
 */
class DateTimeScalarTypeResolverSerializationTest extends AbstractTestCase
{
    private function getDateTimeScalarTypeResolver(): DateTimeScalarTypeResolver
    {
        /** @var DateTimeScalarTypeResolver */
        return $this->getService(DateTimeScalarTypeResolver::class);
    }

    private function getDateScalarTypeResolver(): DateScalarTypeResolver
    {
        /** @var DateScalarTypeResolver */
        return $this->getService(DateScalarTypeResolver::class);
    }

    public function testSerializeDateTimeValue(): void
    {
        $dateTime = new DateTime('2018-10-20T20:03:48+00:00');

        $this->assertSame(
            '2018-10-20T20:03:48+00:00',
            $this->getDateTimeScalarTypeResolver()->serialize($dateTime)
        );
        $this->assertSame(
            '2018-10-20',
            $this->getDateScalarTypeResolver()->serialize($dateTime)
        );
    }

    public function testSerializeDateTimeImmutableValue(): void
    {
        $this->assertSame(
            '2018-10-20T20:03:48+00:00',
            $this->getDateTimeScalarTypeResolver()->serialize(
                new DateTimeImmutable('2018-10-20T20:03:48+00:00')
            )
        );
    }

    public function testSerializeStringValue(): void
    {
        $this->assertSame(
            'not a date',
            $this->getDateTimeScalarTypeResolver()->serialize('not a date')
        );
    }

    public function testSerializeEmptyStringValue(): void
    {
        $this->assertSame(
            '',
            $this->getDateTimeScalarTypeResolver()->serialize('')
        );
    }

    public function testSerializeIntValue(): void
    {
        $this->assertSame(
            10,
            $this->getDateTimeScalarTypeResolver()->serialize(10)
        );
    }

    public function testSerializeFloatValue(): void
    {
        $this->assertSame(
            1.5,
            $this->getDateTimeScalarTypeResolver()->serialize(1.5)
        );
    }

    public function testSerializeBoolValue(): void
    {
        $this->assertSame(
            true,
            $this->getDateTimeScalarTypeResolver()->serialize(true)
        );
        $this->assertSame(
            false,
            $this->getDateTimeScalarTypeResolver()->serialize(false)
        );
    }

    public function testSerializeStdClassValue(): void
    {
        $stdClass = new stdClass();
        $stdClass->greeting = 'hello';
        $stdClass->target = 'world';

        $serializedValue = $this->getDateTimeScalarTypeResolver()->serialize($stdClass);

        $this->assertEquals($stdClass, $serializedValue);
    }

    public function testSerializeStdClassValueContainingADateTime(): void
    {
        $stdClass = new stdClass();
        $stdClass->when = new DateTime('2018-10-20T20:03:48+00:00');

        $expectedValue = new stdClass();
        $expectedValue->when = '2018-10-20T20:03:48+00:00';

        $this->assertEquals(
            $expectedValue,
            $this->getDateTimeScalarTypeResolver()->serialize($stdClass)
        );
    }

    /**
     * The `Date` scalar formats a DateTime differently than `DateTime`
     * does, but both must leave a non-DateTime value untouched.
     */
    public function testSerializeNonDateTimeValueOnEveryDateTimeScalar(): void
    {
        foreach ([$this->getDateTimeScalarTypeResolver(), $this->getDateScalarTypeResolver()] as $scalarTypeResolver) {
            $this->assertSame(
                'not a date',
                $scalarTypeResolver->serialize('not a date')
            );
        }
    }

    public function testIsAlreadyCoercedValue(): void
    {
        $dateTimeScalarTypeResolver = $this->getDateTimeScalarTypeResolver();

        $this->assertTrue($dateTimeScalarTypeResolver->isAlreadyCoercedValue(new DateTime()));
        $this->assertTrue($dateTimeScalarTypeResolver->isAlreadyCoercedValue(new DateTimeImmutable()));
        $this->assertFalse($dateTimeScalarTypeResolver->isAlreadyCoercedValue(new stdClass()));
    }
}
