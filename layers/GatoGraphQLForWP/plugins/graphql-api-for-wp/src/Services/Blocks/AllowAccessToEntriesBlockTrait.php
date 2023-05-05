<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use GatoGraphQL\GatoGraphQL\Constants\BlockAttributeNames;
use GatoGraphQL\GatoGraphQL\StaticHelpers\BehaviorHelpers;
use PoPSchema\SchemaCommons\Constants\Behaviors;

trait AllowAccessToEntriesBlockTrait
{
    /**
     * Pass localized data to the block
     *
     * @return array<string,mixed>
     */
    protected function getDefaultBehaviorLocalizedData(): array
    {
        return [
            'defaultBehavior' => $this->getDefaultBehavior(),
        ];
    }

    protected function getDefaultBehavior(): string
    {
        $useRestrictiveDefaults = BehaviorHelpers::areRestrictiveDefaultsEnabled();
        return $useRestrictiveDefaults ? Behaviors::ALLOW : Behaviors::DENY;
    }

    /**
     * @param array<string,mixed> $attributes
     */
    protected function renderAllowAccessToEntriesBlock(array $attributes): string
    {
        $placeholder = '<p><strong>%s</strong></p>%s';
        $entries = $attributes[BlockAttributeNames::ENTRIES] ?? [];
        $behavior = $attributes[BlockAttributeNames::BEHAVIOR] ?? $this->getDefaultBehavior();
        return sprintf(
            $placeholder,
            $this->getRenderBlockLabel(),
            $entries ?
                sprintf(
                    '<ul><li><code>%s</code></li></ul>',
                    implode('</code></li><li><code>', $entries)
                ) :
                sprintf(
                    '<p><em>%s</em></p>',
                    \__('(not set)', 'gato-graphql')
                )
        ) . sprintf(
            $placeholder,
            $this->__('Behavior', 'gato-graphql'),
            match ($behavior) {
                Behaviors::ALLOW => sprintf('✅ %s', $this->__('Allow access', 'gato-graphql')),
                Behaviors::DENY => sprintf('❌ %s', $this->__('Deny access', 'gato-graphql')),
                default => $behavior,
            }
        );
    }

    abstract protected function getRenderBlockLabel(): string;
}
