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
        return array_map(
            fn (Order $order) => $order->value,
            Order::cases()
        );
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            Order::Asc => $this->__('Ascending order', 'schema-commons'),
            Order::Desc => $this->__('Descending order', 'schema-commons'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
