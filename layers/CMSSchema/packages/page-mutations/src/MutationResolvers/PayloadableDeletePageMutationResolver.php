<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\MutationResolvers\PayloadableDeleteCustomPostMutationResolverTrait;

class PayloadableDeletePageMutationResolver extends DeletePageMutationResolver
{
    use PayloadableDeleteCustomPostMutationResolverTrait;
}
