<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\FieldResolvers\ObjectType;

use PoPSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPSchema\Tags\FieldResolvers\ObjectType\AbstractTagObjectTypeFieldResolver;
use PoPSchema\PostTags\ComponentContracts\PostTagAPISatisfiedContractTrait;

class PostTagObjectTypeFieldResolver extends AbstractTagObjectTypeFieldResolver
{
    use PostTagAPISatisfiedContractTrait;

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostTagObjectTypeResolver::class,
        ];
    }
}
