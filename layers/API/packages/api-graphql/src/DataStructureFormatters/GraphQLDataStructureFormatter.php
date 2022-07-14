<?php

declare(strict_types=1);

namespace PoPAPI\GraphQLAPI\DataStructureFormatters;

use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use PoP\ComponentModel\Constants\Response;
use PoP\ComponentModel\Feedback\FeedbackCategories;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;
use PoPAPI\APIMirrorQuery\DataStructureFormatters\MirrorQueryDataStructureFormatter;
use SplObjectStorage;

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
            $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::ERROR] ?? []),
            $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::ERROR] ?? []),
        );
        if ($errors !== []) {
            $ret['errors'] = $errors;
        }

        /**
         * "warnings", "deprecations", and "logEntries" top-level entries:
         * since they are not part of the spec, place them under the top-level entry "extensions":
         *
         * > This entry is reserved for implementors to extend the protocol however they see fit,
         * > and hence there are no additional restrictions on its contents.
         *
         * @see http://spec.graphql.org/June2018/#sec-Response-Format
         */
        if ($this->addTopLevelExtensionsEntryToResponse()) {
            /** @var ModuleConfiguration */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();

            // Warnings
            if ($moduleConfiguration->enableProactiveFeedbackWarnings()) {
                $warnings = array_merge(
                    $this->reformatGeneralEntries($data[Response::GENERAL_FEEDBACK][FeedbackCategories::WARNING] ?? []),
                    $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::WARNING] ?? []),
                    $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::WARNING] ?? []),
                );
                if ($warnings !== []) {
                    $ret['extensions']['warnings'] = $warnings;
                }
            }

            // Deprecations
            if ($moduleConfiguration->enableProactiveFeedbackDeprecations()) {
                $deprecations = array_merge(
                    $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::DEPRECATION] ?? []),
                    $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::DEPRECATION] ?? []),
                );
                if ($deprecations !== []) {
                    $ret['extensions']['deprecations'] = $deprecations;
                }
            }

            // Notices
            if ($moduleConfiguration->enableProactiveFeedbackNotices()) {
                $notices = array_merge(
                    $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::NOTICE] ?? []),
                    $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::NOTICE] ?? []),
                );
                if ($notices !== []) {
                    $ret['extensions']['notices'] = $notices;
                }
            }

            // Suggestions
            if ($moduleConfiguration->enableProactiveFeedbackSuggestions()) {
                $notices = array_merge(
                    $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::SUGGESTION] ?? []),
                    $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::SUGGESTION] ?? []),
                );
                if ($notices !== []) {
                    $ret['extensions']['suggestions'] = $notices;
                }
            }

            // Logs
            if ($moduleConfiguration->enableProactiveFeedbackLogs()) {
                $logs = array_merge(
                    $this->reformatGeneralEntries($data[Response::GENERAL_FEEDBACK][FeedbackCategories::LOG] ?? []),
                    $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::LOG] ?? []),
                    $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::LOG] ?? []),
                );
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

    protected function reformatObjectEntries($entries): array
    {
        $ret = [];
        foreach ($entries as $typeOutputKey => $id_fieldItems) {
            foreach ($id_fieldItems as $id => $fieldItems) {
                /** @var FieldInterface $field */
                foreach ($fieldItems as $field) {
                    /** @var array<string,mixed> */
                    $items = $fieldItems[$field];
                    foreach ($items as $item) {
                        $ret[] = $this->getObjectEntry($typeOutputKey, $id, $item);
                    }
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

    protected function getObjectEntry(string $typeOutputKey, string|int $id, array $item): array
    {
        $entry = [];
        if ($message = $item[Tokens::MESSAGE] ?? null) {
            $entry['message'] = $message;
        }
        if ($locations = $item[Tokens::LOCATIONS] ?? null) {
            $entry['locations'] = $locations;
        }
        if (
            $extensions = array_merge(
                $this->getObjectEntryExtensions($typeOutputKey, $id, $item),
                $item[Tokens::EXTENSIONS] ?? []
            )
        ) {
            $entry['extensions'] = $extensions;
        }
        return $entry;
    }

    protected function getObjectEntryExtensions(string $typeOutputKey, int|string $id, array $item): array
    {
        $extensions = [
            'type' => $typeOutputKey,
        ];
        if ($field = $item[Tokens::FIELD] ?? null) {
            $extensions['field'] = $field;
        }
        $extensions['id'] = $id;
        $extensions['path'] = $item[Tokens::PATH];
        return $extensions;
    }

    /**
     * @param array<string,SplObjectStorage<FieldInterface,array<string,mixed>>> $entries
     */
    protected function reformatSchemaEntries(array $entries): array
    {
        $ret = [];
        foreach ($entries as $typeOutputKey => $storage) {
            foreach ($storage as $field) {
                /** @var FieldInterface $field */
                $items = $storage[$field];
                /** @var array<string,mixed> $items */
                foreach ($items as $item) {
                    $ret[] = $this->getSchemaEntry($typeOutputKey, $item);
                }
            }
        }
        return $ret;
    }

    protected function getSchemaEntry(string $typeOutputKey, array $item): array
    {
        $entry = [];
        if ($message = $item[Tokens::MESSAGE] ?? null) {
            $entry['message'] = $message;
        }
        if ($locations = $item[Tokens::LOCATIONS] ?? null) {
            $entry['locations'] = $locations;
        }
        if (
            $extensions = array_merge(
                $this->getSchemaEntryExtensions($typeOutputKey, $item),
                $item[Tokens::EXTENSIONS] ?? []
            )
        ) {
            $entry['extensions'] = $extensions;
        }
        return $entry;
    }

    protected function getSchemaEntryExtensions(string $typeOutputKey, array $item): array
    {
        $extensions = [
            'type' => $typeOutputKey,
        ];
        if ($field = $item[Tokens::FIELD] ?? null) {
            $extensions['field'] = $field;
        }
        $extensions['path'] = $item[Tokens::PATH];
        return $extensions;
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
        return [
            'message' => $message,
        ];
    }
}
