<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\MutationResolvers;

use PoPCMSSchema\CommentMetaMutations\MutationResolvers\PayloadableSetCommentMetaMutationResolverTrait;

class PayloadableSetCommentMetaMutationResolver extends AbstractMutateCommentMetaMutationResolver
{
    use PayloadableSetCommentMetaMutationResolverTrait;
}
