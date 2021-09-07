<?php

declare(strict_types=1);

namespace PoPSchema\Locations\FieldResolvers;

use PoPSchema\CustomPosts\TypeResolvers\Interface\IsCustomPostInterfaceTypeResolver;
use PoPSchema\Posts\Constants\InputNames;

class CustomPostLocationFunctionalFieldResolver extends AbstractLocationFunctionalFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(
            IsCustomPostInterfaceTypeResolver::class,
        );
    }

    protected function getDbobjectIdField()
    {
        return InputNames::POST_ID;
    }
}
