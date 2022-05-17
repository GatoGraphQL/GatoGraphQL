<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts\TypeResolvers\InputObjectType;

use PoP\Root\App;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostPaginationInputObjectTypeResolver;
use PoPCMSSchema\Posts\Module;
use PoPCMSSchema\Posts\ModuleConfiguration;

class PostPaginationInputObjectTypeResolver extends CustomPostPaginationInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostPaginationInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to paginate posts', 'posts');
    }

    protected function getDefaultLimit(): ?int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getComponent(Module::class)->getConfiguration();
        return $moduleConfiguration->getPostListDefaultLimit();
    }

    protected function getMaxLimit(): ?int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getComponent(Module::class)->getConfiguration();
        return $moduleConfiguration->getPostListMaxLimit();
    }
}
