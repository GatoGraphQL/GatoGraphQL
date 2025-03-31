<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\MutationResolvers;

use PoPCMSSchema\TagMetaMutations\MutationResolvers\PayloadableDeleteTagTermMetaMutationResolverTrait;

class PayloadableDeleteTagTermMetaMutationResolver extends AbstractMutateTagTermMetaMutationResolver
{
    use PayloadableDeleteTagTermMetaMutationResolverTrait;
}
