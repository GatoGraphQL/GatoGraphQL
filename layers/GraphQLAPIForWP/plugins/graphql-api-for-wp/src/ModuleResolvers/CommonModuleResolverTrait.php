<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

trait CommonModuleResolverTrait
{
    /**
     * @param string[]|null $applicableItems
     */
    protected function getDefaultValueDescription(
        string $blockTitle,
        ?array $applicableItems = null
    ): string {
        $applicableItems ??= $this->getDefaultValueApplicableItems($blockTitle);
        return $this->getSettingsInfoContent(
            sprintf(
                \__('%s %s', 'graphql-api'),
                \__('This is the default value for the schema configuration.', 'graphql-api'),
                $this->getCollapsible(
                    sprintf(
                        '<br/>%s<ul><li>%s</li></ul>',
                        \__('It will be used whenever:', 'graphql-api'),
                        implode(
                            '</li><li>',
                            $applicableItems
                        )
                    ),
                )
            )
        );
    }

    /**
     * @return string[]
     */
    protected function getDefaultValueApplicableItems(string $blockTitle): array
    {
        return [
            \__('The endpoint does not have a Schema Configuration assigned to it', 'graphql-api'),
            \__('The endpoint has Schema Configuration <code>"None"</code> assigned to it', 'graphql-api'),
            sprintf(
                \__('Block <code>%s</code> has not been added to the selected Schema Configuration', 'graphql-api'),
                $blockTitle
            ),
            \__('The block in the Schema Configuration has value <code>"Default"</code>', 'graphql-api')
        ];
    }

    protected function getPressCtrlToSelectMoreThanOneOptionLabel(): string
    {
        return \__('Press <code>ctrl</code> or <code>shift</code> keys to select more than one.', 'graphql-api');
    }

    protected function getCollapsible(
        string $content,
        ?string $showDetailsLabel = null,
    ): string {
        return sprintf(
            '<a href="#" type="button" class="collapsible">%s</a><span class="collapsible-content">%s</span>',
            $showDetailsLabel ?? \__('Show details', 'graphql-api'),
            $content
        );
    }

    protected function getSettingsInfoContent(string $content): string
    {
        return sprintf(
            '<span class="settings-info">%s</span>',
            $content
        );
    }
}
