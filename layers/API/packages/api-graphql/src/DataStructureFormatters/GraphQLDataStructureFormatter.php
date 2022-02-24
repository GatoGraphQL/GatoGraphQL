<?php

declare(strict_types=1);

namespace PoPAPI\GraphQLAPI\DataStructureFormatters;

use PoP\Root\App;
use GraphQLByPoP\GraphQLServer\Component;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use PoPAPI\APIMirrorQuery\DataStructureFormatters\MirrorQueryDataStructureFormatter;
use PoP\ComponentModel\Feedback\Tokens;

class GraphQLDataStructureFormatter extends MirrorQueryDataStructureFormatter
{
    public function getName(): string
    {
        return 'graphql';
    }

    public function getFormattedData(array $data): array
    {
        $ret = [];

        // Add errors
        $errors = $warnings = $deprecations = $notices = [];
        if (isset($data['generalErrors'])) {
            $errors = array_merge(
                $errors,
                $this->reformatGeneralEntries($data['generalErrors'])
            );
        }
        if (isset($data['documentErrors'])) {
            $errors = array_merge(
                $errors,
                $this->reformatDocumentEntries($data['documentErrors'])
            );
        }
        if (isset($data['schemaErrors'])) {
            $errors = array_merge(
                $errors,
                $this->reformatSchemaEntries($data['schemaErrors'])
            );
        }
        if (isset($data['objectErrors'])) {
            $errors = array_merge(
                $errors,
                $this->reformatDBEntries($data['objectErrors'])
            );
        }
        if ($errors) {
            $ret['errors'] = $errors;
        }

        // Add warnings always, not inside of Proactive Feedback,
        // because the difference between errors and warnings sometimes is not clear
        // Eg: `{ posts(searchfor: ["posts"]) { id } }` will fail casting fieldArg `searchfor`,
        // raising a warning, but field `posts` is still executed, retrieving all results.
        // If the user is not told that there was an error/warning, it's very confusing
        if ($data['generalWarnings'] ?? null) {
            $warnings = array_merge(
                $warnings,
                $this->reformatGeneralEntries($data['generalWarnings'])
            );
        }
        if ($data['documentWarnings'] ?? null) {
            $warnings = array_merge(
                $warnings,
                $this->reformatDocumentEntries($data['documentWarnings'])
            );
        }
        if ($data['schemaWarnings'] ?? null) {
            $warnings = array_merge(
                $warnings,
                $this->reformatSchemaEntries($data['schemaWarnings'])
            );
        }
        if ($data['objectWarnings'] ?? null) {
            $warnings = array_merge(
                $warnings,
                $this->reformatDBEntries($data['objectWarnings'])
            );
        }
        if ($warnings) {
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
                if ($data['objectNotices'] ?? null) {
                    $notices = $this->reformatDBEntries($data['objectNotices']);
                }
                if ($data['schemaNotices'] ?? null) {
                    $notices = array_merge(
                        $notices,
                        $this->reformatSchemaEntries($data['schemaNotices'])
                    );
                }
                if ($notices) {
                    $ret['extensions']['notices'] = $notices;
                }
            }

            // Add deprecations
            if ($componentConfiguration->enableProactiveFeedbackDeprecations()) {
                if ($data['objectDeprecations'] ?? null) {
                    $deprecations = $this->reformatDBEntries($data['objectDeprecations']);
                }
                if ($data['schemaDeprecations'] ?? null) {
                    $deprecations = array_merge(
                        $deprecations,
                        $this->reformatSchemaEntries($data['schemaDeprecations'])
                    );
                }
                if ($deprecations) {
                    $ret['extensions']['deprecations'] = $deprecations;
                }
            }

            // Add logs
            if ($componentConfiguration->enableProactiveFeedbackLogs()) {
                if ($data['logEntries'] ?? null) {
                    $ret['extensions']['logs'] = $data['logEntries'];
                }
            }
        }

        if ($resultData = parent::getFormattedData($data)) {
            $ret['data'] = $resultData;
        }

        return $ret;
    }

    protected function reformatDBEntries($entries)
    {
        $ret = [];
        foreach ($entries as $dbKey => $id_items) {
            foreach ($id_items as $id => $items) {
                foreach ($items as $item) {
                    $ret[] = $this->getDBEntry($dbKey, $id, $item);
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

    protected function getDBEntry(string $dbKey, string | int $id, array $item): array
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
                $this->getDBEntryExtensions($dbKey, $id, $item),
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

    protected function getDBEntryExtensions(string $dbKey, int | string $id, array $item): array
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
