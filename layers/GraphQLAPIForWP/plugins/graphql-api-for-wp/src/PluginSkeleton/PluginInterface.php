<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

interface PluginInterface
{
    public function setup(): void;
    
    /**
     * Execute logic after the plugin/extension has just been activated
     */
    public function pluginJustActivated(): void;

    /**
     * Execute logic after the plugin/extension has just been updated
     */
    public function pluginJustUpdated(string $storedVersion): void;

    /**
     * Plugin name
     */
    public function getPluginName(): string;

    /**
     * Plugin base name
     */
    public function getPluginBaseName(): string;

    /**
     * Plugin main file
     */
    public function getPluginFile(): string;

    /**
     * Plugin version
     */
    public function getPluginVersion(): string;

    /**
     * Plugin dir
     */
    public function getPluginDir(): string;

    /**
     * Plugin URL
     */
    public function getPluginURL(): string;

    /**
     * PluginInfo class for the Plugin
     */
    public function getInfo(): ?PluginInfoInterface;
}
