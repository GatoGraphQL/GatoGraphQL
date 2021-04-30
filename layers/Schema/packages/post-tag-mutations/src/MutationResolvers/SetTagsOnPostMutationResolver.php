<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\MutationResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPostTagMutations\MutationResolvers\AbstractSetTagsOnCustomPostMutationResolver;
use PoPSchema\CustomPostTagMutations\TypeAPIs\CustomPostTagTypeMutationAPIInterface;
use PoPSchema\PostTagMutations\Facades\PostTagTypeMutationAPIFacade;

class SetTagsOnPostMutationResolver extends AbstractSetTagsOnCustomPostMutationResolver
{
    protected function getCustomPostTagTypeMutationAPI(): CustomPostTagTypeMutationAPIInterface
    {
        return PostTagTypeMutationAPIFacade::getInstance();
    }

    protected function getEntityName(): string
    {
        return $this->translationAPI->__('post', 'post-tag-mutations');
    }
}
