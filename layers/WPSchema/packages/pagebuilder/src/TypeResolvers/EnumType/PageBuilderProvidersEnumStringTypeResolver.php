<?php

declare(strict_types=1);

namespace PoPWPSchema\PageBuilder\TypeResolvers\EnumType;

use PoPWPSchema\PageBuilder\Services\PageBuilderServiceInterface;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\AbstractEnumStringScalarTypeResolver;

class PageBuilderProvidersEnumStringTypeResolver extends AbstractEnumStringScalarTypeResolver
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
        return 'PageBuilderProvidersEnumString';
    }
    /**
     * @return string[]
     */
    public function getPossibleValues(): array
    {
        return array_keys($this->getPageBuilderService()->getProviderNameServices());
    }
}
