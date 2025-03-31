<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\PayloadableAddCustomPostMetaMutationResolverTrait;

class PayloadableAddCustomPostMetaMutationResolver extends AbstractMutateCustomPostMetaMutationResolver
{
    use PayloadableAddCustomPostMetaMutationResolverTrait;
}
