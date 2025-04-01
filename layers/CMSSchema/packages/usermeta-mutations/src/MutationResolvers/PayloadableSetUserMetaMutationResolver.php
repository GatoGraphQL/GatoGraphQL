<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\MutationResolvers;

use PoPCMSSchema\UserMetaMutations\MutationResolvers\PayloadableSetUserMetaMutationResolverTrait;

class PayloadableSetUserMetaMutationResolver extends AbstractMutateUserMetaMutationResolver
{
    use PayloadableSetUserMetaMutationResolverTrait;
}
