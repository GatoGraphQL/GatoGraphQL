<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\MutationResolvers;

use PoPCMSSchema\TagMutations\MutationResolvers\PayloadableCreateTagTermMutationResolverTrait;

class PayloadableCreateGenericTagTermMutationResolver extends AbstractMutateGenericTagTermMutationResolver
{
    use PayloadableCreateTagTermMutationResolverTrait;
}
