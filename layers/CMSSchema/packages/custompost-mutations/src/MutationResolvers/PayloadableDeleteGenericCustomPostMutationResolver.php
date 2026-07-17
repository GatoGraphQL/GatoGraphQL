<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

class PayloadableDeleteGenericCustomPostMutationResolver extends DeleteGenericCustomPostMutationResolver
{
    use PayloadableDeleteCustomPostMutationResolverTrait;
}
