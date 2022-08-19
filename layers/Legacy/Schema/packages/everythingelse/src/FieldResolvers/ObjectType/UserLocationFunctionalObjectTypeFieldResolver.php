<?php

declare(strict_types=1);

namespace PoPCMSSchema\Locations\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\Users\Constants\InputNames;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class UserLocationFunctionalObjectTypeFieldResolver extends AbstractLocationFunctionalObjectTypeFieldResolver
{
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserObjectTypeResolver::class,
        ];
    }

    protected function getDbobjectIdField(): string
    {
        return InputNames::USER_ID;
    }
}
