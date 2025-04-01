<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\PayloadableAddCustomPostMetaMutationResolverTrait;

class PayloadableAddCustomPostMetaMutationResolver extends AbstractMutateCustomPostMetaMutationResolver
{
    use PayloadableAddCustomPostMetaMutationResolverTrait;
}
