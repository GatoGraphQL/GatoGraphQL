<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractQueryableInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use stdClass;

class FilterByAuthorInputObjectTypeResolver extends AbstractQueryableInputObjectTypeResolver
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        if ($this->idScalarTypeResolver === null) {
            /** @var IDScalarTypeResolver */
            $idScalarTypeResolver = $this->instanceManager->getInstance(IDScalarTypeResolver::class);
            $this->idScalarTypeResolver = $idScalarTypeResolver;
        }
        return $this->idScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'FilterByAuthorInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Filter by author', 'users');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'ids' => $this->getIDScalarTypeResolver(),
            'slug' => $this->getStringScalarTypeResolver(),
            'excludeIDs' => $this->getIDScalarTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'ids' => $this->__('Get results from the authors with given IDs', 'pop-users'),
            'slug' => $this->__('Get results from the authors with given slug', 'pop-users'),
            'excludeIDs' => $this->__('Get results excluding the ones from authors with given IDs', 'pop-users'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            'ids',
            'excludeIDs'
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }

    /**
     * @param array<string,mixed> $query
     * @param stdClass|stdClass[]|array<stdClass[]> $inputValue
     */
    public function integrateInputValueToFilteringQueryArgs(array &$query, stdClass|array $inputValue): void
    {
        parent::integrateInputValueToFilteringQueryArgs($query, $inputValue);

        if (isset($inputValue->ids)) {
            $query['author-ids'] = $inputValue->ids;
        }

        if (isset($inputValue->excludeIDs)) {
            $query['exclude-author-ids'] = $inputValue->excludeIDs;
        }

        if (isset($inputValue->slug)) {
            $query['author-slug'] = $inputValue->slug;
        }
    }
}
