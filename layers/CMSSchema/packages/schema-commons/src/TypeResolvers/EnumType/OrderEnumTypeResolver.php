<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPSchema\SchemaCommons\Constants\Order;

class OrderEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'OrderEnum';
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            Order::ASC,
            Order::DESC,
        ];
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            Order::ASC => $this->__('Ascending order', 'schema-commons'),
            Order::DESC => $this->__('Descending order', 'schema-commons'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
