<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources;

class DataToAppendAndRemoveDataSource
{
    /**
     * @return array<string, mixed>
     */
    public function getDataToAppend(): array
    {
        // Install also the monorepo-builder! So it can be used in CI
        return [
            'require-dev' => [
                'symplify/monorepo-builder' => '^10.0',
                'friendsofphp/php-cs-fixer' => '^3.5',
                'slevomat/coding-standard' => '^7.0',
                /**
                 * BrainFaker has no releases yet, so point to dev-master
                 * (under "leoloso/BrainFaker" repository, so it's under control)
                 *
                 * @see https://github.com/leoloso/BrainFaker
                 */
                'brain/faker' => 'dev-master',
            ],
            'autoload' => [
                'psr-4' => [
                    'PoP\\PoP\\' => 'src',
                ],
            ],
            'repositories' => [
                [
                    'type' => 'vcs',
                    'url' => 'https://github.com/leoloso/monorepo-builder.git',
                ],
                [
                    'type' => 'vcs',
                    /**
                     * Override `Brain-WP/BrainFaker` to use dependency "fakerphp/faker",
                     * which works with PHP 8.
                     *
                     * @see https://github.com/leoloso/BrainFaker/commit/7747c4d684c44e94d94ea951372c2b62b7a7755d
                     */
                    'url' => 'https://github.com/leoloso/BrainFaker.git',
                ],
            ],
            // 'extra' => [
            //     'installer-paths' => [
            //         'wordpress/wp-content/plugins/{$name}/' => [
            //             'type:wordpress-plugin',
            //         ]
            //     ]
            // ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function getDataToRemove(): array
    {
        return [
            'require-dev' => [
                // 'phpunit/phpunit' => '*',
                'wpackagist-plugin/block-metadata' => '*',
            ],
            // 'minimum-stability' => 'dev',
            // 'prefer-stable' => true,
        ];
    }
}
