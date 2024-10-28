<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\CustomPostTypes;

use PoP\Root\Services\ServiceInterface;

interface CustomPostTypeInterface extends ServiceInterface
{
    public function getCustomPostType(): string;
    /**
     * Unregister the custom post type
     */
    public function unregisterCustomPostType(): void;
    /**
     * Register the custom post type
     */
    public function registerCustomPostType(): void;
    public function isHierarchical(): bool;
    public function useCustomPostExcerptAsDescription(): bool;
    /**
     * Show in menu
     */
    public function showInMenu(): ?string;
}
