<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

class UpdateGenericCustomPostMutationResolver extends AbstractCreateOrUpdateGenericCustomPostMutationResolver
{
    use UpdateCustomPostMutationResolverTrait;
}
