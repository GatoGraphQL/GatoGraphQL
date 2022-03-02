<?php

declare(strict_types=1);

namespace PoPAPI\GraphQLAPI\DataStructureFormatters;

use GraphQLByPoP\GraphQLServer\Component;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use PoP\ComponentModel\Constants\Response;
use PoP\ComponentModel\Feedback\FeedbackCategories;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\Root\App;
use PoPAPI\APIMirrorQuery\DataStructureFormatters\MirrorQueryDataStructureFormatter;

class GraphQLDataStructureFormatter extends MirrorQueryDataStructureFormatter
{
    public function getName(): string
    {
        return 'graphql';
    }

    public function getFormattedData(array $data): array
    {
        $ret = [];

        // Add feedback
        $errors = array_merge(
            $this->reformatGeneralEntries($data[Response::GENERAL_FEEDBACK][FeedbackCategories::ERROR] ?? []),
            $this->reformatDocumentEntries($data[Response::DOCUMENT_FEEDBACK][FeedbackCategories::ERROR] ?? []),
            $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::ERROR] ?? []),
            $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::ERROR] ?? [])
        );
        if ($errors !== []) {
            $ret['errors'] = $errors;
        }

        // Add warnings always, not inside of Proactive Feedback,
        // because the difference between errors and warnings sometimes is not clear
        // Eg: `{ posts(searchfor: ["posts"]) { id } }` will fail casting fieldArg `searchfor`,
        // raising a warning, but field `posts` is still executed, retrieving all results.
        // If the user is not told that there was an error/warning, it's very confusing
        $warnings = array_merge(
            $this->reformatGeneralEntries($data[Response::GENERAL_FEEDBACK][FeedbackCategories::WARNING] ?? []),
            $this->reformatDocumentEntries($data[Response::DOCUMENT_FEEDBACK][FeedbackCategories::WARNING] ?? []),
            $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::WARNING] ?? []),
            $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::WARNING] ?? [])
        );
        if ($warnings !== []) {
            $ret['extensions']['warnings'] = $warnings;
        }

        /**
         * "deprecations", and "logEntries" top-level entries:
         * since they are not part of the spec, place them under the top-level entry "extensions":
         *
         * > This entry is reserved for implementors to extend the protocol however they see fit,
         * > and hence there are no additional restrictions on its contents.
         *
         * @see http://spec.graphql.org/June2018/#sec-Response-Format
         *
         * "warnings" are added always (see above)
         */
        if ($this->addTopLevelExtensionsEntryToResponse()) {
            // Add notices
            /** @var ComponentConfiguration */
            $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
            if ($componentConfiguration->enableProactiveFeedbackNotices()) {
                $notices = array_merge(
                    $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::NOTICE] ?? []),
                    $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::NOTICE] ?? [])
                );
                if ($notices !== []) {
                    $ret['extensions']['notices'] = $notices;
                }
            }

            // Add deprecations
            if ($componentConfiguration->enableProactiveFeedbackDeprecations()) {
                $deprecations = array_merge(
                    $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::DEPRECATION] ?? []),
                    $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::DEPRECATION] ?? [])
                );
                if ($deprecations !== []) {
                    $ret['extensions']['deprecations'] = $deprecations;
                }
            }

            // Add logs
            if ($componentConfiguration->enableProactiveFeedbackLogs()) {
                $logs = $data[Response::DOCUMENT_FEEDBACK][FeedbackCategories::LOG] ?? [];
                if ($logs !== []) {
                    $ret['extensions']['logs'] = $logs;
                }
            }
        }

        if ($resultData = parent::getFormattedData($data)) {
            $ret['data'] = $resultData;
        }

        return $ret;
    }

    protected function reformatObjectEntries($entries)
    {
        $ret = [];
        foreach ($entries as $dbKey => $id_items) {
            foreach ($id_items as $id => $items) {
                foreach ($items as $item) {
                    $ret[] = $this->getObjectEntry($dbKey, $id, $item);
                }
            }
        }
        return $ret;
    }

    /**
     * Indicate if to add entry "extensions" as a top-level entry
     */
    protected function addTopLevelExtensionsEntryToResponse(): bool
    {
        return true;
    }

    protected function getObjectEntry(string $dbKey, string | int $id, array $item): array
    {
        $entry = [];
        if ($message = $item[Tokens::MESSAGE] ?? null) {
            $entry['message'] = $message;
        }
        if ($locations = $item[Tokens::LOCATIONS] ?? null) {
            $entry['locations'] = $locations;
        }
        if ($name = $item[Tokens::NAME] ?? null) {
            $entry['name'] = $name;
        }
        // if ($this->addTopLevelExtensionsEntryToResponse()) {
        if (
            $extensions = array_merge(
                $this->getObjectEntryExtensions($dbKey, $id, $item),
                $item[Tokens::EXTENSIONS] ?? []
            )
        ) {
            $entry['extensions'] = $this->reformatExtensions($extensions);
        }
        // }
        return $entry;
    }

    /**
     * Enable to modify the shape of the extensions.
     *
     * @param array<string,mixed> $extensions
     * @return array<string,mixed>
     */
    protected function reformatExtensions(array $extensions): array
    {
        // Recursive call for nested elements
        foreach (($extensions[Tokens::NESTED] ?? []) as $index => $nested) {
            if (!isset($nested[Tokens::EXTENSIONS])) {
                continue;
            }
            $extensions[Tokens::NESTED][$index][Tokens::EXTENSIONS] = $this->reformatExtensions($nested[Tokens::EXTENSIONS]);
        }
        return $extensions;
    }

    protected function getObjectEntryExtensions(string $dbKey, int | string $id, array $item): array
    {
        return [
            'type' => 'dataObject',
            'entityDBKey' => $dbKey,
            'id' => $id,
            'path' => $item[Tokens::PATH] ?? [],
        ];
    }

    protected function reformatSchemaEntries($entries)
    {
        $ret = [];
        foreach ($entries as $dbKey => $items) {
            foreach ($items as $item) {
                $ret[] = $this->getSchemaEntry($dbKey, $item);
            }
        }
        return $ret;
    }

    protected function getSchemaEntry(string $dbKey, array $item): array
    {
        $entry = [];
        if ($message = $item[Tokens::MESSAGE] ?? null) {
            $entry['message'] = $message;
        }
        if ($locations = $item[Tokens::LOCATIONS] ?? null) {
            $entry['locations'] = $locations;
        }
        if ($name = $item[Tokens::NAME] ?? null) {
            $entry['name'] = $name;
        }
        // if ($this->addTopLevelExtensionsEntryToResponse()) {
        if (
            $extensions = array_merge(
                $this->getSchemaEntryExtensions($dbKey, $item),
                $item[Tokens::EXTENSIONS] ?? []
            )
        ) {
            $entry['extensions'] = $this->reformatExtensions($extensions);
        }
        // }
        return $entry;
    }

    protected function getSchemaEntryExtensions(string $dbKey, array $item): array
    {
        return [
            'type' => 'schema',
            'entityDBKey' => $dbKey,
            'path' => $item[Tokens::PATH] ?? [],
        ];
    }

    protected function reformatDocumentEntries($entries)
    {
        $ret = [];
        foreach ($entries as $entry) {
            $ret[] = $this->getDocumentEntry($entry);
        }
        return $ret;
    }

    protected function getDocumentEntry(array $entry): array
    {
        if ($extensions = $entry[Tokens::EXTENSIONS] ?? null) {
            $entry['extensions'] = $this->reformatExtensions($extensions);
        }
        return $entry;
    }

    protected function getDocumentEntryExtensions(): array
    {
        return [
            'type' => 'document',
        ];
    }

    protected function reformatGeneralEntries($entries)
    {
        $ret = [];
        foreach ($entries as $message => $extensions) {
            $ret[] = $this->getGeneralEntry($message, $extensions);
        }
        return $ret;
    }

    protected function getGeneralEntry(string $message, array $extensions): array
    {
        $entry = [
            'message' => $message,
        ];
        if ($extensions = $this->getDocumentEntryExtensions()) {
            $entry['extensions'] = $this->reformatExtensions($extensions);
        };
        return $entry;
    }
}
