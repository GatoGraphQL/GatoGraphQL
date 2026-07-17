<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\MutationResolvers\PayloadableDeleteCustomPostMutationResolverTrait;

class PayloadableDeletePostMutationResolver extends DeletePostMutationResolver
{
    use PayloadableDeleteCustomPostMutationResolverTrait;
}
