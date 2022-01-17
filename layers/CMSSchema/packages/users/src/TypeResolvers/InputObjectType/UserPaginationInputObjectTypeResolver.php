<?php

declare(strict_types=1);

namespace PoPSchema\Users\TypeResolvers\InputObjectType;

use PoP\Root\App;
use PoPSchema\Users\Component;
use PoPSchema\Users\ComponentConfiguration;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\PaginationInputObjectTypeResolver;

class UserPaginationInputObjectTypeResolver extends PaginationInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'UserPaginationInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to paginate users', 'users');
    }

    protected function getDefaultLimit(): ?int
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getUserListDefaultLimit();
    }

    protected function getMaxLimit(): ?int
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getUserListMaxLimit();
    }
}
