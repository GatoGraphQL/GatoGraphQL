<?php

declare(strict_types=1);

namespace PoPSchema\Pages\TypeResolvers\InputObjectType;

use PoPSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostPaginationInputObjectTypeResolver;
use PoPSchema\Pages\ComponentConfiguration;

class PagePaginationInputObjectTypeResolver extends CustomPostPaginationInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PagePaginationInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('Input to paginate pages', 'pages');
    }

    protected function getDefaultLimit(): ?int
    {
        return ComponentConfiguration::getPageListDefaultLimit();
    }

    protected function getMaxLimit(): ?int
    {
        return ComponentConfiguration::getPageListMaxLimit();
    }
}
