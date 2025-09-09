<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutationsWP\TypeAPIs;

use PoPCMSSchema\MediaMutations\Exception\MediaItemCRUDMutationException;
use PoPCMSSchema\MediaMutations\Module;
use PoPCMSSchema\MediaMutations\ModuleConfiguration;
use PoPCMSSchema\MediaMutations\TypeAPIs\MediaTypeMutationAPIInterface;
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

class MediaTypeMutationAPI extends AbstractBasicService implements MediaTypeMutationAPIInterface
{
    /**
     * @throws MediaItemCRUDMutationException In case of error
     * @param array<string,mixed> $mediaItemData
     */
    public function createMediaItemFromExistingMediaItem(
        string|int $existingMediaItemID,
        array $mediaItemData,
    ): string|int|null {
        $toCreateMediaItemData = get_post((int) $existingMediaItemID, ARRAY_A);

        if ($toCreateMediaItemData === null || $toCreateMediaItemData === []) {
            return null;
        }

        unset($toCreateMediaItemData['ID']);

        $customPostID = 0;
        if (isset($mediaItemData['customPostID'])) {
            $customPostID = $mediaItemData['customPostID'];
            unset($mediaItemData['customPostID']);
        }

        /**
         * Override properties with the provided ones
         */
        $mediaItemData = $this->convertMediaItemCreationArgs($mediaItemData);
        $toCreateMediaItemData = array_merge(
            $toCreateMediaItemData,
            array_filter($mediaItemData)
        );

        $mediaItemIDOrError = wp_insert_attachment(
            wp_slash($toCreateMediaItemData),
            false,
            $customPostID,
            true
        );

        if (is_wp_error($mediaItemIDOrError)) {
            /** @var WP_Error */
            $wpError = $mediaItemIDOrError;
            throw new MediaItemCRUDMutationException(
                $wpError->get_error_message()
            );
        }

        /** @var int */
        $mediaItemID = $mediaItemIDOrError;

        /**
         * Copy over:
         *
         * - Metadata
         * - Attached file
         * - Alternative text
         */
        $attachmentMetadata = wp_get_attachment_metadata((int) $existingMediaItemID, true);
        if ($attachmentMetadata !== false) {
            wp_update_attachment_metadata($mediaItemID, $attachmentMetadata);
        }
        $attachedFile = get_attached_file((int) $existingMediaItemID, true);
        if ($attachedFile !== false) {
            update_attached_file($mediaItemID, $attachedFile);
        }
        $alternativeText = get_post_meta((int) $existingMediaItemID, '_wp_attachment_image_alt', true);
        if ($alternativeText) {
            add_post_meta($mediaItemID, '_wp_attachment_image_alt', $alternativeText);
        }

        return $mediaItemID;
    }

    /**
     * @throws MediaItemCRUDMutationException In case of error
     * @param array<string,mixed> $mediaItemData
     */
    public function updateMediaItem(
        string|int $mediaItemID,
        array $mediaItemData,
    ): void {
        $mimeType = $mediaItemData['mimeType'] ?? null;
        if ($mimeType !== null) {
            $mimes = get_allowed_mime_types();
            if (!in_array($mimeType, $mimes)) {
                throw new MediaItemCRUDMutationException(
                    sprintf(
                        $this->__('Mime type \'%s\' is not allowed', 'media-mutations'),
                        $mimeType
                    )
                );
            }
        }

        $mediaItemData = $this->convertMediaItemCreationArgs($mediaItemData);
        $mediaItemData['ID'] = $mediaItemID;

        $mediaItemIDOrError = wp_update_post(
            wp_slash($mediaItemData), // @phpstan-ignore-line
            true
        );

        if (is_wp_error($mediaItemIDOrError)) {
            /** @var WP_Error */
            $wpError = $mediaItemIDOrError;
            throw new MediaItemCRUDMutationException(
                $wpError->get_error_message()
            );
        }

        /** @var string|null */
        $altText = $mediaItemData['altText'] ?? null;
        if ($altText !== null) {
            $this->updateImageAltText($mediaItemID, $altText);
        }
    }

    /**
     * @throws MediaItemCRUDMutationException In case of error
     * @param string|null $filename Override the filename from the URL, or pass `null` to use filename from URL
     * @param array<string,mixed> $mediaItemData
     */
    public function createMediaItemFromURL(
        string $url,
        ?string $filename,
        array $mediaItemData,
    ): string|int {
        // @phpstan-ignore-next-line
        require_once ABSPATH . 'wp-admin/includes/file.php';

        /**
         * When creating a media item from an URL, WordPress sets
         * "reject_unsafe_urls" to `true`, because `download_url`
         * calls `wp_safe_remote_get`.
         *
         * This way, by default we can't create media items from unsafe URLs,
         * such as "https://playground-dev.local".
         *
         * @see wordpress/wp-includes/class-wp-http.php method `request`
         *
         * Allow to override this behavior!
         */
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $rejectUnsafeURLs = $moduleConfiguration->rejectUnsafeURLs();
        if (!$rejectUnsafeURLs) {
            add_filter('http_request_args', $this->customizeHTTPRequestArgsDoNotRejectUnsafeURLs(...), PHP_INT_MAX);
        }
        $downloadedFileOrError = download_url($url);
        if (!$rejectUnsafeURLs) {
            remove_filter('http_request_args', $this->customizeHTTPRequestArgsDoNotRejectUnsafeURLs(...), PHP_INT_MAX);
        }

        if (is_wp_error($downloadedFileOrError)) {
            /** @var WP_Error */
            $wpError = $downloadedFileOrError;
            throw new MediaItemCRUDMutationException(
                $wpError->get_error_message()
            );
        }

        $downloadedFile = $downloadedFileOrError;

        /**
         * Either use the provided filename or, if `null`,
         * use the same one as from the URL.
         *
         * The URL might contain params (eg: with the dynamically
         * generated image by DALL-E).
         *
         * Remove the URL params to expose the extension, or
         * `wp_check_filetype` won't figure out the mime type
         */
        $filename ??= basename(GeneralUtils::getURLWithoutQueryParams($url));

        /**
         * The mime type is retrieved from the filename extension
         * always, because that's what `wp_handle_sideload` does.
         *
         * Then, we need to always add the corresponding extension
         * to the file name.
         *
         * If the filename has no extension, get it from the "mimeType"
         * input param.
         */
        $filename = $this->maybeAddExtensionToFilename(
            $filename,
            $mediaItemData['mimeType'] ?? null,
        );
        $mimeType = $this->getFileMimeTypeOrThrowError($filename);

        if (empty($mediaItemData['title'])) {
            $mediaItemData['title'] = $filename;
        }

        $mediaItemID = $this->createMediaItemFromLocalFile(
            $downloadedFile,
            $filename,
            $mimeType,
            $mediaItemData,
        );

        \unlink($downloadedFile);

        return $mediaItemID;
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
     * @throws MediaItemCRUDMutationException In case of error
     * @param array<string,mixed> $mediaItemData
     */
    public function createMediaItemFromContents(
        string $body,
        string $filename,
        array $mediaItemData,
    ): string|int {
        $filename = $this->maybeAddExtensionToFilename(
            $filename,
            $mediaItemData['mimeType'] ?? null,
        );
        $mimeType = $this->getFileMimeTypeOrThrowError($filename);

        $uploadedFileOrError = \wp_upload_bits($filename, null, $body);
        if ($uploadedFileOrError['error']) {
            /** @var string */
            $errorMessage = $uploadedFileOrError['error'];
            throw new MediaItemCRUDMutationException(
                $errorMessage
            );
        }
        $uploadedFile = $uploadedFileOrError;

        if (empty($mediaItemData['title'])) {
            $mediaItemData['title'] = $filename;
        }

        /** @var string */
        $file = $uploadedFile['file'];
        return $this->createMediaItemFromLocalFile(
            $file,
            $filename,
            $mimeType,
            $mediaItemData,
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
     * @throws MediaItemCRUDMutationException In case of error
     * @param array<string,mixed> $mediaItemData
     */
    protected function createMediaItemFromLocalFile(
        string $file,
        string $filename,
        string $mimeType,
        array $mediaItemData
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
            throw new MediaItemCRUDMutationException(
                $errorMessage
            );
        }

        /** @var string */
        $uploadedFilename = $uploadedFile['file'];

        $customPostID = 0;
        if (isset($mediaItemData['customPostID'])) {
            $customPostID = $mediaItemData['customPostID'];
            unset($mediaItemData['customPostID']);
        }

        if (empty($mediaItemData['title'])) {
            $mediaItemData['title'] = sanitize_file_name(basename($uploadedFilename));
        }

        $mediaItemData['mimeType'] = $mimeType;

        $mediaItemData = $this->convertMediaItemCreationArgs($mediaItemData);

        $mediaItemIDOrError = wp_insert_attachment(
            $mediaItemData,
            $uploadedFilename,
            $customPostID,
            true
        );

        if (is_wp_error($mediaItemIDOrError)) {
            /** @var WP_Error */
            $wpError = $mediaItemIDOrError;
            throw new MediaItemCRUDMutationException(
                $wpError->get_error_message()
            );
        }

        $mediaItemID = $mediaItemIDOrError;

        $this->addImageMetaData(
            $mediaItemID,
            $uploadedFilename,
            $mediaItemData,
        );

        return $mediaItemID;
    }

    /**
     * @throws MediaItemCRUDMutationException If the mime type is not allowed
     */
    protected function getFileMimeTypeOrThrowError(string $filename): string
    {
        // Get the mime type from the file, and check it's allowed
        $mimeTypeCheck = wp_check_filetype(sanitize_file_name(basename($filename)));
        if (!$mimeTypeCheck['type']) {
            throw new MediaItemCRUDMutationException(
                $this->__('The file\'s mime type is not allowed', 'media-mutations')
            );
        }
        /** @var string */
        $mimeType = $mimeTypeCheck['type'];
        return $mimeType;
    }

    /**
     * @param array<string,mixed> $mediaItemData
     * @return array<string,mixed>
     */
    protected function convertMediaItemCreationArgs(array $mediaItemData): array
    {
        if (isset($mediaItemData['authorID'])) {
            $mediaItemData['post_author'] = $mediaItemData['authorID'];
            unset($mediaItemData['authorID']);
        }
        if (isset($mediaItemData['title'])) {
            $mediaItemData['post_title'] = $mediaItemData['title'];
            unset($mediaItemData['title']);
        }
        if (isset($mediaItemData['slug'])) {
            $mediaItemData['post_name'] = $mediaItemData['slug'];
            unset($mediaItemData['slug']);
        }
        if (isset($mediaItemData['caption'])) {
            $mediaItemData['post_excerpt'] = $mediaItemData['caption'];
            unset($mediaItemData['caption']);
        }
        if (isset($mediaItemData['description'])) {
            $mediaItemData['post_content'] = $mediaItemData['description'];
            unset($mediaItemData['description']);
        }
        if (isset($mediaItemData['mimeType'])) {
            $mediaItemData['post_mime_type'] = $mediaItemData['mimeType'];
            unset($mediaItemData['mimeType']);
        }
        if (isset($mediaItemData['date'])) {
            $mediaItemData['post_date'] = $mediaItemData['date'];
            unset($mediaItemData['date']);
        }
        if (isset($mediaItemData['gmtDate'])) {
            $mediaItemData['post_date_gmt'] = $mediaItemData['gmtDate'];
            unset($mediaItemData['gmtDate']);
        }
        if (isset($mediaItemData['customPostID'])) {
            $mediaItemData['post_parent'] = $mediaItemData['customPostID'];
            unset($mediaItemData['customPostID']);
        } elseif (array_key_exists('customPostID', $mediaItemData)) {
            // `customPostID` = `null` => Set as `0`
            $mediaItemData['post_parent'] = 0;
            unset($mediaItemData['customPostID']);
        }
        return $mediaItemData;
    }

    /**
     * Update the image metadata, including the dimensions
     * to generate the thumbnails
     *
     * @param array<string,mixed> $mediaItemData
     */
    protected function addImageMetaData(
        string|int $mediaItemID,
        string $filename,
        array $mediaItemData
    ): void {
        // @phpstan-ignore-next-line
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $mediaItemMetaData = \wp_generate_attachment_metadata((int) $mediaItemID, $filename);
        wp_update_attachment_metadata((int) $mediaItemID, $mediaItemMetaData);

        /** @var string|null */
        $altText = $mediaItemData['altText'] ?? null;
        if (!empty($altText)) {
            $this->updateImageAltText($mediaItemID, $altText);
        }
    }

    protected function updateImageAltText(
        string|int $mediaItemID,
        string $altText,
    ): void {
        update_post_meta((int) $mediaItemID, '_wp_attachment_image_alt', $altText);
    }

    public function canUserEditMediaItems(
        string|int $userID
    ): bool {
        $attachmentObject = get_post_type_object('attachment');
        if ($attachmentObject === null) {
            return false;
        }

        return isset($attachmentObject->cap->edit_posts) && user_can((int)$userID, $attachmentObject->cap->edit_posts);
    }

    public function canUserEditMediaItem(
        string|int $userID,
        string|int $mediaItemID,
    ): bool {
        return user_can((int)$userID, 'edit_post', $mediaItemID);
    }
}
