<?php

namespace GraphQLAPI\PluginSkeleton;

class Extension extends AbstractExtension
{
    /**
     * Plugin main file
     */
    protected function getPluginFile(): string
    {
        return '';
    }

    /**
     * Plugin name
     */
    protected function getPluginName(): string
    {
        return 'Some extension for testing';
    }
}
