<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\MutationResolvers;

class PayloadableUpdateCommentMutationResolver extends UpdateCommentMutationResolver
{
    use PayloadableUpdateCommentMutationResolverTrait;
}
