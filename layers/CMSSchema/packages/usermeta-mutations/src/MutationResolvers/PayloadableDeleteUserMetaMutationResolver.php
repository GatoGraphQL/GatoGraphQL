<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\MutationResolvers;

use PoPCMSSchema\UserMetaMutations\MutationResolvers\PayloadableDeleteUserMetaMutationResolverTrait;

class PayloadableDeleteUserMetaMutationResolver extends AbstractMutateUserMetaMutationResolver
{
    use PayloadableDeleteUserMetaMutationResolverTrait;
}
