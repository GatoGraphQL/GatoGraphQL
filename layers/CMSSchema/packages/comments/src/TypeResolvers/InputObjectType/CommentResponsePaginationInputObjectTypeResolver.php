<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\TypeResolvers\InputObjectType;

use PoP\Root\App;
use PoPCMSSchema\Comments\Module;
use PoPCMSSchema\Comments\ModuleConfiguration;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\PaginationInputObjectTypeResolver;

class CommentResponsePaginationInputObjectTypeResolver extends PaginationInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CommentResponsePaginationInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to paginate response comments', 'comments');
    }

    protected function getDefaultLimit(): ?int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getCustomPostCommentOrCommentResponseListDefaultLimit();
    }

    protected function getMaxLimit(): ?int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getCommentListMaxLimit();
    }
}
