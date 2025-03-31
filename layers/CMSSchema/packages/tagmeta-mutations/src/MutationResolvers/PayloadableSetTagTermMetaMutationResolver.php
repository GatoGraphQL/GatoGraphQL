<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\MutationResolvers;

use PoPCMSSchema\TagMetaMutations\MutationResolvers\PayloadableSetTagTermMetaMutationResolverTrait;

class PayloadableSetTagTermMetaMutationResolver extends AbstractMutateTagTermMetaMutationResolver
{
    use PayloadableSetTagTermMetaMutationResolverTrait;
}
