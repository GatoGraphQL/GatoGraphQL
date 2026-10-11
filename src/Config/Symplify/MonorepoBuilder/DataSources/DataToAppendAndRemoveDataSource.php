<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources;

class DataToAppendAndRemoveDataSource
{
    /**
     * @return array<string,mixed>
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
                'symplify/monorepo-builder' => '^10.2.2',
                'friendsofphp/php-cs-fixer' => '^3.5',
                'slevomat/coding-standard' => '^7.0',
                'wp-cli/i18n-command' => '^2.7',
                /**
                 * wp-cli/wp-cli has no stable 2.13 release, and its `main`
                 * branch moved to 3.0, while wp-cli/i18n-command 2.7.3
                 * requires `^2.13`. Pin the last 2.13 commit of `main`.
                 */
                'wp-cli/wp-cli' => 'dev-main#7b3a8f56ee053ef74e325d554ea6352d699fe506 as 2.13.x-dev',
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
                    'url' => 'https://github.com/leoloso/symplify-composer-json-manipulator.git',
                ],
                [
                    'type' => 'vcs',
                    'url' => 'https://github.com/leoloso/symplify-easy-testing.git',
                ],
                [
                    'type' => 'vcs',
                    'url' => 'https://github.com/leoloso/symplify-smart-file-system.git',
                ],
                [
                    'type' => 'vcs',
                    'url' => 'https://github.com/leoloso/symplify-symplify-kernel.git',
                ],
                [
                    'type' => 'vcs',
                    'url' => 'https://github.com/leoloso/symplify-autowire-array-parameter.git',
                ],
                /**
                 * Also override "symplify/package-builder" because its dependency
                 * of "sebastian/diff" is on "^4.0", which does not let PHPUnit v10
                 * get installed. "leoloso/package-builder" upgrades it to "^5.0"
                 */
                [
                    'type' => 'vcs',
                    'url' => 'https://github.com/leoloso/package-builder.git',
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
     * @return array<string,mixed>
     */
    public function getDataToRemove(): array
    {
        return [
            // 'minimum-stability' => 'dev',
            // 'prefer-stable' => true,
        ];
    }
}
