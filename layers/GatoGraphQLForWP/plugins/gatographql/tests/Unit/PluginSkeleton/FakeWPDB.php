<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Unit\PluginSkeleton;

/**
 * The slice of `wpdb` the uninstaller touches. `prepare()` substitutes the
 * placeholders the way `wpdb` does for the values used here, so that the
 * assertions can be made on the final statements.
 */
class FakeWPDB
{
    public string $prefix = 'wp_';

    /**
     * @var string[]
     */
    public array $queries = [];

    /**
     * @var array<string,string[]> The rows `get_col` answers, keyed by the start of the statement
     */
    public array $columns = [];

    public function __get(string $name): string
    {
        return $this->prefix . $name;
    }

    public function esc_like(string $text): string // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    {
        return addcslashes($text, '_%\\');
    }

    public function prepare(string $query, mixed ...$args): string
    {
        $query = str_replace(['%d', '%s'], ['%d', "'%s'"], $query);
        return vsprintf($query, array_map(
            static fn (mixed $arg): mixed => is_string($arg) ? addslashes($arg) : $arg,
            $args
        ));
    }

    public function query(string $query): int
    {
        $this->queries[] = $query;
        return 1;
    }

    /**
     * @return string[]
     */
    public function get_col(string $query): array // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    {
        $matches = [];
        foreach ($this->columns as $prefix => $rows) {
            if (str_starts_with($query, $prefix)) {
                $matches[strlen($prefix)] = $rows;
            }
        }
        if ($matches === []) {
            return [];
        }
        krsort($matches);
        return reset($matches);
    }
}
