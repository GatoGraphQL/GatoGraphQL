<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\MutationResolvers\PayloadableCreateCustomPostMutationResolverTrait;

class PayloadableCreatePageMutationResolver extends AbstractCreateOrUpdatePageMutationResolver
{
    use PayloadableCreateCustomPostMutationResolverTrait;
}
