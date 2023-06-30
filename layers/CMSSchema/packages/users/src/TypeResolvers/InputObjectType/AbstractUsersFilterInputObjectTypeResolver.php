<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
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
        if ($this->userSearchByInputObjectTypeResolver === null) {
            /** @var UserSearchByInputObjectTypeResolver */
            $userSearchByInputObjectTypeResolver = $this->instanceManager->getInstance(UserSearchByInputObjectTypeResolver::class);
            $this->userSearchByInputObjectTypeResolver = $userSearchByInputObjectTypeResolver;
        }
        return $this->userSearchByInputObjectTypeResolver;
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to filter users', 'users');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
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
