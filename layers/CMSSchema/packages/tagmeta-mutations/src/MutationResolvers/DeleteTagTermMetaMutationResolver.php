<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\MutationResolvers;

use PoPCMSSchema\TagMetaMutations\MutationResolvers\DeleteTagTermMetaMutationResolverTrait;

class DeleteTagTermMetaMutationResolver extends AbstractMutateTagTermMetaMutationResolver
{
    use DeleteTagTermMetaMutationResolverTrait;
}
