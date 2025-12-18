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
use function sanitize_text_field;
use function sanitize_title;
use function user_can;
use function wp_insert_term;
use function wp_update_term;

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
        $termArgs = $this->convertMenuCreationArgs($menuData);

        $termIDOrError = wp_update_term(
            (int) $menuID,
            'nav_menu',
            $termArgs,
        );

        if (is_wp_error($termIDOrError)) {
            /** @var WP_Error */
            $wpError = $termIDOrError;
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
        $termArgs = $this->convertMenuCreationArgs($menuData);

        $menuName = $termArgs['name'] ?? '';
        $menuName = trim($menuName);
        if ($menuName === '') {
            throw new MenuCRUDMutationException(
                $this->__('The menu name cannot be empty', 'menu-mutations')
            );
        }
        $menuName = sanitize_text_field($menuName);

        // `wp_insert_term` expects the term name separately from the args.
        unset($termArgs['name']);

        $termOrError = wp_insert_term(
            $menuName,
            'nav_menu',
            $termArgs,
        );

        if (is_wp_error($termOrError)) {
            /** @var WP_Error */
            $wpError = $termOrError;
            throw new MenuCRUDMutationException(
                $wpError->get_error_message()
            );
        }

        /** @var array{term_id:int,term_taxonomy_id:int} $termData */
        $termData = $termOrError;
        return $termData['term_id'];
    }

    /**
     * @param array<string,mixed> $menuData
     * @return array<string,mixed>
     */
    protected function convertMenuCreationArgs(array $menuData): array
    {
        /**
         * Nav menus are stored as terms in the `nav_menu` taxonomy.
         *
         * Map the mutation input properties to the supported term args.
         *
         * @see wp_insert_term()
         * @see wp_update_term()
         */
        $termArgs = [];

        $name = $menuData['name'] ?? null;
        if ($name !== null) {
            $name = trim((string) $name);
            if ($name !== '') {
                $termArgs['name'] = sanitize_text_field($name);
            }
        }

        $slug = $menuData['slug'] ?? null;
        if ($slug !== null) {
            $slug = trim((string) $slug);
            if ($slug !== '') {
                $termArgs['slug'] = sanitize_title($slug);
            }
        }

        return $termArgs;
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
