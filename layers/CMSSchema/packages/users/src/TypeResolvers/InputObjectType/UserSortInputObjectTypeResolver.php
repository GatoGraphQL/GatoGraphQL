<?php

declare(strict_types=1);

namespace PoPSchema\Users\TypeResolvers\InputObjectType;

use PoPSchema\Users\Constants\UserOrderBy;
use PoPSchema\Users\TypeResolvers\EnumType\UserOrderByEnumTypeResolver;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\SortInputObjectTypeResolver;

class UserSortInputObjectTypeResolver extends SortInputObjectTypeResolver
{
    private ?UserOrderByEnumTypeResolver $customPostSortByEnumTypeResolver = null;

    final public function setUserOrderByEnumTypeResolver(UserOrderByEnumTypeResolver $customPostSortByEnumTypeResolver): void
    {
        $this->customPostSortByEnumTypeResolver = $customPostSortByEnumTypeResolver;
    }
    final protected function getUserOrderByEnumTypeResolver(): UserOrderByEnumTypeResolver
    {
        return $this->customPostSortByEnumTypeResolver ??= $this->instanceManager->getInstance(UserOrderByEnumTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'UserSortInput';
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                'by' => $this->getUserOrderByEnumTypeResolver(),
            ]
        );
    }

    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return match ($inputFieldName) {
            'by' => UserOrderBy::ID,
            default => parent::getInputFieldDefaultValue($inputFieldName),
        };
    }
}
