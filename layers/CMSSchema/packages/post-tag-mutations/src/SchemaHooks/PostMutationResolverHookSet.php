<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\SchemaHooks;

use PoPSchema\CustomPostTagMutations\SchemaHooks\AbstractCustomPostMutationResolverHookSet;
use PoPSchema\PostMutations\SchemaHooks\PostMutationResolverHookSetTrait;

class PostMutationResolverHookSet extends AbstractCustomPostMutationResolverHookSet
{
    use PostMutationResolverHookSetTrait;
}
