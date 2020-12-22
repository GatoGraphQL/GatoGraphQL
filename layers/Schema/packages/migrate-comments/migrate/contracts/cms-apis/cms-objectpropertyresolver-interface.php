<?php
namespace PoPSchema\Comments;

interface ObjectPropertyResolver
{
    public function getCommentContent($comment);
    public function getCommentPlainContent($comment);
    public function getCommentUserId($comment);
    public function getCommentPostId($comment);
    public function isCommentApproved($comment);
    public function getCommentType($comment);
    public function getCommentParent($comment);
    public function getCommentDateGmt($comment);
    public function getCommentId($comment);
    public function getCommentAuthor($comment);
    public function getCommentAuthorEmail($comment);
}
