<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\TypeResolvers\InputObjectType;

class FilterCommentsByCustomPostAuthorInputObjectTypeResolver extends AbstractFilterCommentsByAuthorInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'FilterCommentsByCustomPostAuthorInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Filter comments by custom post author', 'comments');
    }

    protected function getAuthorIDsFilteringQueryArgName(): string
    {
        return 'custompost-author-ids';
    }

    protected function getExcludeAuthorIDsFilteringQueryArgName(): string
    {
        return 'exclude-custompost-author-ids';
    }   
}
