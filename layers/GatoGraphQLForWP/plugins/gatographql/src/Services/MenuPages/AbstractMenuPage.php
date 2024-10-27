<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\AppHelpers;
use GatoGraphQL\GatoGraphQL\Services\Helpers\EndpointHelpers;
use GatoGraphQL\GatoGraphQL\Services\Helpers\MenuPageHelper;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractMenuPage extends AbstractAutomaticallyInstantiatedService implements MenuPageInterface
{
    use BasicServiceTrait;

    protected ?string $hookName = null;

    private ?MenuPageHelper $menuPageHelper = null;
    private ?EndpointHelpers $endpointHelpers = null;

    final protected function getMenuPageHelper(): MenuPageHelper
    {
        if ($this->menuPageHelper === null) {
            /** @var MenuPageHelper */
            $menuPageHelper = $this->instanceManager->getInstance(MenuPageHelper::class);
            $this->menuPageHelper = $menuPageHelper;
        }
        return $this->menuPageHelper;
    }
    final protected function getEndpointHelpers(): EndpointHelpers
    {
        if ($this->endpointHelpers === null) {
            /** @var EndpointHelpers */
            $endpointHelpers = $this->instanceManager->getInstance(EndpointHelpers::class);
            $this->endpointHelpers = $endpointHelpers;
        }
        return $this->endpointHelpers;
    }

    public function setHookName(string $hookName): void
    {
        $this->hookName = $hookName;
    }

    public function getHookName(): ?string
    {
        return $this->hookName;
    }

    /**
     * Only initialize once, for the main AppThread
     */
    public function isServiceEnabled(): bool
    {
        return AppHelpers::isMainAppThread();
    }

    /**
     * Initialize menu page. Function to override
     */
    public function initialize(): void
    {
        \add_action(
            'admin_enqueue_scripts',
            $this->maybeEnqueueAssets(...)
        );
    }

    /**
     * Maybe enqueue the required assets and initialize the localized scripts
     */
    public function maybeEnqueueAssets(): void
    {
        // Enqueue assets if we are on that page
        if ($this->isCurrentScreen()) {
            $this->enqueueAssets();
        }
    }

    public function getMenuName(): string
    {
        return $this->getMenu()->getName();
    }

    protected function isCurrentScreen(): bool
    {
        // Check we are on the specific screen
        $currentScreen = \get_current_screen();
        if ($currentScreen === null) {
            return false;
        }
        $screenID = $this->getScreenID();
        // If it is the top level page, the current screen is prepended with "toplevel_page_"
        // If not, the current screen is prepended with the section name
        // Then, check that the screen ends with the requested screen ID
        return substr($currentScreen->id, -1 * strlen($screenID)) == $screenID;
    }

    public function getScreenID(): string
    {
        return sprintf(
            '%s_%s',
            $this->getMenuName(),
            $this->getMenuPageSlug()
        );
    }

    /**
     * Enqueue the required assets and initialize the localized scripts
     */
    protected function enqueueAssets(): void
    {
    }
}
