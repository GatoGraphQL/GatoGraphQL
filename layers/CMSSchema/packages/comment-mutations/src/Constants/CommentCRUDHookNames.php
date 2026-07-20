<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\Constants;

class CommentCRUDHookNames
{
    public const VALIDATE_ADD_COMMENT = __CLASS__ . ':validate-add-comment';
    public const EXECUTE_ADD_COMMENT = __CLASS__ . ':execute-add-comment';
    public const GET_ADD_COMMENT_DATA = __CLASS__ . ':get-add-comment-data';
    public const VALIDATE_UPDATE_COMMENT = __CLASS__ . ':validate-update-comment';
    public const EXECUTE_UPDATE_COMMENT = __CLASS__ . ':execute-update-comment';
    public const GET_UPDATE_COMMENT_DATA = __CLASS__ . ':get-update-comment-data';
    public const VALIDATE_DELETE_COMMENT = __CLASS__ . ':validate-delete-comment';
    public const EXECUTE_DELETE_COMMENT = __CLASS__ . ':execute-delete-comment';
    public final const ERROR_PAYLOAD = __CLASS__ . ':error-payload';
}
