<?php

declare(strict_types=1);

namespace PoP\ModuleRouting;

use PoP\Hooks\HooksAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractRouteModuleProcessor
{
    protected ?HooksAPIInterface $hooksAPI = null;

    #[Required]
    final public function autowireAbstractRouteModuleProcessor(
        HooksAPIInterface $hooksAPI,
    ): void {
        $this->hooksAPI = $hooksAPI;
    }

    /**
     * @return string[]
     */
    public function getGroups(): array
    {
        return array();
    }

    /**
     * @return array<string, array<string, array<array>>>
     */
    public function getModulesVarsPropertiesByNatureAndRoute(): array
    {
        return array();
    }

    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        return array();
    }

    /**
     * @return array<array<string, string[]>>
     */
    public function getModulesVarsProperties(): array
    {
        return array();
    }
}
