<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\DeleteCustomPostMetaMutationResolverTrait;

class DeleteCustomPostMetaMutationResolver extends AbstractMutateCustomPostMetaMutationResolver
{
    use DeleteCustomPostMetaMutationResolverTrait;
}
