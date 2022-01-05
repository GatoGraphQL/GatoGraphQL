<?php

declare(strict_types=1);

namespace PoP\GraphQLAPI\DataStructureFormatters;

use PoP\Engine\App;
use GraphQLByPoP\GraphQLServer\Component;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration;
use PoP\APIMirrorQuery\DataStructureFormatters\MirrorQueryDataStructureFormatter;
use PoP\ComponentModel\Feedback\Tokens;

class GraphQLDataStructureFormatter extends MirrorQueryDataStructureFormatter
{
    public function getName(): string
    {
        return 'graphql';
    }

    public function getFormattedData($data)
    {
        $ret = [];

        // Add errors
        $errors = $warnings = $deprecations = $notices = $traces = [];
        if (isset($data['queryErrors'])) {
            $errors = array_merge(
                $errors,
                $this->reformatQueryEntries($data['queryErrors'])
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
        if ($data['objectWarnings'] ?? null) {
            $warnings = $this->reformatDBEntries($data['objectWarnings']);
        }
        if ($data['schemaWarnings'] ?? null) {
            $warnings = array_merge(
                $warnings,
                $this->reformatSchemaEntries($data['schemaWarnings'])
            );
        }
        if ($data['queryWarnings'] ?? null) {
            $warnings = array_merge(
                $warnings,
                $this->reformatQueryEntries($data['queryWarnings'])
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

            // Add traces
            if ($componentConfiguration->enableProactiveFeedbackTraces()) {
                if ($data['objectTraces'] ?? null) {
                    $traces = $this->reformatDBEntries($data['objectTraces']);
                }
                if ($data['schemaTraces'] ?? null) {
                    $traces = array_merge(
                        $traces,
                        $this->reformatSchemaEntries($data['schemaTraces'])
                    );
                }
                if ($traces) {
                    $ret['extensions']['traces'] = $traces;
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
            // // GraphQL places the queried data under entries 'data' => query => results
            // // Replicate this structure. Because we don't have a query name here,
            // // replace it with the queried URL path, which is known to the client
            // $path = RoutingUtils::getURLPath();
            // // If there is no path, it is the single point of entry (homepage => root)
            // if (!$path) {
            //     $path = '/';
            // }
            // $ret['data'] = [
            //     $path => $resultData,
            // ];
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

    protected function reformatQueryEntries($entries)
    {
        $ret = [];
        foreach ($entries as $message => $extensions) {
            $ret[] = $this->getQueryEntry($message, $extensions);
        }
        return $ret;
    }

    protected function getQueryEntry(string $message, array $extensions): array
    {
        $entry = [
            'message' => $message,
        ];
        // if ($this->addTopLevelExtensionsEntryToResponse()) {
        if (
            $extensions = array_merge(
                $this->getQueryEntryExtensions(),
                $extensions
            )
        ) {
            $entry['extensions'] = $this->reformatExtensions($extensions);
        };
        // }
        return $entry;
    }

    protected function getQueryEntryExtensions(): array
    {
        return [
            'type' => 'query',
        ];
    }
}
