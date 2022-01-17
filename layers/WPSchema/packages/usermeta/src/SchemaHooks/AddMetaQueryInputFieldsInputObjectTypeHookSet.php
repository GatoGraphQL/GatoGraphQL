<?php

declare(strict_types=1);

namespace PoPWPSchema\UserMeta\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPCMSSchema\Users\TypeResolvers\InputObjectType\AbstractUsersFilterInputObjectTypeResolver;
use PoPWPSchema\Meta\SchemaHooks\AbstractAddMetaQueryInputFieldsInputObjectTypeHookSet;
use PoPWPSchema\Meta\TypeResolvers\InputObjectType\AbstractMetaQueryInputObjectTypeResolver;
use PoPWPSchema\UserMeta\TypeResolvers\InputObjectType\UserMetaQueryInputObjectTypeResolver;

class AddMetaQueryInputFieldsInputObjectTypeHookSet extends AbstractAddMetaQueryInputFieldsInputObjectTypeHookSet
{
    private ?UserMetaQueryInputObjectTypeResolver $userMetaQueryInputObjectTypeResolver = null;

    final public function setUserMetaQueryInputObjectTypeResolver(UserMetaQueryInputObjectTypeResolver $userMetaQueryInputObjectTypeResolver): void
    {
        $this->userMetaQueryInputObjectTypeResolver = $userMetaQueryInputObjectTypeResolver;
    }
    final protected function getUserMetaQueryInputObjectTypeResolver(): UserMetaQueryInputObjectTypeResolver
    {
        return $this->userMetaQueryInputObjectTypeResolver ??= $this->instanceManager->getInstance(UserMetaQueryInputObjectTypeResolver::class);
    }

    protected function getMetaQueryInputObjectTypeResolver(): AbstractMetaQueryInputObjectTypeResolver
    {
        return $this->getUserMetaQueryInputObjectTypeResolver();
    }

    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof AbstractUsersFilterInputObjectTypeResolver;
    }
}
