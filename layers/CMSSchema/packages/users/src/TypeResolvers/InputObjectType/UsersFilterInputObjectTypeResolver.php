<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\TypeResolvers\InputObjectType;

class UsersFilterInputObjectTypeResolver extends AbstractUsersFilterInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'UsersFilterInput';
    }
}
