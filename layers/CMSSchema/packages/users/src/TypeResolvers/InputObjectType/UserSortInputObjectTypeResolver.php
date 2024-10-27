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

    final protected function getUserOrderByEnumTypeResolver(): UserOrderByEnumTypeResolver
    {
        if ($this->customPostSortByEnumTypeResolver === null) {
            /** @var UserOrderByEnumTypeResolver */
            $customPostSortByEnumTypeResolver = $this->instanceManager->getInstance(UserOrderByEnumTypeResolver::class);
            $this->customPostSortByEnumTypeResolver = $customPostSortByEnumTypeResolver;
        }
        return $this->customPostSortByEnumTypeResolver;
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
