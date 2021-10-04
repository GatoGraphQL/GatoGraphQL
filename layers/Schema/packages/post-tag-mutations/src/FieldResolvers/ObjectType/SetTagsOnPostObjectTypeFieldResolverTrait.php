<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPSchema\PostTagMutations\MutationResolvers\SetTagsOnPostMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

trait SetTagsOnPostObjectTypeFieldResolverTrait
{
    protected PostObjectTypeResolver $postObjectTypeResolver;
    protected SetTagsOnPostMutationResolver $setTagsOnPostMutationResolver;
    protected TranslationAPIInterface $translationAPI;

    #[Required]
    public function autowireSetTagsOnPostObjectTypeFieldResolverTrait(
        PostObjectTypeResolver $postObjectTypeResolver,
        SetTagsOnPostMutationResolver $setTagsOnPostMutationResolver,
        TranslationAPIInterface $translationAPI,
    ): void {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
        $this->setTagsOnPostMutationResolver = $setTagsOnPostMutationResolver;
        $this->translationAPI = $translationAPI;
    }

    public function getCustomPostObjectTypeResolver(): CustomPostObjectTypeResolverInterface
    {
        return $this->postObjectTypeResolver;
    }

    public function getSetTagsMutationResolver(): MutationResolverInterface
    {
        return $this->setTagsOnPostMutationResolver;
    }

    protected function getEntityName(): string
    {
        return $this->translationAPI->__('post', 'post-tag-mutations');
    }
}
