<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Unit\MarketplaceProviders;

use Brain\Monkey;
use GatoGraphQL\GatoGraphQL\MarketplaceProviders\FluentCartMarketplaceProviderServiceTrait;
use PHPUnit\Framework\TestCase;

use function Brain\Monkey\Functions\when;

class FluentCartMarketplaceProviderServiceTraitTest extends TestCase
{
    private const SITE_URL = 'https://example.com';
    private const USER_AGENT = 'GatoPlugins/20.1.0; https://example.com';

    protected function setUp(): void
    {
        parent::setUp();
        Monkey\setUp();

        when('home_url')->justReturn(self::SITE_URL);
        when('wp_parse_url')->alias('parse_url');
    }

    protected function tearDown(): void
    {
        Monkey\tearDown();
        parent::tearDown();
    }

    /**
     * @dataProvider getStoreURLs
     */
    public function testAddsTheUserAgentToStoreRequests(string $url): void
    {
        $this->assertSame(
            ['timeout' => 15, 'user-agent' => self::USER_AGENT],
            $this->addUserAgent(['timeout' => 15], $url),
        );
    }

    /**
     * @return array<string,array{string}>
     */
    public static function getStoreURLs(): array
    {
        return [
            'license check' => ['https://store.gatoplugins.com?fluent-cart=check_license'],
            'update check' => ['https://store.gatoplugins.com?fluent-cart=get_license_version'],
            'package download' => ['https://store.gatoplugins.com/?fluent-cart=download_license_package'],
        ];
    }

    /**
     * @dataProvider getOtherURLs
     */
    public function testLeavesOtherRequestsUntouched(string $url): void
    {
        $args = ['timeout' => 15, 'user-agent' => 'WordPress/7.1.4; https://example.com'];

        $this->assertSame(
            $args,
            $this->addUserAgent($args, $url),
        );
    }

    /**
     * @return array<string,array{string}>
     */
    public static function getOtherURLs(): array
    {
        return [
            'another site' => ['https://downloads.wordpress.org/plugin/akismet.zip'],
            'a host that starts like the store' => ['https://store.gatoplugins.com.example.org/'],
            'another subdomain' => ['https://gatoplugins.com/'],
        ];
    }

    /**
     * @param array<string,mixed> $args
     * @return array<string,mixed>
     */
    private function addUserAgent(array $args, string $url): array
    {
        $service = new class {
            use FluentCartMarketplaceProviderServiceTrait;

            protected function getFluentCartRequestPluginVersion(): string
            {
                return '20.1.0';
            }
        };
        return $service->addUserAgentToFluentCartRequest($args, $url);
    }
}
