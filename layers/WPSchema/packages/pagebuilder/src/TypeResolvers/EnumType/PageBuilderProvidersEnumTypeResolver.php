<?php

declare(strict_types=1);

namespace PoPWPSchema\PageBuilder\TypeResolvers\EnumType;

use PoPWPSchema\PageBuilder\Services\PageBuilderServiceInterface;
use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;

class PageBuilderProvidersEnumTypeResolver extends AbstractEnumTypeResolver
{
    private ?PageBuilderServiceInterface $pageBuilderService = null;

    final protected function getPageBuilderService(): PageBuilderServiceInterface
    {
        if ($this->pageBuilderService === null) {
            /** @var PageBuilderServiceInterface */
            $pageBuilderService = $this->instanceManager->getInstance(PageBuilderServiceInterface::class);
            $this->pageBuilderService = $pageBuilderService;
        }
        return $this->pageBuilderService;
    }

    public function getTypeName(): string
    {
        return 'PageBuilderProvidersEnum';
    }
    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return array_keys($this->getPageBuilderService()->getProviderNameServices());
    }
}
