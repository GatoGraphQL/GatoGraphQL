<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\MutationResolvers;

use PoPCMSSchema\CommentMetaMutations\MutationResolvers\DeleteCommentMetaMutationResolverTrait;

class DeleteCommentMetaMutationResolver extends AbstractMutateCommentMetaMutationResolver
{
    use DeleteCommentMetaMutationResolverTrait;
}
