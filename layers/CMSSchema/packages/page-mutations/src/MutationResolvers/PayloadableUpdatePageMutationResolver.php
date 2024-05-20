<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\MutationResolvers\PayloadableUpdateCustomPostMutationResolverTrait;

class PayloadableUpdatePageMutationResolver extends AbstractCreateUpdatePageMutationResolver
{
    use PayloadableUpdateCustomPostMutationResolverTrait;
}
