<?php

declare(strict_types=1);

namespace App\Services;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DynamicFilter
{
    protected $tableName;

    protected $customFilters = [];

    protected $query;

    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
        $this->query = DB::table($tableName);
    }

    /**
     * Fetch column types from the database schema.
     */
    public function getColumnTypes(): array
    {
        $columns = Schema::getColumnListing($this->tableName);
        $columnTypes = [];

        foreach ($columns as $column) {
            // Use raw SQL to fetch column types (Laravel no longer supports Doctrine)
            $type = DB::selectOne('
                SELECT DATA_TYPE
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_NAME = ? AND COLUMN_NAME = ? AND TABLE_SCHEMA = ?
            ', [$this->tableName, $column, env('DB_DATABASE')])->DATA_TYPE;

            $columnTypes[$column] = $type;
        }

        return $columnTypes;
    }

    /**
     * Add custom filter for a specific column.
     */
    public function addCustomFilter(string $column, Closure $filter): self
    {
        $this->customFilters[$column] = $filter;

        return $this;
    }

    /**
     * Apply filters to the query based on request parameters.
     */
    public function applyFilters(array $params): self
    {
        $columnTypes = $this->getColumnTypes();

        foreach ($params as $column => $value) {
            if (isset($this->customFilters[$column])) {
                // Apply custom filter
                $this->query = ($this->customFilters[$column])($this->query, $value);
            } elseif (isset($columnTypes[$column])) {
                // Apply default filter based on column type
                $this->applyDefaultFilter($column, $value, $columnTypes[$column]);
            }
        }

        return $this;
    }

    /**
     * Apply default filter based on column type.
     */
    protected function applyDefaultFilter(string $column, $value, string $type): void
    {
        switch ($type) {
            case 'varchar':
            case 'text':
                $this->query->where($column, 'like', "%$value%");
                break;

            case 'datetime':
            case 'timestamp':
                $this->query->whereDate($column, $value);
                break;

            case 'int':
            case 'tinyint':
            case 'boolean':
                $this->query->where($column, $value);
                break;

            default:
                // Add other type handlers as needed
                $this->query->where($column, $value);
                break;
        }
    }

    /**
     * Get the final query result.
     */
    public function getResult(): Collection
    {
        return $this->query->get();
    }
}
