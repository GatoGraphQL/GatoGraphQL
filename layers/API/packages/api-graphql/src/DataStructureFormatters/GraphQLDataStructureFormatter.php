<?php

declare(strict_types=1);

namespace PoPAPI\GraphQLAPI\DataStructureFormatters;

use PoP\ComponentModel\Constants\Response;
use PoP\ComponentModel\Feedback\FeedbackCategories;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
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
            $this->reformatDocumentEntries($data[Response::DOCUMENT_FEEDBACK][FeedbackCategories::ERROR] ?? []),
            $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::ERROR] ?? []),
            $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::ERROR] ?? []),
        );
        if ($errors !== []) {
            $ret['errors'] = $errors;
        }

        $this->maybeAddTopLevelExtensionsEntryToResponse($ret, $data);

        if ($resultData = parent::getFormattedData($data)) {
            $ret['data'] = $resultData;
        }

        return $ret;
    }

    /**
     * "warnings", "deprecations" and "logs" are top-level entries:
     * since they are not part of the spec, place them under
     * the top-level entry "extensions":
     *
     * > This entry is reserved for implementors to extend the protocol however they see fit,
     * > and hence there are no additional restrictions on its contents.
     *
     * @see http://spec.graphql.org/June2018/#sec-Response-Format
     */
    protected function maybeAddTopLevelExtensionsEntryToResponse(array &$ret, array $data): void
    {
        if (!$this->addTopLevelExtensionsEntryToResponse()) {
            return;
        }

        // Deprecations
        $deprecations = array_merge(
            $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::DEPRECATION] ?? []),
            $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::DEPRECATION] ?? []),
        );
        if ($deprecations !== []) {
            $ret['extensions']['deprecations'] = $deprecations;
        }

        // Warnings
        $warnings = array_merge(
            $this->reformatGeneralEntries($data[Response::GENERAL_FEEDBACK][FeedbackCategories::WARNING] ?? []),
            $this->reformatDocumentEntries($data[Response::DOCUMENT_FEEDBACK][FeedbackCategories::WARNING] ?? []),
            $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::WARNING] ?? []),
            $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::WARNING] ?? []),
        );
        if ($warnings !== []) {
            $ret['extensions']['warnings'] = $warnings;
        }

        // Logs
        $logs = array_merge(
            $this->reformatGeneralEntries($data[Response::GENERAL_FEEDBACK][FeedbackCategories::LOG] ?? []),
            $this->reformatDocumentEntries($data[Response::DOCUMENT_FEEDBACK][FeedbackCategories::LOG] ?? []),
            $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::LOG] ?? []),
            $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::LOG] ?? []),
        );
        if ($logs !== []) {
            $ret['extensions']['logs'] = $logs;
        }

        // Notices
        $notices = array_merge(
            $this->reformatGeneralEntries($data[Response::GENERAL_FEEDBACK][FeedbackCategories::NOTICE] ?? []),
            $this->reformatDocumentEntries($data[Response::DOCUMENT_FEEDBACK][FeedbackCategories::NOTICE] ?? []),
            $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::NOTICE] ?? []),
            $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::NOTICE] ?? []),
        );
        if ($notices !== []) {
            $ret['extensions']['notices'] = $notices;
        }

        // Suggestions
        $suggestions = array_merge(
            $this->reformatGeneralEntries($data[Response::GENERAL_FEEDBACK][FeedbackCategories::SUGGESTION] ?? []),
            $this->reformatDocumentEntries($data[Response::DOCUMENT_FEEDBACK][FeedbackCategories::SUGGESTION] ?? []),
            $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::SUGGESTION] ?? []),
            $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::SUGGESTION] ?? []),
        );
        if ($suggestions !== []) {
            $ret['extensions']['suggestions'] = $suggestions;
        }
    }

    /**
     * Indicate if to add entry "extensions" as a top-level entry
     */
    protected function addTopLevelExtensionsEntryToResponse(): bool
    {
        return true;
    }

    protected function reformatGeneralEntries(array $entries): array
    {
        $ret = [];
        foreach ($entries as $item) {
            $ret[] = $this->getGeneralEntry($item);
        }
        return $ret;
    }

    protected function getGeneralEntry(array $item): array
    {
        $entry = [
            'message' => $item[Tokens::MESSAGE],
        ];
        if ($extensions = $item[Tokens::EXTENSIONS]) {
            $entry['extensions'] = $extensions;
        }
        return $entry;
    }

    protected function reformatDocumentEntries(array $entries): array
    {
        $ret = [];
        foreach ($entries as $item) {
            $ret[] = $this->getDocumentEntry($item);
        }
        return $ret;
    }

    protected function getDocumentEntry(array $item): array
    {
        $entry = [
            'message' => $item[Tokens::MESSAGE],
        ];
        if ($locations = $item[Tokens::LOCATIONS]) {
            $entry['locations'] = $locations;
        }
        if (
            $extensions = array_merge(
                $this->getDocumentEntryExtensions($item),
                $item[Tokens::EXTENSIONS] ?? []
            )
        ) {
            $entry['extensions'] = $extensions;
        }
        return $entry;
    }

    protected function getDocumentEntryExtensions(array $item): array
    {
        $extensions = [];
        if ($path = $item[Tokens::PATH] ?? null) {
            $extensions['path'] = $path;
        }
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
        $entry = [
            'message' => $item[Tokens::MESSAGE],
        ];
        if ($locations = $item[Tokens::LOCATIONS]) {
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
        $extensions = [];
        if ($path = $item[Tokens::PATH] ?? null) {
            $extensions['path'] = $path;
        }
        $extensions['type'] = $typeOutputKey;
        if ($field = $item[Tokens::FIELD] ?? null) {
            $extensions['field'] = $field;
        } elseif ($dynamicField = $item[Tokens::DYNAMIC_FIELD] ?? null) {
            $extensions['dynamicField'] = $dynamicField;
        }
        return $extensions;
    }

    protected function reformatObjectEntries(array $entries): array
    {
        $ret = [];
        foreach ($entries as $typeOutputKey => $fieldItems) {
            /** @var FieldInterface $field */
            foreach ($fieldItems as $field) {
                /** @var array<string,mixed> */
                $items = $fieldItems[$field];
                foreach ($items as $item) {
                    $ret[] = $this->getObjectEntry($typeOutputKey, $item);
                }
            }
        }
        return $ret;
    }

    protected function getObjectEntry(string $typeOutputKey, array $item): array
    {
        $entry = [
            'message' => $item[Tokens::MESSAGE],
        ];
        if ($locations = $item[Tokens::LOCATIONS]) {
            $entry['locations'] = $locations;
        }
        if (
            $extensions = array_merge(
                $this->getObjectEntryExtensions($typeOutputKey, $item),
                $item[Tokens::EXTENSIONS] ?? []
            )
        ) {
            $entry['extensions'] = $extensions;
        }
        return $entry;
    }

    /**
     * The entry is similar to Schema, plus the
     * addition of the object ID/IDs
     */
    protected function getObjectEntryExtensions(string $typeOutputKey, array $item): array
    {
        $extensions = $this->getSchemaEntryExtensions($typeOutputKey, $item);

        /** @var array<string|int> */
        $ids = $item[Tokens::IDS];
        if (count($ids) === 1) {
            $extensions['id'] = $ids[0];
        } else {
            $extensions['ids'] = $ids;
        }

        return $extensions;
    }
}
