<?php

declare(strict_types=1);

namespace PoP\Engine\ConditionalOnContext\Guzzle\SchemaServices\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractGlobalObjectTypeFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GuzzleHelpers\GuzzleHelpers;
use PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;

class OperatorGlobalObjectTypeFieldResolver extends AbstractGlobalObjectTypeFieldResolver
{
    private ?JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver = null;
    private ?URLScalarTypeResolver $urlScalarTypeResolver = null;

    final public function setJSONObjectScalarTypeResolver(JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver): void
    {
        $this->jsonObjectScalarTypeResolver = $jsonObjectScalarTypeResolver;
    }
    final protected function getJSONObjectScalarTypeResolver(): JSONObjectScalarTypeResolver
    {
        return $this->jsonObjectScalarTypeResolver ??= $this->instanceManager->getInstance(JSONObjectScalarTypeResolver::class);
    }
    final public function setURLScalarTypeResolver(URLScalarTypeResolver $urlScalarTypeResolver): void
    {
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
    }
    final protected function getURLScalarTypeResolver(): URLScalarTypeResolver
    {
        return $this->urlScalarTypeResolver ??= $this->instanceManager->getInstance(URLScalarTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'getJSON',
            'getAsyncJSON',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'getJSON' => $this->getJSONObjectScalarTypeResolver(),
            'getAsyncJSON' => $this->getJSONObjectScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'getAsyncJSON' => SchemaTypeModifiers::IS_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'getJSON' => $this->__('Retrieve data from URL and decode it as a JSON object', 'pop-component-model'),
            'getAsyncJSON' => $this->__('Retrieve data from multiple URL asynchronously, and decode each of them as a JSON object', 'pop-component-model'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'getJSON' => [
                'url' => $this->getURLScalarTypeResolver(),
            ],
            'getAsyncJSON' => [
                'urls' => $this->getURLScalarTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['getJSON' => 'url'] => $this->__('The URL to request', 'pop-component-model'),
            ['getAsyncJSON' => 'urls'] => $this->__('The URLs to request, with format `key:value`, where the value is the URL, and the key, if provided, is the name where to store the JSON data in the result (if not provided, it is accessed under the corresponding numeric index)', 'pop-component-model'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['getJSON' => 'url'] => SchemaTypeModifiers::MANDATORY,
            ['getAsyncJSON' => 'urls'] => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
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
        array $fieldArgs,
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        switch ($fieldName) {
            case 'getJSON':
                $response = GuzzleHelpers::requestJSON($fieldArgs['url'], [], 'GET');
                if (GeneralUtils::isError($response)) {
                    return $response;
                }
                return (object) $response;
            case 'getAsyncJSON':
                $response = GuzzleHelpers::requestAsyncJSON($fieldArgs['urls'], [], 'GET');
                if (GeneralUtils::isError($response)) {
                    return $response;
                }
                return (object) $response;
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
