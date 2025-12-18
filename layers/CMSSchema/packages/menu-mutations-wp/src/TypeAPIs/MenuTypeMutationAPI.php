<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutationsWP\TypeAPIs;

use PoPCMSSchema\MenuMutations\Exception\MenuCRUDMutationException;
use PoPCMSSchema\MenuMutations\Module;
use PoPCMSSchema\MenuMutations\ModuleConfiguration;
use PoPCMSSchema\MenuMutations\TypeAPIs\MenuTypeMutationAPIInterface;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\Services\AbstractBasicService;
use WP_Error;

use function add_filter;
use function add_post_meta;
use function download_url;
use function get_allowed_mime_types;
use function get_attached_file;
use function get_taxonomy;
use function get_term;
use function get_post;
use function get_post_meta;
use function is_wp_error;
use function remove_filter;
use function update_attached_file;
use function update_post_meta;
use function user_can;
use function wp_check_filetype;
use function wp_get_attachment_metadata;
use function wp_insert_attachment;
use function wp_slash;
use function wp_update_attachment_metadata;

class MenuTypeMutationAPI extends AbstractBasicService implements MenuTypeMutationAPIInterface
{
    /**
     * @throws MenuCRUDMutationException In case of error
     * @param array<string,mixed> $menuData
     */
    public function updateMenu(
        string|int $menuID,
        array $menuData,
    ): void {
        $mimeType = $menuData['mimeType'] ?? null;
        if ($mimeType !== null) {
            $mimes = get_allowed_mime_types();
            if (!in_array($mimeType, $mimes)) {
                throw new MenuCRUDMutationException(
                    sprintf(
                        $this->__('Mime type \'%s\' is not allowed', 'menu-mutations'),
                        $mimeType
                    )
                );
            }
        }

        $menuData = $this->convertMenuCreationArgs($menuData);
        $menuData['ID'] = $menuID;

        $menuIDOrError = wp_update_post(
            wp_slash($menuData), // @phpstan-ignore-line
            true
        );

        if (is_wp_error($menuIDOrError)) {
            /** @var WP_Error */
            $wpError = $menuIDOrError;
            throw new MenuCRUDMutationException(
                $wpError->get_error_message()
            );
        }
    }

    /**
     * @param array<string,mixed> $args
     * @return array<string,mixed>
     */
    public function customizeHTTPRequestArgsDoNotRejectUnsafeURLs(array $args): array
    {
        $args['reject_unsafe_urls'] = false;
        return $args;
    }

    /**
     * @throws MenuCRUDMutationException In case of error
     * @param array<string,mixed> $menuData
     */
    public function createMenu(
        string $body,
        string $filename,
        array $menuData,
    ): string|int {
        
        // @todo Implement this method
        return 0;
    }

    /**
     * If the file doesn't contain an extension (even when we are
     * providing the mime type), we will get an error:
     *
     *   > Sorry, you are not allowed to upload this file type.
     *
     * Then, append the extension if missing.
     */
    protected function maybeAddExtensionToFilename(
        string $filename,
        ?string $explicitMimeType,
    ): string {
        if ($explicitMimeType === null || $explicitMimeType === '') {
            return $filename;
        }

        if (strrpos($filename, '.') !== false) {
            return $filename;
        }

        $extension = \wp_get_default_extension_for_mime_type($explicitMimeType);
        if ($extension === false) {
            return $filename;
        }

        return $filename . '.' . $extension;
    }

    /**
     * @throws MenuCRUDMutationException If the mime type is not allowed
     */
    protected function getFileMimeTypeOrThrowError(string $filename): string
    {
        // Get the mime type from the file, and check it's allowed
        $mimeTypeCheck = wp_check_filetype(sanitize_file_name(basename($filename)));
        if (!$mimeTypeCheck['type']) {
            throw new MenuCRUDMutationException(
                $this->__('The file\'s mime type is not allowed', 'menu-mutations')
            );
        }
        /** @var string */
        $mimeType = $mimeTypeCheck['type'];
        return $mimeType;
    }

    /**
     * @param array<string,mixed> $menuData
     * @return array<string,mixed>
     */
    protected function convertMenuCreationArgs(array $menuData): array
    {
        if (isset($menuData['authorID'])) {
            $menuData['post_author'] = $menuData['authorID'];
            unset($menuData['authorID']);
        }
        if (isset($menuData['title'])) {
            $menuData['post_title'] = $menuData['title'];
            unset($menuData['title']);
        }
        if (isset($menuData['slug'])) {
            $menuData['post_name'] = $menuData['slug'];
            unset($menuData['slug']);
        }
        if (isset($menuData['caption'])) {
            $menuData['post_excerpt'] = $menuData['caption'];
            unset($menuData['caption']);
        }
        if (isset($menuData['description'])) {
            $menuData['post_content'] = $menuData['description'];
            unset($menuData['description']);
        }
        if (isset($menuData['mimeType'])) {
            $menuData['post_mime_type'] = $menuData['mimeType'];
            unset($menuData['mimeType']);
        }
        if (isset($menuData['date'])) {
            $menuData['post_date'] = $menuData['date'];
            unset($menuData['date']);
        }
        if (isset($menuData['gmtDate'])) {
            $menuData['post_date_gmt'] = $menuData['gmtDate'];
            unset($menuData['gmtDate']);
        }
        if (isset($menuData['customPostID'])) {
            $menuData['post_parent'] = $menuData['customPostID'];
            unset($menuData['customPostID']);
        } elseif (array_key_exists('customPostID', $menuData)) {
            // `customPostID` = `null` => Set as `0`
            $menuData['post_parent'] = 0;
            unset($menuData['customPostID']);
        }
        return $menuData;
    }

    public function canUserEditMenus(
        string|int $userID
    ): bool {
        $navMenuTaxonomy = get_taxonomy('nav_menu');
        if ($navMenuTaxonomy === null) {
            return false;
        }

        if (isset($navMenuTaxonomy->cap->manage_terms)) {
            return user_can((int) $userID, $navMenuTaxonomy->cap->manage_terms);
        }

        // Fallback: `nav_menu` taxonomy capabilities map to `edit_theme_options`.
        return user_can((int) $userID, 'edit_theme_options');
    }

    public function canUserEditMenu(
        string|int $userID,
        string|int $menuID,
    ): bool {
        $menuTerm = get_term((int) $menuID, 'nav_menu');
        if (
            $menuTerm === null
            || is_wp_error($menuTerm)
            || !($menuTerm instanceof \WP_Term)
            || $menuTerm->taxonomy !== 'nav_menu'
        ) {
            return false;
        }

        return user_can((int) $userID, 'edit_term', (int) $menuID);
    }
}
