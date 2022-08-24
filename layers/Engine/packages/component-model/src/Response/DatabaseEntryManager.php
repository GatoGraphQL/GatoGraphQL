<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Response;

use PoP\ComponentModel\App;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Services\BasicServiceTrait;
use SplObjectStorage;

class DatabaseEntryManager implements DatabaseEntryManagerInterface
{
    use BasicServiceTrait;
    
    public const PRIMARY_DBNAME = 'primary';
    public const HOOK_DBNAME_TO_FIELDNAMES = __CLASS__ . ':dbName-to-fieldNames';

    /**
     * @var array<string,string[]>|null
     */
    protected ?array $dbNameFieldNames = null;

    /**
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $entries
     * @return array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>>
     */
    public function moveEntriesWithIDUnderDBName(
        array $entries,
        RelationalTypeResolverInterface $relationalTypeResolver
    ): array {
        if (!$entries) {
            return [];
        }

        /** @var array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> */
        $dbname_entries = $this->getEntriesUnderPrimaryDBName($entries);
        $dbNameToFieldNames = $this->getDBNameFieldNames($relationalTypeResolver);
        foreach ($dbname_entries[self::PRIMARY_DBNAME] as $id => $fieldValues) {
            $fields = iterator_to_array($fieldValues);
            foreach ($dbNameToFieldNames as $dbName => $fieldNames) {
                $fields_to_move = array_filter(
                    $fields,
                    fn (FieldInterface $field) => in_array($field->getName(), $fieldNames),
                );
                $dbname_entries[$dbName][$id] ??= new SplObjectStorage();
                foreach ($fields_to_move as $field) {
                    $dbname_entries[$dbName][$id][$field] = $dbname_entries[self::PRIMARY_DBNAME][$id][$field];
                    $dbname_entries[self::PRIMARY_DBNAME][$id]->detach($field);
                }
            }
        }
        return $dbname_entries;
    }

    /**
     * Place all entries under dbName "primary"
     *
     * @param SplObjectStorage<FieldInterface,mixed>|array<string|int,SplObjectStorage<FieldInterface,mixed>> $entries
     * @return array<string,SplObjectStorage<FieldInterface,mixed>>|array<mixed,array<int|string,SplObjectStorage<FieldInterface,mixed>>>
     */
    protected function getEntriesUnderPrimaryDBName(
        array|SplObjectStorage $entries,
    ): array {
        return [
            self::PRIMARY_DBNAME => $entries,
        ];
    }

    /**
     * Allow to inject what data fields must be placed under what dbNames
     *
     * @return array<string,string[]> Array of key: dbName, values: field names
     */
    protected function getDBNameFieldNames(
        RelationalTypeResolverInterface $relationalTypeResolver
    ): array {
        if ($this->dbNameFieldNames === null) {
            $this->dbNameFieldNames = App::applyFilters(
                self::HOOK_DBNAME_TO_FIELDNAMES,
                [],
                $relationalTypeResolver
            );
        }
        return $this->dbNameFieldNames;
    }

    /**
     * @param SplObjectStorage<FieldInterface,mixed> $entries
     * @return array<string,SplObjectStorage<FieldInterface,mixed>>
     */
    public function moveEntriesWithoutIDUnderDBName(
        SplObjectStorage $entries,
        RelationalTypeResolverInterface $relationalTypeResolver
    ): array {
        if ($entries->count() === 0) {
            return [];
        }

        // By default place everything under "primary"
        /** @var array<string,SplObjectStorage<FieldInterface,mixed>> */
        $dbname_entries = $this->getEntriesUnderPrimaryDBName($entries);
        $dbNameToFieldNames = $this->getDBNameFieldNames($relationalTypeResolver);
        $fields = iterator_to_array($entries);
        foreach ($dbNameToFieldNames as $dbName => $fieldNames) {
            // Move these data fields under "meta" DB name
            $fields_to_move = array_filter(
                $fields,
                fn (FieldInterface $field) => in_array($field->getName(), $fieldNames),
            );
            foreach ($fields_to_move as $field) {
                $dbname_entries[$dbName] ??= new SplObjectStorage();
                $dbname_entries[$dbName][$field] = $dbname_entries[self::PRIMARY_DBNAME][$field];
                $dbname_entries[self::PRIMARY_DBNAME]->detach($field);
            }
        }
        return $dbname_entries;
    }
}
