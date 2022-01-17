<?php

declare(strict_types=1);

namespace PoPSchema\Categories\TypeResolvers\InputObjectType;

use PoP\Root\App;
use PoPSchema\Categories\Component;
use PoPSchema\Categories\ComponentConfiguration;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\PaginationInputObjectTypeResolver;

class CategoryPaginationInputObjectTypeResolver extends PaginationInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CategoryPaginationInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to paginate categories', 'categories');
    }

    protected function getDefaultLimit(): ?int
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getCategoryListDefaultLimit();
    }

    protected function getMaxLimit(): ?int
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getCategoryListMaxLimit();
    }
}
