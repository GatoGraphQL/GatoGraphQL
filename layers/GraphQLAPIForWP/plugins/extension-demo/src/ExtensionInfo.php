<?php

declare(strict_types=1);

namespace GraphQLAPI\ExtensionDemo;

use GraphQLAPI\GraphQLAPI\PluginSkeleton\AbstractExtensionInfo;

class ExtensionInfo extends AbstractExtensionInfo
{
    protected function initialize(): void
    {
        $this->values = [
            'someProp' => 'value',
        ];
    }

    /**
     * Example of storing custom information properties for the Extension
     */
    public function getSomeProp(): string
    {
        return $this->values['someProp'];
    }
}
