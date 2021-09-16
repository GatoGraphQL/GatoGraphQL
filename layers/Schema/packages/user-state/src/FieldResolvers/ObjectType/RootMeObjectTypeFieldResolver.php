<?php

declare(strict_types=1);

namespace PoPSchema\UserState\FieldResolvers\ObjectType;

use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoPSchema\UserState\FieldResolvers\ObjectType\AbstractUserStateObjectTypeFieldResolver;
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootMeObjectTypeFieldResolver extends AbstractUserStateObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'me',
        ];
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'me' => $this->translationAPI->__('The logged-in user', 'user-state'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        switch ($fieldName) {
            case 'me':
                $vars = ApplicationState::getVars();
                return $vars['global-userstate']['current-user-id'];
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolverClass(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        switch ($fieldName) {
            case 'me':
                return UserObjectTypeResolver::class;
        }

        return parent::getFieldTypeResolverClass($objectTypeResolver, $fieldName);
    }
}
