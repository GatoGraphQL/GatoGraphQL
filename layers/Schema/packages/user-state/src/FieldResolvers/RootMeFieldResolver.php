<?php

declare(strict_types=1);

namespace PoPSchema\UserState\FieldResolvers;

use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoPSchema\UserState\FieldResolvers\AbstractUserStateFieldResolver;
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

class RootMeFieldResolver extends AbstractUserStateFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'me',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'me' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'me' => $this->translationAPI->__('The logged-in user', 'user-state'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
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

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'me':
                return UserTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
