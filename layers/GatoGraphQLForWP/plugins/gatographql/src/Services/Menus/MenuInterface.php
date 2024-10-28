<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Menus;

use PoP\Root\Services\AutomaticallyInstantiatedServiceInterface;

interface MenuInterface extends AutomaticallyInstantiatedServiceInterface
{
    public function getName(): string;
    public function addMenuPage(): void;
    public function getMenuName(): string;
}
