<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutationsWP\TypeAPIs;

use PoPCMSSchema\MenuMutations\Exception\MenuCRUDMutationException;
use PoPCMSSchema\MenuMutations\TypeAPIs\MenuTypeMutationAPIInterface;
use PoP\Root\Services\AbstractBasicService;
use WP_Error;

use function get_taxonomy;
use function get_term;
use function is_wp_error;
use function user_can;
use function wp_slash;

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
