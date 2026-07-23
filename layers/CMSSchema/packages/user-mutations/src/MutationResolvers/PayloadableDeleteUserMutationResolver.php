<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\MutationResolvers;

class PayloadableDeleteUserMutationResolver extends DeleteUserMutationResolver
{
    use PayloadableDeleteUserMutationResolverTrait;
}
