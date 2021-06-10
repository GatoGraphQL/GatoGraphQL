<?php

declare(strict_types=1);

namespace PoPSchema\UserState\FieldResolvers;

use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\UserState\FieldResolvers\AbstractGlobalUserStateFieldResolver;

class GlobalUserStateFieldResolver extends AbstractGlobalUserStateFieldResolver
{
    public function getFieldNamesToResolve(): array
    {
        return [
            'loggedInUserID',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'loggedInUserID' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'loggedInUserID' => $this->translationAPI->__('The logged-in user\'s ID', 'user-state'),
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
            case 'loggedInUserID':
                $vars = ApplicationState::getVars();
                return $vars['global-userstate']['current-user-id'];
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
