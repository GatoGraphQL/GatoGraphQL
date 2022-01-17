<?php

declare(strict_types=1);

namespace PoPSchema\Users\TypeResolvers\InputObjectType;

use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\AbstractObjectsFilterInputObjectTypeResolver;

abstract class AbstractUsersFilterInputObjectTypeResolver extends AbstractObjectsFilterInputObjectTypeResolver
{
    private ?UserSearchByInputObjectTypeResolver $userSearchByInputObjectTypeResolver = null;

    final public function setUserSearchByInputObjectTypeResolver(UserSearchByInputObjectTypeResolver $userSearchByInputObjectTypeResolver): void
    {
        $this->userSearchByInputObjectTypeResolver = $userSearchByInputObjectTypeResolver;
    }
    final protected function getUserSearchByInputObjectTypeResolver(): UserSearchByInputObjectTypeResolver
    {
        return $this->userSearchByInputObjectTypeResolver ??= $this->instanceManager->getInstance(UserSearchByInputObjectTypeResolver::class);
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter users', 'users');
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                'searchBy' => $this->getUserSearchByInputObjectTypeResolver(),
            ]
        );
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'searchBy' => $this->__('Search for users', 'users'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
