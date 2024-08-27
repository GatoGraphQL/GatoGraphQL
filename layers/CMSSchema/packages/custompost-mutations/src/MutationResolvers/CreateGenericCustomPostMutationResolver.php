<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\MutationResolvers\CreateCustomPostMutationResolverTrait;

class CreateGenericCustomPostMutationResolver extends AbstractCreateOrUpdateGenericCustomPostMutationResolver
{
    use CreateCustomPostMutationResolverTrait;
}
