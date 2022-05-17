<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\TypeResolvers\InputObjectType;

use PoP\Root\App;
use PoPCMSSchema\Categories\Module;
use PoPCMSSchema\Categories\ModuleConfiguration;
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
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getCategoryListDefaultLimit();
    }

    protected function getMaxLimit(): ?int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getCategoryListMaxLimit();
    }
}
