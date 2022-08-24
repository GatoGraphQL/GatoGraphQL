<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoPCMSSchema\Users\Constants\UserOrderBy;
use PoPCMSSchema\Users\TypeResolvers\EnumType\UserOrderByEnumTypeResolver;
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
        /** @var UserOrderByEnumTypeResolver */
        return $this->customPostSortByEnumTypeResolver ??= $this->instanceManager->getInstance(UserOrderByEnumTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'UserSortInput';
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
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
