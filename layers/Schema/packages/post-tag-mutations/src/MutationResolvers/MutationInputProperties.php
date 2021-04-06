<?php

declare(strict_types=1);

namespace PoPSchema\PostTagMutations\MutationResolvers;

class MutationInputProperties
{
    public const POST_ID = 'postID';
    public const POST_TAG_IDS = 'postTagIDs';
    public const APPEND_POST_TAGS = 'appendPostTags';
}
