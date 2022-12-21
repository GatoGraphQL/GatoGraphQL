<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\FieldResolvers\InterfaceType;

use PoPCMSSchema\Categories\TypeResolvers\InterfaceType\IsCategoryInterfaceTypeResolver;
use PoPCMSSchema\Taxonomies\FieldResolvers\InterfaceType\AbstractIsTaxonomyInterfaceTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;

class IsCategoryInterfaceTypeFieldResolver extends AbstractIsTaxonomyInterfaceTypeFieldResolver
{
    /**
     * @return array<class-string<InterfaceTypeResolverInterface>>
     */
    public function getInterfaceTypeResolverClassesToAttachTo(): array
    {
        return [
            IsCategoryInterfaceTypeResolver::class,
        ];
    }

    public function getFieldDescription(string $fieldName): ?string
    {
        return match ($fieldName) {
            'name' => $this->__('Category', 'categories'),
            'description' => $this->__('Category description', 'categories'),
            'count' => $this->__('Number of custom posts containing this category', 'categories'),
            default => parent::getFieldDescription($fieldName),
        };
    }
}
