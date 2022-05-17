<?php

declare(strict_types=1);

namespace PoPCMSSchema\Menus\TypeResolvers\InputObjectType;

use PoP\Root\App;
use PoPCMSSchema\Menus\Module;
use PoPCMSSchema\Menus\ModuleConfiguration;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\PaginationInputObjectTypeResolver;

class RootMenuPaginationInputObjectTypeResolver extends PaginationInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootMenuPaginationInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to paginate menus', 'menus');
    }

    protected function getDefaultLimit(): ?int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getComponent(Module::class)->getConfiguration();
        return $moduleConfiguration->getMenuListDefaultLimit();
    }

    protected function getMaxLimit(): ?int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getComponent(Module::class)->getConfiguration();
        return $moduleConfiguration->getMenuListMaxLimit();
    }
}
