<?php

declare(strict_types=1);

namespace PoPSchema\Locations\FieldResolvers;

use PoPSchema\Users\Constants\InputNames;
use PoPSchema\Users\TypeResolvers\ObjectType\UserTypeResolver;

class UserLocationFunctionalFieldResolver extends AbstractLocationFunctionalFieldResolver
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
