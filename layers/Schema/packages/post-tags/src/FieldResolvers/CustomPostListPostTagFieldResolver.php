<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\FieldResolvers;

use PoPSchema\PostTags\TypeResolvers\PostTagTypeResolver;
use PoPSchema\Tags\FieldResolvers\AbstractCustomPostListTagFieldResolver;
use PoPSchema\PostTags\ComponentContracts\PostTagAPISatisfiedContractTrait;

class CustomPostListPostTagFieldResolver extends AbstractCustomPostListTagFieldResolver
{
    use PostTagAPISatisfiedContractTrait;

    public function getClassesToAttachTo(): array
    {
        return array(PostTagTypeResolver::class);
    }

    protected function getQueryProperty(): string
    {
        return 'tag-ids';
    }
}
