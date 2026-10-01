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

    public function getBackedEnumClass(): ?string
    {
        return OperationStatusEnum::class;
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match (OperationStatusEnum::tryFrom($enumValue)) {
            OperationStatusEnum::SUCCESS => $this->__('Success', 'gatographql'),
            OperationStatusEnum::FAILURE => $this->__('Failure', 'gatographql'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
