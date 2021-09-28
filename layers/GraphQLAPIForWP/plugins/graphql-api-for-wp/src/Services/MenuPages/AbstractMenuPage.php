<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use Symfony\Contracts\Service\Attribute\Required;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\Services\Helpers\MenuPageHelper;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\MenuPageInterface;
use GraphQLAPI\GraphQLAPI\Services\Menus\AbstractMenu;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

/**
 * Menu page
 */
abstract class AbstractMenuPage extends AbstractAutomaticallyInstantiatedService implements MenuPageInterface
{
    protected ?string $hookName = null;
    protected InstanceManagerInterface $instanceManager;
    protected MenuPageHelper $menuPageHelper;
    protected EndpointHelpers $endpointHelpers;

    #[Required]
    public function autowireAbstractMenuPage(InstanceManagerInterface $instanceManager, MenuPageHelper $menuPageHelper, EndpointHelpers $endpointHelpers)
    {
        $this->instanceManager = $instanceManager;
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

    abstract public function getMenuClass(): string;

    protected function getMenu(): AbstractMenu
    {
        $menuClass = $this->getMenuClass();
        /** @var AbstractMenu */
        return $this->instanceManager->getInstance($menuClass);
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

    abstract public function getMenuPageSlug(): string;

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
