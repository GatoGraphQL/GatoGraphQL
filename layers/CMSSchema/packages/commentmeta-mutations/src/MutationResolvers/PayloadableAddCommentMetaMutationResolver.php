<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\MutationResolvers;

use PoPCMSSchema\CommentMetaMutations\MutationResolvers\PayloadableAddCommentMetaMutationResolverTrait;

class PayloadableAddCommentMetaMutationResolver extends AbstractMutateCommentMetaMutationResolver
{
    use PayloadableAddCommentMetaMutationResolverTrait;
}
