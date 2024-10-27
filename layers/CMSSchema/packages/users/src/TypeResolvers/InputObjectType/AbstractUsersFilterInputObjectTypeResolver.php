<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\AbstractObjectsFilterInputObjectTypeResolver;

abstract class AbstractUsersFilterInputObjectTypeResolver extends AbstractObjectsFilterInputObjectTypeResolver
{
    private ?UserSearchByOneofInputObjectTypeResolver $userSearchByOneofInputObjectTypeResolver = null;

    final protected function getUserSearchByOneofInputObjectTypeResolver(): UserSearchByOneofInputObjectTypeResolver
    {
        if ($this->userSearchByOneofInputObjectTypeResolver === null) {
            /** @var UserSearchByOneofInputObjectTypeResolver */
            $userSearchByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(UserSearchByOneofInputObjectTypeResolver::class);
            $this->userSearchByOneofInputObjectTypeResolver = $userSearchByOneofInputObjectTypeResolver;
        }
        return $this->userSearchByOneofInputObjectTypeResolver;
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
                'searchBy' => $this->getUserSearchByOneofInputObjectTypeResolver(),
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
