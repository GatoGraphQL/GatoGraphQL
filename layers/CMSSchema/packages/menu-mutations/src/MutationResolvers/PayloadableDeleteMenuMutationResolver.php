<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\MutationResolvers;

class PayloadableDeleteMenuMutationResolver extends DeleteMenuMutationResolver
{
    use PayloadableDeleteMenuMutationResolverTrait;
}
