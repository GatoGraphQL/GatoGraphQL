<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\MutationResolvers;

class PayloadableDeleteMediaItemMutationResolver extends DeleteMediaItemMutationResolver
{
    use PayloadableDeleteMediaItemMutationResolverTrait;
}
