<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\MutationResolvers;

class DeleteGenericTagTermMutationResolver extends AbstractMutateGenericTagTermMutationResolver
{
    use DeleteTagTermMutationResolverTrait;
}
