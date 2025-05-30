<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\PayloadableDeleteCustomPostMetaMutationResolverTrait;

class PayloadableDeleteCustomPostMetaMutationResolver extends AbstractMutateCustomPostMetaMutationResolver
{
    use PayloadableDeleteCustomPostMetaMutationResolverTrait;
}
