<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Services\WithInstanceManagerServiceTrait;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractFieldResolver implements FieldResolverInterface
{
    use WithInstanceManagerServiceTrait;
    
    protected ?HooksAPIInterface $hooksAPI = null;

    public function setHooksAPI(HooksAPIInterface $hooksAPI): void
    {
        $this->hooksAPI = $hooksAPI;
    }
    protected function getHooksAPI(): HooksAPIInterface
    {
        return $this->hooksAPI ??= $this->getInstanceManager()->getInstance(HooksAPIInterface::class);
    }

    //#[Required]
    final public function autowireAbstractFieldResolver(
        HooksAPIInterface $hooksAPI,
    ): void {
        $this->hooksAPI = $hooksAPI;
    }

    public function getAdminFieldNames(): array
    {
        return [];
    }
}
