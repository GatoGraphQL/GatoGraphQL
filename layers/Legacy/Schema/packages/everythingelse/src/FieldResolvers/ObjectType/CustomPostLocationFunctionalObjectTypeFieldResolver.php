<?php

declare(strict_types=1);

namespace PoPCMSSchemaPRO\Locations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPCMSSchema\Posts\Constants\InputNames;

class CustomPostLocationFunctionalObjectTypeFieldResolver extends AbstractLocationFunctionalObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostObjectTypeResolver::class,
        ];
    }

    protected function getDbobjectIdField()
    {
        return InputNames::POST_ID;
    }
}
