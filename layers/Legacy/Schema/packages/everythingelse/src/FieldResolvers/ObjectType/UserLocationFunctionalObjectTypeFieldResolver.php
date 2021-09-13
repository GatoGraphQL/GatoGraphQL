<?php

declare(strict_types=1);

namespace PoPSchema\Locations\FieldResolvers\ObjectType;

use PoPSchema\Users\Constants\InputNames;
use PoPSchema\Users\TypeResolvers\ObjectType\UserTypeResolver;

class UserLocationFunctionalObjectTypeFieldResolver extends AbstractLocationFunctionalObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserTypeResolver::class,
        ];
    }

    protected function getDbobjectIdField()
    {
        return InputNames::USER_ID;
    }
}
