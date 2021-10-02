<?php

declare(strict_types=1);

namespace PoPSchema\PostMediaMutations\SchemaHooks;

use PoPSchema\CustomPostMediaMutations\Hooks\AbstractCustomPostMutationResolverHookSet;
use PoPSchema\PostMutations\SchemaHooks\PostMutationResolverHookSetTrait;

class PostMutationResolverHookSet extends AbstractCustomPostMutationResolverHookSet
{
    use PostMutationResolverHookSetTrait;
}
