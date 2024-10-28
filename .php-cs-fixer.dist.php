<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/layers/API/packages/*/src',
        __DIR__ . '/layers/API/packages/*/tests',
        __DIR__ . '/layers/Backbone/packages/*/src',
        __DIR__ . '/layers/Backbone/packages/*/tests',
        __DIR__ . '/layers/CMSSchema/packages/*/src',
        __DIR__ . '/layers/CMSSchema/packages/*/tests',
        __DIR__ . '/layers/Engine/packages/*/src',
        __DIR__ . '/layers/Engine/packages/*/tests',
        __DIR__ . '/layers/GatoGraphQLForWP/packages/*/src',
        __DIR__ . '/layers/GatoGraphQLForWP/packages/*/tests',
        __DIR__ . '/layers/GatoGraphQLForWP/plugins/*/src',
        __DIR__ . '/layers/GatoGraphQLForWP/plugins/*/tests',
        __DIR__ . '/layers/GatoGraphQLStandaloneForWP/plugin-packages/*/src',
        __DIR__ . '/layers/GatoGraphQLStandaloneForWP/plugin-packages/*/tests',
        __DIR__ . '/layers/GraphQLByPoP/packages/*/src',
        __DIR__ . '/layers/GraphQLByPoP/packages/*/tests',
        __DIR__ . '/layers/Schema/packages/*/src',
        __DIR__ . '/layers/Schema/packages/*/tests',
        __DIR__ . '/layers/WPSchema/packages/*/src',
        __DIR__ . '/layers/WPSchema/packages/*/tests',
    ])
;

$config = new PhpCsFixer\Config();
return $config->setRules([
        'no_unused_imports' => true,
    ])
    ->setFinder($finder)
;
