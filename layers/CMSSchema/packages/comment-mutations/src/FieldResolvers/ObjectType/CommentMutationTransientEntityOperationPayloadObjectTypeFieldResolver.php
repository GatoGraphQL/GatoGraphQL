<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentReplyMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentUpdateMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CustomPostAddCommentMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\RootAddCommentToCustomPostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\RootReplyCommentMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\RootUpdateCommentMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractTransientEntityOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class CommentMutationTransientEntityOperationPayloadObjectTypeFieldResolver extends AbstractTransientEntityOperationPayloadObjectTypeFieldResolver
{
    protected function getObjectIDFieldName(): string
    {
        return 'commentID';
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootAddCommentToCustomPostMutationPayloadObjectTypeResolver::class,
            CustomPostAddCommentMutationPayloadObjectTypeResolver::class,
            RootReplyCommentMutationPayloadObjectTypeResolver::class,
            CommentReplyMutationPayloadObjectTypeResolver::class,
            RootUpdateCommentMutationPayloadObjectTypeResolver::class,
            CommentUpdateMutationPayloadObjectTypeResolver::class,
        ];
    }
}
