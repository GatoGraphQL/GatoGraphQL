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

    final protected function getUserMetaQueryInputObjectTypeResolver(): UserMetaQueryInputObjectTypeResolver
    {
        if ($this->userMetaQueryInputObjectTypeResolver === null) {
            /** @var UserMetaQueryInputObjectTypeResolver */
            $userMetaQueryInputObjectTypeResolver = $this->instanceManager->getInstance(UserMetaQueryInputObjectTypeResolver::class);
            $this->userMetaQueryInputObjectTypeResolver = $userMetaQueryInputObjectTypeResolver;
        }
        return $this->userMetaQueryInputObjectTypeResolver;
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
