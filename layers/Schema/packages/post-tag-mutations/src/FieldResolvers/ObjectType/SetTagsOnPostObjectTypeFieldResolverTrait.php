<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPSchema\PostTagMutations\MutationResolvers\SetTagsOnPostMutationResolver;

trait SetTagsOnPostObjectTypeFieldResolverTrait
{
    public function getCustomPostTypeResolver(): CustomPostObjectTypeResolverInterface
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        return $instanceManager->getInstance(PostObjectTypeResolver::class);
    }

    public function getTypeMutationResolverClass(): string
    {
        return SetTagsOnPostMutationResolver::class;
    }

    protected function getEntityName(): string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('post', 'post-tag-mutations');
    }
}
