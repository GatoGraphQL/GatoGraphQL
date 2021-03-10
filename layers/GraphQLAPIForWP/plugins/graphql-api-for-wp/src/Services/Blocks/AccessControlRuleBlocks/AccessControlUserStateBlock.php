<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks\AccessControlRuleBlocks;

use GraphQLAPI\GraphQLAPI\Services\Blocks\GraphQLByPoPBlockTrait;
use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\AccessControlFunctionalityModuleResolver;
use PoPSchema\UserStateAccessControl\ConfigurationEntries\UserStates;

/**
 * Access Control Disable Access block
 */
class AccessControlUserStateBlock extends AbstractAccessControlRuleBlock
{
    use GraphQLByPoPBlockTrait;

    protected function getBlockName(): string
    {
        return 'access-control-user-state';
    }

    protected function getEnablingModule(): ?string
    {
        return AccessControlFunctionalityModuleResolver::ACCESS_CONTROL_RULE_USER_STATE;
    }

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function renderBlock(array $attributes, string $content): string
    {
        $label = isset($attributes[self::ATTRIBUTE_NAME_VALUE]) && $attributes[self::ATTRIBUTE_NAME_VALUE] == UserStates::IN ?
            __('Logged-in users', 'graphql-api') :
            __('Not logged-in users', 'graphql-api');
        return sprintf(
            '<ul class="%s"><li>%s</li></ul>',
            $this->getBlockClassName(),
            $label
        );
    }
}
