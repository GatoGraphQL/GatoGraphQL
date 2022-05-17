<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\TypeResolvers\InputObjectType;

use PoP\Root\App;
use PoPCMSSchema\Media\Module;
use PoPCMSSchema\Media\ModuleConfiguration;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\PaginationInputObjectTypeResolver;

class RootMediaItemPaginationInputObjectTypeResolver extends PaginationInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'RootMediaItemPaginationInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to paginate media items', 'media');
    }

    protected function getDefaultLimit(): ?int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getMediaListDefaultLimit();
    }

    protected function getMaxLimit(): ?int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getMediaListMaxLimit();
    }
}
