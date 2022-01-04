<?php

declare(strict_types=1);

namespace PoPSchema\Comments\TypeResolvers\InputObjectType;

use PoPSchema\Comments\Component;
use PoPSchema\Comments\ComponentConfiguration;
use PoPSchema\SchemaCommons\TypeResolvers\InputObjectType\PaginationInputObjectTypeResolver;

class CommentResponsePaginationInputObjectTypeResolver extends PaginationInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CommentResponsePaginationInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Input to paginate response comments', 'comments');
    }

    protected function getDefaultLimit(): ?int
    {
        return ComponentConfiguration::getCustomPostCommentOrCommentResponseListDefaultLimit();
    }

    protected function getMaxLimit(): ?int
    {
        return ComponentConfiguration::getCommentListMaxLimit();
    }
}
