<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\MutationResolvers\PayloadableUpdateCustomPostMutationResolverTrait;

class PayloadableUpdateGenericCustomPostMutationResolver extends AbstractCreateOrUpdateGenericCustomPostMutationResolver
{
    use PayloadableUpdateCustomPostMutationResolverTrait;
}
