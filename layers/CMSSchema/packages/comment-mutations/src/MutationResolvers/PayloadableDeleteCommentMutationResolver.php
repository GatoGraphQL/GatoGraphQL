<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\MutationResolvers;

class PayloadableDeleteCommentMutationResolver extends DeleteCommentMutationResolver
{
    use PayloadableDeleteCommentMutationResolverTrait;
}
