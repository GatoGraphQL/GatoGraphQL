<?php

declare(strict_types=1);

namespace PoPWPSchema\Settings\TypeAPIs;

use PoPWPSchema\SchemaCommons\StaticHelpers\WordPressStaticHelpers;
use PoP\Root\Services\AbstractBasicService;

use function use_block_editor_for_post_type;

class SettingsTypeAPI extends AbstractBasicService implements SettingsTypeAPIInterface
{
    /**
     * The Gutenberg editor is enabled when the Classic editor
     * plugin is not enabled
     *
     * @see https://wordpress.org/plugins/classic-editor/
     */
    public function isGutenbergEditorEnabled(): bool
    {
        return !WordPressStaticHelpers::isWordPressPluginActive('classic-editor/classic-editor.php');
    }

    public function useGutenbergEditorWithCustomPostType(string $customPostType): bool
    {
        if (!$this->isGutenbergEditorEnabled()) {
            return false;
        }
        return use_block_editor_for_post_type($customPostType);
    }
}
