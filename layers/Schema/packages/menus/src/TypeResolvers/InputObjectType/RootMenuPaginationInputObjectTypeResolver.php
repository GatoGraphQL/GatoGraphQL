<?php

declare(strict_types=1);

namespace PoPSchema\Menus\TypeResolvers\InputObjectType;

use PoPSchema\Menus\Component;
use PoPSchema\Menus\ComponentConfiguration;
use PoPSchema\SchemaCommons\TypeResolvers\InputObjectType\PaginationInputObjectTypeResolver;

class RootMenuPaginationInputObjectTypeResolver extends PaginationInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootMenuPaginationInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Input to paginate menus', 'menus');
    }

    protected function getDefaultLimit(): ?int
    {
        return ComponentConfiguration::getMenuListDefaultLimit();
    }

    protected function getMaxLimit(): ?int
    {
        return ComponentConfiguration::getMenuListMaxLimit();
    }
}
