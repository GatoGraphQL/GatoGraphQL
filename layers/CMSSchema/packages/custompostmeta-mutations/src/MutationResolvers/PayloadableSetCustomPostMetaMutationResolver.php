<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\PayloadableSetCustomPostMetaMutationResolverTrait;

class PayloadableSetCustomPostMetaMutationResolver extends AbstractMutateCustomPostMetaMutationResolver
{
    use PayloadableSetCustomPostMetaMutationResolverTrait;
}
