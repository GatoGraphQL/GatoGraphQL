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
    protected ?PostObjectTypeResolver $postObjectTypeResolver = null;
    protected ?SetTagsOnPostMutationResolver $setTagsOnPostMutationResolver = null;
    protected ?TranslationAPIInterface $translationAPI = null;

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
        return $this->getPostObjectTypeResolver();
    }

    public function getSetTagsMutationResolver(): MutationResolverInterface
    {
        return $this->getSetTagsOnPostMutationResolver();
    }

    protected function getEntityName(): string
    {
        return $this->getTranslationAPI()->__('post', 'post-tag-mutations');
    }
}
