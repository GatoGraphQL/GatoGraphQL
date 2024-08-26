<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\MutationResolvers\AbstractCreateOrUpdateCustomPostMutationResolver;

abstract class AbstractCreateOrUpdateGenericCustomPostMutationResolver extends AbstractCreateOrUpdateCustomPostMutationResolver
{
    public function getCustomPostType(): string
    {
        return '';
    }
}
