<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\MutationResolvers;

use PoPCMSSchema\UserMetaMutations\MutationResolvers\PayloadableAddUserMetaMutationResolverTrait;

class PayloadableAddUserMetaMutationResolver extends AbstractMutateUserMetaMutationResolver
{
    use PayloadableAddUserMetaMutationResolverTrait;
}
