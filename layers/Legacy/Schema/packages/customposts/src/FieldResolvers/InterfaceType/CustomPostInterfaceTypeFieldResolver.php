<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\FieldResolvers\InterfaceType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoPCMSSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateScalarTypeResolver;

class CustomPostInterfaceTypeFieldResolver extends QueryableInterfaceTypeFieldResolver
{
    private ?DateScalarTypeResolver $dateScalarTypeResolver = null;
    private ?QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver = null;
    
    final public function setDateScalarTypeResolver(DateScalarTypeResolver $dateScalarTypeResolver): void
    {
        $this->dateScalarTypeResolver = $dateScalarTypeResolver;
    }
    final protected function getDateScalarTypeResolver(): DateScalarTypeResolver
    {
        if ($this->dateScalarTypeResolver === null) {
            /** @var DateScalarTypeResolver */
            $dateScalarTypeResolver = $this->instanceManager->getInstance(DateScalarTypeResolver::class);
            $this->dateScalarTypeResolver = $dateScalarTypeResolver;
        }
        return $this->dateScalarTypeResolver;
    }
    final public function setQueryableInterfaceTypeFieldResolver(QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver): void
    {
        $this->queryableInterfaceTypeFieldResolver = $queryableInterfaceTypeFieldResolver;
    }
    final protected function getQueryableInterfaceTypeFieldResolver(): QueryableInterfaceTypeFieldResolver
    {
        if ($this->queryableInterfaceTypeFieldResolver === null) {
            /** @var QueryableInterfaceTypeFieldResolver */
            $queryableInterfaceTypeFieldResolver = $this->instanceManager->getInstance(QueryableInterfaceTypeFieldResolver::class);
            $this->queryableInterfaceTypeFieldResolver = $queryableInterfaceTypeFieldResolver;
        }
        return $this->queryableInterfaceTypeFieldResolver;
    }

    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->queryableInterfaceTypeFieldResolver,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToImplement(): array
    {
        return array_merge(
            parent::getFieldNamesToImplement(),
            [
                'datetime',
            ]
        );
    }

    public function getFieldTypeResolver(string $fieldName): ConcreteTypeResolverInterface
    {
        return match($fieldName) {
            'datetime' => $this->dateScalarTypeResolver,
            default => parent::getFieldTypeResolver($fieldName),
        };
    }

    public function getFieldTypeModifiers(string $fieldName): int
    {
        /**
         * Please notice that the URL, slug, title and excerpt are nullable,
         * and content is not!
         */
        switch ($fieldName) {
            case 'datetime':
                return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getFieldTypeModifiers($fieldName);
    }

    public function getFieldDescription(string $fieldName): ?string
    {
        return match($fieldName) {
            'datetime' => $this->getTranslationAPI()->__('Custom post published date and time', 'customposts'),
            default => parent::getFieldDescription($fieldName),
        };
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(string $fieldName): array
    {
        return match ($fieldName) {
            'datetime' => [
                'format' => $this->stringScalarTypeResolver,
            ],
            default => parent::getFieldArgNameTypeResolvers($fieldName),
        };
    }
    
    public function getFieldArgDescription(string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['datetime' => 'format'] => sprintf(
                $this->getTranslationAPI()->__('Date and time format, as defined in %s. Default value: \'%s\' (for current year date) or \'%s\' (otherwise)', 'customposts'),
                'https://www.php.net/manual/en/function.date.php',
                'j M, H:i',
                'j M Y, H:i'
            ),
            default => parent::getFieldArgDescription($fieldName, $fieldArgName),
        };
    }
}
