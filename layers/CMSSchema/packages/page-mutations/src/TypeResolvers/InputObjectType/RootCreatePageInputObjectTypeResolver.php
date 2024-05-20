<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\AbstractCustomPostContentAsOneofInputObjectTypeResolver;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\RootCreateCustomPostInputObjectTypeResolver;

class RootCreatePageInputObjectTypeResolver extends RootCreateCustomPostInputObjectTypeResolver implements CreatePageInputObjectTypeResolverInterface
{
    private ?PageContentAsOneofInputObjectTypeResolver $pageContentAsOneofInputObjectTypeResolver = null;

    final public function setPageContentAsOneofInputObjectTypeResolver(PageContentAsOneofInputObjectTypeResolver $pageContentAsOneofInputObjectTypeResolver): void
    {
        $this->pageContentAsOneofInputObjectTypeResolver = $pageContentAsOneofInputObjectTypeResolver;
    }
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
        return 'RootCreatePageInput';
    }

    protected function getContentAsOneofInputObjectTypeResolver(): AbstractCustomPostContentAsOneofInputObjectTypeResolver
    {
        return $this->getPageContentAsOneofInputObjectTypeResolver();
    }
}
