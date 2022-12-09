<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\ConditionalOnModule\Users\Overrides\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType\GenericCustomPostObjectTypeFieldResolverTrait;

class GenericCustomPostObjectTypeFieldResolver extends AbstractAddCommentToCustomPostObjectTypeFieldResolver
{
    use GenericCustomPostObjectTypeFieldResolverTrait;
}
