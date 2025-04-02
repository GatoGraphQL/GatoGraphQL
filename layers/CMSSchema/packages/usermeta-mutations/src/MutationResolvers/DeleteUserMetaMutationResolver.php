<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\MutationResolvers;

use PoPCMSSchema\UserMetaMutations\MutationResolvers\DeleteUserMetaMutationResolverTrait;

class DeleteUserMetaMutationResolver extends AbstractMutateUserMetaMutationResolver
{
    use DeleteUserMetaMutationResolverTrait;
}
