<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\TypeResolvers\InputObjectType;

use PoP\Root\App;
use PoPCMSSchema\Tags\Module;
use PoPCMSSchema\Tags\ModuleConfiguration;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\PaginationInputObjectTypeResolver;

class TagPaginationInputObjectTypeResolver extends PaginationInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'TagPaginationInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to paginate tags', 'tags');
    }

    protected function getDefaultLimit(): ?int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getTagListDefaultLimit();
    }

    protected function getMaxLimit(): ?int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getTagListMaxLimit();
    }
}
