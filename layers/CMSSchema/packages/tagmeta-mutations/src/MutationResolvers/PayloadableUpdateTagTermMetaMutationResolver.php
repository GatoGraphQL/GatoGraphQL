<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\MutationResolvers;

use PoPCMSSchema\TagMetaMutations\MutationResolvers\PayloadableUpdateTagTermMetaMutationResolverTrait;

class PayloadableUpdateTagTermMetaMutationResolver extends AbstractMutateTagTermMetaMutationResolver
{
    use PayloadableUpdateTagTermMetaMutationResolverTrait;
}
