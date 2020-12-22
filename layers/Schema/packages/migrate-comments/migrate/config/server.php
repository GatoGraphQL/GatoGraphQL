<?php
namespace PoPSchema\Comments;

class Server
{
    public static function mustHaveUserAccountToAddComment()
    {
        // Must have PoP Users
        if (!defined('POP_USERS_INITIALIZED')) {
            return false;
        }

        if (defined('POP_COMMENTS_SERVER_MUSTHAVEUSERACCOUNTTOADDCOMMENT')) {
            return POP_COMMENTS_SERVER_MUSTHAVEUSERACCOUNTTOADDCOMMENT;
        }

        return true;
    }
}
