<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPSchema\SchemaCommons\Constants\Order;

class OrderEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'Order';
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
            Order::ASC => $this->getTranslationAPI()->__('Ascending order', 'schema-commons'),
            Order::DESC => $this->getTranslationAPI()->__('Descending order', 'schema-commons'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
