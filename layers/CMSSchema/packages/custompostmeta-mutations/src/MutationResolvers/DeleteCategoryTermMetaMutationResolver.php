<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\DeleteCustomPostMetaMutationResolverTrait;

class DeleteCustomPostMetaMutationResolver extends AbstractMutateCustomPostMetaMutationResolver
{
    use DeleteCustomPostMetaMutationResolverTrait;
}
