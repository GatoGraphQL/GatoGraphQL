<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\MutationResolvers\CreateCustomPostMutationResolverTrait;

class CreatePageMutationResolver extends AbstractCreateOrUpdatePageMutationResolver
{
    use CreateCustomPostMutationResolverTrait;
}
