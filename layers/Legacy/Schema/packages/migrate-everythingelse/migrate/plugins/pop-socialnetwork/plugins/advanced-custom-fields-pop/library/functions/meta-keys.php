<?php

\PoP\Root\App::getHookManager()->addFilter('gdAcfGetKeysStoreAsArray', 'gdSocialnetworkAcfGetKeysStoreAsArray');
function gdSocialnetworkAcfGetKeysStoreAsArray($keys)
{
    $keys[] = GD_METAKEY_POST_RECOMMENDEDBY;
    $keys[] = GD_METAKEY_POST_UPVOTEDBY;
    $keys[] = GD_METAKEY_POST_DOWNVOTEDBY;
    $keys[] = GD_METAKEY_POST_TAGGEDUSERS;

    $keys[] = GD_METAKEY_PROFILE_FOLLOWSUSERS;
    $keys[] = GD_METAKEY_PROFILE_RECOMMENDSPOSTS;
    $keys[] = GD_METAKEY_PROFILE_SUBSCRIBESTOTAGS;
    $keys[] = GD_METAKEY_PROFILE_UPVOTESPOSTS;
    $keys[] = GD_METAKEY_PROFILE_DOWNVOTESPOSTS;
    $keys[] = GD_METAKEY_PROFILE_FOLLOWEDBY;

    $keys[] = GD_METAKEY_TERM_SUBSCRIBEDBY;
    return $keys;
}
