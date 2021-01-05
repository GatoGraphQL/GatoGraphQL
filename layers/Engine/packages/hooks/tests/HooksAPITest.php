<?php

declare(strict_types=1);

namespace PoP\Hooks;

use PoP\Hooks\HooksAPIInterface;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Hooks\ContractImplementations\HooksAPI;
use PoP\Root\Container\ContainerBuilderFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class HooksAPITest extends \PHPUnit\Framework\TestCase
{
    public function __construct()
    {
        parent::__construct();
        /** @var ContainerBuilder */
        $containerBuilder = ContainerBuilderFactory::getInstance();
        $containerBuilder
            ->register(HooksAPIInterface::class, HooksAPI::class);
    }
    /**
     * Test that applyFilter returns $value
     */
    public function testApplyFilters(): void
    {
        $hooksapi = HooksAPIFacade::getInstance();
        $this->assertEquals(
            'bar',
            $hooksapi->applyFilters('foo', 'bar')
        );
        $this->assertEquals(
            'baz',
            $hooksapi->applyFilters('foo', 'baz')
        );
    }
}
