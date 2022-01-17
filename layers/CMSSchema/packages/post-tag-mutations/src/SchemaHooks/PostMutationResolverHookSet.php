<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\SchemaHooks;

use PoPCMSSchema\CustomPostTagMutations\SchemaHooks\AbstractCustomPostMutationResolverHookSet;
use PoPCMSSchema\PostMutations\SchemaHooks\PostMutationResolverHookSetTrait;

class PostMutationResolverHookSet extends AbstractCustomPostMutationResolverHookSet
{
    use PostMutationResolverHookSetTrait;
}
