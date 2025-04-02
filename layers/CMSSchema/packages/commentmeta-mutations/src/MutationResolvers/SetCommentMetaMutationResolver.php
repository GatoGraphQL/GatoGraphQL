<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\MutationResolvers;

use PoPCMSSchema\CommentMetaMutations\MutationResolvers\SetCommentMetaMutationResolverTrait;

class SetCommentMetaMutationResolver extends AbstractMutateCommentMetaMutationResolver
{
    use SetCommentMetaMutationResolverTrait;
}
