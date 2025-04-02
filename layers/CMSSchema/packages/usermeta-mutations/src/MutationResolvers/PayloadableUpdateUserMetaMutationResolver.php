<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\MutationResolvers;

use PoPCMSSchema\UserMetaMutations\MutationResolvers\PayloadableUpdateUserMetaMutationResolverTrait;

class PayloadableUpdateUserMetaMutationResolver extends AbstractMutateUserMetaMutationResolver
{
    use PayloadableUpdateUserMetaMutationResolverTrait;
}
