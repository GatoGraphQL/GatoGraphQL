<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\MutationResolvers;

use PoPCMSSchema\CommentMetaMutations\MutationResolvers\PayloadableUpdateCommentMetaMutationResolverTrait;

class PayloadableUpdateCommentMetaMutationResolver extends AbstractMutateCommentMetaMutationResolver
{
    use PayloadableUpdateCommentMetaMutationResolverTrait;
}
