<?php

declare(strict_types=1);

namespace PoPSchema\Users\FieldResolvers\InterfaceType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractInterfaceTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoPSchema\Users\TypeResolvers\InterfaceType\WithAuthorInterfaceTypeResolver;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class WithAuthorInterfaceTypeFieldResolver extends AbstractInterfaceTypeFieldResolver
{
    protected UserObjectTypeResolver $userObjectTypeResolver;

    #[Required]
    final public function autowireWithAuthorInterfaceTypeFieldResolver(
        UserObjectTypeResolver $userObjectTypeResolver,
    ): void {
        $this->userObjectTypeResolver = $userObjectTypeResolver;
    }

    public function getInterfaceTypeResolverClassesToAttachTo(): array
    {
        return [
            WithAuthorInterfaceTypeResolver::class,
        ];
    }

    public function getFieldNamesToImplement(): array
    {
        return [
            'author',
        ];
    }

    public function getFieldTypeModifiers(string $fieldName): int
    {
        switch ($fieldName) {
            case 'author':
                return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getFieldTypeModifiers($fieldName);
    }

    public function getFieldDescription(string $fieldName): ?string
    {
        return match ($fieldName) {
            'author' => $this->translationAPI->__('The entity\'s author', 'queriedobject'),
            default => parent::getFieldDescription($fieldName),
        };
    }

    public function getFieldTypeResolver(string $fieldName): ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'author':
                return $this->userObjectTypeResolver;
        }

        return parent::getFieldTypeResolver($fieldName);
    }
}
