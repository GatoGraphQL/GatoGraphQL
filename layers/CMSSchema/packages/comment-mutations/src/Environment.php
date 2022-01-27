<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations;

class Environment
{
    public const MUST_USER_BE_LOGGED_IN_TO_ADD_COMMENT = 'MUST_USER_BE_LOGGED_IN_TO_ADD_COMMENT';
    public const REQUIRE_COMMENTER_NAME_AND_EMAIL = 'REQUIRE_COMMENTER_NAME_AND_EMAIL';
}
