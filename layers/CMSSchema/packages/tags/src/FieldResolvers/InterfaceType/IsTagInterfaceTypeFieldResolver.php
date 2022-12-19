<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\FieldResolvers\InterfaceType;

use PoPCMSSchema\Tags\TypeResolvers\InterfaceType\IsTagInterfaceTypeResolver;
use PoPCMSSchema\Taxonomies\FieldResolvers\InterfaceType\AbstractIsTaxonomyInterfaceTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;

class IsTagInterfaceTypeFieldResolver extends AbstractIsTaxonomyInterfaceTypeFieldResolver
{
    /**
     * @return array<class-string<InterfaceTypeResolverInterface>>
     */
    public function getInterfaceTypeResolverClassesToAttachTo(): array
    {
        return [
            IsTagInterfaceTypeResolver::class,
        ];
    }

    public function getFieldDescription(string $fieldName): ?string
    {
        return match ($fieldName) {
            'name' => $this->__('Tag', 'tags'),
            'description' => $this->__('Tag description', 'tags'),
            'count' => $this->__('Number of custom posts containing this tag', 'tags'),
            default => parent::getFieldDescription($fieldName),
        };
    }
}
