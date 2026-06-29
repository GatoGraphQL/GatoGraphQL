<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\EnumType;

use PoPSchema\SchemaCommons\Enums\OperationStatusEnum;
use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;

class OperationStatusEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'OperationStatusEnum';
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            OperationStatusEnum::SUCCESS,
            OperationStatusEnum::FAILURE,
        ];
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            OperationStatusEnum::SUCCESS => $this->__('Success', 'gatographql'),
            OperationStatusEnum::FAILURE => $this->__('Failure', 'gatographql'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
