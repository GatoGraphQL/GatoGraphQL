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
use function get_post;
use function get_post_meta;
use function is_wp_error;
use function remove_filter;
use function update_attached_file;
use function update_post_meta;
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
    public function createMenuFromExistingMenu(
        string|int $existingMenuID,
        array $menuData,
    ): string|int|null {
        $toCreateMenuData = get_post((int) $existingMenuID, ARRAY_A);

        if ($toCreateMenuData === null || $toCreateMenuData === []) {
            return null;
        }

        unset($toCreateMenuData['ID']);

        $customPostID = 0;
        if (isset($menuData['customPostID'])) {
            $customPostID = $menuData['customPostID'];
            unset($menuData['customPostID']);
        }

        /**
         * Override properties with the provided ones
         */
        $menuData = $this->convertMenuCreationArgs($menuData);
        $toCreateMenuData = array_merge(
            $toCreateMenuData,
            array_filter($menuData)
        );

        $menuIDOrError = wp_insert_attachment(
            wp_slash($toCreateMenuData),
            false,
            $customPostID,
            true
        );

        if (is_wp_error($menuIDOrError)) {
            /** @var WP_Error */
            $wpError = $menuIDOrError;
            throw new MenuCRUDMutationException(
                $wpError->get_error_message()
            );
        }

        /** @var int */
        $menuID = $menuIDOrError;

        /**
         * Copy over:
         *
         * - Metadata
         * - Attached file
         * - Alternative text
         */
        $attachmentMetadata = wp_get_attachment_metadata((int) $existingMenuID, true);
        if ($attachmentMetadata !== false) {
            wp_update_attachment_metadata($menuID, $attachmentMetadata);
        }
        $attachedFile = get_attached_file((int) $existingMenuID, true);
        if ($attachedFile !== false) {
            update_attached_file($menuID, $attachedFile);
        }
        $alternativeText = get_post_meta((int) $existingMenuID, '_wp_attachment_image_alt', true);
        if ($alternativeText) {
            add_post_meta($menuID, '_wp_attachment_image_alt', $alternativeText);
        }

        return $menuID;
    }

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

        /** @var string|null */
        $altText = $menuData['altText'] ?? null;
        if ($altText !== null) {
            $this->updateImageAltText($menuID, $altText);
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
    public function createMenuFromContents(
        string $body,
        string $filename,
        array $menuData,
    ): string|int {
        $filename = $this->maybeAddExtensionToFilename(
            $filename,
            $menuData['mimeType'] ?? null,
        );
        $mimeType = $this->getFileMimeTypeOrThrowError($filename);

        $uploadedFileOrError = \wp_upload_bits($filename, null, $body);
        if ($uploadedFileOrError['error']) {
            /** @var string */
            $errorMessage = $uploadedFileOrError['error'];
            throw new MenuCRUDMutationException(
                $errorMessage
            );
        }
        $uploadedFile = $uploadedFileOrError;

        if (empty($menuData['title'])) {
            $menuData['title'] = $filename;
        }

        /** @var string */
        $file = $uploadedFile['file'];
        return $this->createMenuFromLocalFile(
            $file,
            $filename,
            $mimeType,
            $menuData,
        );
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
     * @throws MenuCRUDMutationException In case of error
     * @param array<string,mixed> $menuData
     */
    protected function createMenuFromLocalFile(
        string $file,
        string $filename,
        string $mimeType,
        array $menuData
    ): string|int {
        // @phpstan-ignore-next-line
        require_once ABSPATH . 'wp-admin/includes/file.php';

        $filesize = filesize($file);
        if ($filesize === false) {
            $filesize = 0;
        }
        $fileData = [
            'name' => \sanitize_file_name($filename),
            'type' => $mimeType,
            'tmp_name' => $file,
            'error' => 0,
            'size' => $filesize,
        ];

        $uploadedFile = \wp_handle_sideload(
            $fileData,
            [
                'test_form' => false,
            ]
        );

        if (isset($uploadedFile['error'])) {
            /** @var string */
            $errorMessage = $uploadedFile['error'];
            throw new MenuCRUDMutationException(
                $errorMessage
            );
        }

        /** @var string */
        $uploadedFilename = $uploadedFile['file'];

        $customPostID = 0;
        if (isset($menuData['customPostID'])) {
            $customPostID = $menuData['customPostID'];
            unset($menuData['customPostID']);
        }

        if (empty($menuData['title'])) {
            $menuData['title'] = sanitize_file_name(basename($uploadedFilename));
        }

        $menuData['mimeType'] = $mimeType;

        $menuData = $this->convertMenuCreationArgs($menuData);

        $menuIDOrError = wp_insert_attachment(
            $menuData,
            $uploadedFilename,
            $customPostID,
            true
        );

        if (is_wp_error($menuIDOrError)) {
            /** @var WP_Error */
            $wpError = $menuIDOrError;
            throw new MenuCRUDMutationException(
                $wpError->get_error_message()
            );
        }

        $menuID = $menuIDOrError;

        $this->addImageMetaData(
            $menuID,
            $uploadedFilename,
            $menuData,
        );

        return $menuID;
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

    /**
     * Update the image metadata, including the dimensions
     * to generate the thumbnails
     *
     * @param array<string,mixed> $menuData
     */
    protected function addImageMetaData(
        string|int $menuID,
        string $filename,
        array $menuData
    ): void {
        // @phpstan-ignore-next-line
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $menuMetaData = \wp_generate_attachment_metadata((int) $menuID, $filename);
        wp_update_attachment_metadata((int) $menuID, $menuMetaData);

        /** @var string|null */
        $altText = $menuData['altText'] ?? null;
        if (!empty($altText)) {
            $this->updateImageAltText($menuID, $altText);
        }
    }

    protected function updateImageAltText(
        string|int $menuID,
        string $altText,
    ): void {
        update_post_meta((int) $menuID, '_wp_attachment_image_alt', $altText);
    }

    public function canUserEditMenus(
        string|int $userID
    ): bool {
        $attachmentObject = get_post_type_object('attachment');
        if ($attachmentObject === null) {
            return false;
        }

        return isset($attachmentObject->cap->edit_posts) && user_can((int)$userID, $attachmentObject->cap->edit_posts);
    }

    public function canUserEditMenu(
        string|int $userID,
        string|int $menuID,
    ): bool {
        return user_can((int)$userID, 'edit_post', $menuID);
    }
}
