<?php

declare(strict_types=1);

namespace PoPWPSchema\PageBuilder\TypeResolvers\EnumType;

use PoPWPSchema\PageBuilder\Services\PageBuilderInterface;
use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;

class PageBuilderProvidersEnumTypeResolver extends AbstractEnumTypeResolver
{
    private ?PageBuilderInterface $pageBuilder = null;

    final protected function getPageBuilder(): PageBuilderInterface
    {
        if ($this->pageBuilder === null) {
            /** @var PageBuilderInterface */
            $pageBuilder = $this->instanceManager->getInstance(PageBuilderInterface::class);
            $this->pageBuilder = $pageBuilder;
        }
        return $this->pageBuilder;
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
        return array_keys($this->getPageBuilder()->getProviderNameServices());
    }
}
