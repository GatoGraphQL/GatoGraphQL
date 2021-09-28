<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Menus;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

/**
 * Admin menu class
 */
abstract class AbstractMenu extends AbstractAutomaticallyInstantiatedService
{
    protected InstanceManagerInterface $instanceManager;

    #[Required]
    public function autowireAbstractMenu(InstanceManagerInterface $instanceManager): void
    {
        $this->instanceManager = $instanceManager;
    }

    abstract public function getName(): string;
    abstract public function addMenuPage(): void;

    /**
     * Initialize the endpoints
     */
    public function initialize(): void
    {
        \add_action(
            'admin_menu',
            [$this, 'addMenuPage'],
            5
        );
    }
}
