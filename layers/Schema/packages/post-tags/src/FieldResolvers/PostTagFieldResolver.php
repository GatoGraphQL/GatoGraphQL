<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\FieldResolvers;

use PoPSchema\PostTags\TypeResolvers\Object\PostTagTypeResolver;
use PoPSchema\Tags\FieldResolvers\AbstractTagFieldResolver;
use PoPSchema\PostTags\ComponentContracts\PostTagAPISatisfiedContractTrait;

class PostTagFieldResolver extends AbstractTagFieldResolver
{
    use PostTagAPISatisfiedContractTrait;

    public function getClassesToAttachTo(): array
    {
        return array(PostTagTypeResolver::class);
    }
}
