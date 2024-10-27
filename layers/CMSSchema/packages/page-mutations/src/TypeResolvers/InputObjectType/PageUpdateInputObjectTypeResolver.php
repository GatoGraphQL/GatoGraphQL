<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\AbstractCustomPostContentAsOneofInputObjectTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\CustomPostUpdateInputObjectTypeResolver;

class PageUpdateInputObjectTypeResolver extends CustomPostUpdateInputObjectTypeResolver implements UpdatePageInputObjectTypeResolverInterface
{
    private ?PageContentAsOneofInputObjectTypeResolver $pageContentAsOneofInputObjectTypeResolver = null;

    final protected function getPageContentAsOneofInputObjectTypeResolver(): PageContentAsOneofInputObjectTypeResolver
    {
        if ($this->pageContentAsOneofInputObjectTypeResolver === null) {
            /** @var PageContentAsOneofInputObjectTypeResolver */
            $pageContentAsOneofInputObjectTypeResolver = $this->instanceManager->getInstance(PageContentAsOneofInputObjectTypeResolver::class);
            $this->pageContentAsOneofInputObjectTypeResolver = $pageContentAsOneofInputObjectTypeResolver;
        }
        return $this->pageContentAsOneofInputObjectTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'PageUpdateInput';
    }

    protected function getContentAsOneofInputObjectTypeResolver(): AbstractCustomPostContentAsOneofInputObjectTypeResolver
    {
        return $this->getPageContentAsOneofInputObjectTypeResolver();
    }
}
