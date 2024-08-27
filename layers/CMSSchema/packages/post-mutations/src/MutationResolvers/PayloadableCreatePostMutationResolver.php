<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\MutationResolvers\PayloadableCreateCustomPostMutationResolverTrait;

class PayloadableCreatePostMutationResolver extends AbstractCreateOrUpdatePostMutationResolver
{
    use PayloadableCreateCustomPostMutationResolverTrait;
}
