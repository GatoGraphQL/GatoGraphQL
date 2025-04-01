<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\MutationResolvers;

use PoPCMSSchema\CommentMetaMutations\MutationResolvers\PayloadableDeleteCommentMetaMutationResolverTrait;

class PayloadableDeleteCommentMetaMutationResolver extends AbstractMutateCommentMetaMutationResolver
{
    use PayloadableDeleteCommentMetaMutationResolverTrait;
}
