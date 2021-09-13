<?php

declare(strict_types=1);

namespace PoPSchema\Locations\FieldResolvers\ObjectType;

use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostTypeResolver;
use PoPSchema\Posts\Constants\InputNames;

class CustomPostLocationFunctionalObjectTypeFieldResolver extends AbstractLocationFunctionalObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostTypeResolver::class,
        ];
    }

    protected function getDbobjectIdField()
    {
        return InputNames::POST_ID;
    }
}
