<?php

declare(strict_types=1);

namespace PoPSchema\Comments\TypeResolvers\InputObjectType;

use PoP\Root\App;
use PoPSchema\Comments\Component;
use PoPSchema\Comments\ComponentConfiguration;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\PaginationInputObjectTypeResolver;

class CustomPostCommentPaginationInputObjectTypeResolver extends PaginationInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostCommentPaginationInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to paginate custom post comments', 'comments');
    }

    protected function getDefaultLimit(): ?int
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getCustomPostCommentOrCommentResponseListDefaultLimit();
    }

    protected function getMaxLimit(): ?int
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getCommentListMaxLimit();
    }
}
