<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\Services\Helpers\MenuPageHelper;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Services\BasicServiceTrait;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractMenuPage extends AbstractAutomaticallyInstantiatedService implements MenuPageInterface
{
    use BasicServiceTrait;
    
    protected ?string $hookName = null;

    protected ?MenuPageHelper $menuPageHelper = null;
    protected ?EndpointHelpers $endpointHelpers = null;

    public function setMenuPageHelper(MenuPageHelper $menuPageHelper): void
    {
        $this->menuPageHelper = $menuPageHelper;
    }
    protected function getMenuPageHelper(): MenuPageHelper
    {
        return $this->menuPageHelper ??= $this->getInstanceManager()->getInstance(MenuPageHelper::class);
    }
    public function setEndpointHelpers(EndpointHelpers $endpointHelpers): void
    {
        $this->endpointHelpers = $endpointHelpers;
    }
    protected function getEndpointHelpers(): EndpointHelpers
    {
        return $this->endpointHelpers ??= $this->getInstanceManager()->getInstance(EndpointHelpers::class);
    }

    //#[Required]
    final public function autowireAbstractMenuPage(MenuPageHelper $menuPageHelper, EndpointHelpers $endpointHelpers): void
    {
        $this->menuPageHelper = $menuPageHelper;
        $this->endpointHelpers = $endpointHelpers;
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
     * Initialize menu page. Function to override
     */
    public function initialize(): void
    {
        \add_action(
            'admin_enqueue_scripts',
            [$this, 'maybeEnqueueAssets']
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
        if (is_null($currentScreen)) {
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
