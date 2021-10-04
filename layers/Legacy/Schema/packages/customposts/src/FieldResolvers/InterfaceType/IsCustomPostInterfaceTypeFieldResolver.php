<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\FieldResolvers\InterfaceType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateScalarTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class IsCustomPostInterfaceTypeFieldResolver extends QueryableInterfaceTypeFieldResolver
{
    protected DateScalarTypeResolver $dateScalarTypeResolver;
    protected StringScalarTypeResolver $stringScalarTypeResolver;
    protected QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver;
    
    #[Required]
    public function autowireIsCustomPostInterfaceTypeFieldResolver(
        DateScalarTypeResolver $dateScalarTypeResolver,
        StringScalarTypeResolver $stringScalarTypeResolver,
        QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver,
    ): void {
        $this->dateScalarTypeResolver = $dateScalarTypeResolver;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        $this->queryableInterfaceTypeFieldResolver = $queryableInterfaceTypeFieldResolver;
    }

    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->queryableInterfaceTypeFieldResolver,
        ];
    }

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
            'datetime' => $this->translationAPI->__('Custom post published date and time', 'customposts'),
            default => parent::getFieldDescription($fieldName),
        };
    }
    public function getFieldArgNameResolvers(string $fieldName): array
    {
        return match ($fieldName) {
            'datetime' => [
                'format' => $this->stringScalarTypeResolver,
            ],
            default => parent::getFieldArgNameResolvers($fieldName),
        };
    }
    
    public function getFieldArgDescription(string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['datetime' => 'format'] => sprintf(
                $this->translationAPI->__('Date and time format, as defined in %s. Default value: \'%s\' (for current year date) or \'%s\' (otherwise)', 'customposts'),
                'https://www.php.net/manual/en/function.date.php',
                'j M, H:i',
                'j M Y, H:i'
            ),
            default => parent::getFieldArgDescription($fieldName, $fieldArgName),
        };
    }
}
