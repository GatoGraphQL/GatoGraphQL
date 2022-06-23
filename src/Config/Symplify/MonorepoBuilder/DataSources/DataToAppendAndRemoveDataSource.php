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
                /**
                 * Last working version of the MonorepoBuilder before upgraded to using MBConfig
                 * (after which the library can't be used directly from source anymore).
                 *
                 * @see https://github.com/symplify/symplify/issues/4184
                 */                
                'symplify/monorepo-builder' => '10.2.2',
                'friendsofphp/php-cs-fixer' => '^3.5',
                'slevomat/coding-standard' => '^7.0',
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
