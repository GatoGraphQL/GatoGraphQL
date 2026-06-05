<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\FieldResolvers\InterfaceType;

use PoPCMSSchema\Categories\TypeResolvers\InterfaceType\CategoryInterfaceTypeResolver;
use PoPCMSSchema\Taxonomies\FieldResolvers\InterfaceType\AbstractIsTaxonomyInterfaceTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;

class CategoryInterfaceTypeFieldResolver extends AbstractIsTaxonomyInterfaceTypeFieldResolver
{
    /**
     * @return array<class-string<InterfaceTypeResolverInterface>>
     */
    public function getInterfaceTypeResolverClassesToAttachTo(): array
    {
        return [
            CategoryInterfaceTypeResolver::class,
        ];
    }

    public function getFieldDescription(string $fieldName): ?string
    {
        return match ($fieldName) {
            'name' => $this->__('Category', 'gatographql'),
            'description' => $this->__('Category description', 'gatographql'),
            'count' => $this->__('Number of custom posts containing this category', 'gatographql'),
            'slugPath' => $this->__('Full category slug, from the root ancestor all the way down, separated by \'/\', and not including \'/\' at either end', 'gatographql'),
            default => parent::getFieldDescription($fieldName),
        };
    }
}
