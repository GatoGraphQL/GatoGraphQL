<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\MutationResolvers;

use PoPCMSSchema\TagMutations\MutationResolvers\PayloadableDeleteTagTermMutationResolverTrait;

class PayloadableDeleteGenericTagTermMutationResolver extends AbstractMutateGenericTagTermMutationResolver
{
    use PayloadableDeleteTagTermMutationResolverTrait;
}
