<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\CategoryMetaMutations\MutationResolvers\PayloadableSetCustomPostMetaMutationResolverTrait;

class PayloadableSetCustomPostMetaMutationResolver extends AbstractMutateCustomPostMetaMutationResolver
{
    use PayloadableSetCustomPostMetaMutationResolverTrait;
}
