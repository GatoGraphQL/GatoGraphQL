<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\MutationResolvers;

use PoPCMSSchema\TagMutations\MutationResolvers\PayloadableUpdateTagTermMutationResolverTrait;

class PayloadableUpdateGenericTagTermMutationResolver extends AbstractMutateGenericTagTermMutationResolver
{
    use PayloadableUpdateTagTermMutationResolverTrait;
}
