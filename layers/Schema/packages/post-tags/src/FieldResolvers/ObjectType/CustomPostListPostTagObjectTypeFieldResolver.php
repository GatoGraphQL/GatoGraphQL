<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\FieldResolvers\ObjectType;

use PoPSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPSchema\Tags\FieldResolvers\ObjectType\AbstractCustomPostListTagObjectTypeFieldResolver;
use PoPSchema\PostTags\ComponentContracts\PostTagAPISatisfiedContractTrait;

class CustomPostListPostTagObjectTypeFieldResolver extends AbstractCustomPostListTagObjectTypeFieldResolver
{
    use PostTagAPISatisfiedContractTrait;

    public function isServiceEnabled(): bool
    {
        /**
         * @todo Enable if the post tag (i.e. taxonomy "post_tag") can have other custom post types use it (eg: page, event, etc)
         */
        return false;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostTagObjectTypeResolver::class,
        ];
    }

    protected function getQueryProperty(): string
    {
        return 'tag-ids';
    }
}
