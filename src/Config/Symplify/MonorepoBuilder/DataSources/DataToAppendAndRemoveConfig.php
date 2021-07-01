<?php

declare(strict_types=1);

namespace PoP\PoP\Config\Symplify\MonorepoBuilder\DataSources;

class DataToAppendAndRemoveConfig
{
    /**
     * @return array<string, mixed>
     */
    public function getDataToAppend(): array
    {
        // Install also the monorepo-builder! So it can be used in CI
        return [
            'require-dev' => [
                'symplify/monorepo-builder' => '^9.0',
            ],
            'autoload' => [
                'psr-4' => [
                    'PoP\\PoP\\'=> 'src',
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
