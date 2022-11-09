<?php

namespace SmurfWorks\Sieve;

use Illuminate\Database\Eloquent\Builder;

class SieveQuery
{
    const OPERATOR_EQUAL = 'eq';
    const OPERATOR_NOT_EQUAL = 'neq';
    const OPERATOR_GREATER_THAN = 'gt';
    const OPERATOR_GREATER_THAN_OR_EQUAL = 'gte';
    const OPERATOR_LESS_THAN = 'lt';
    const OPERATOR_LESS_THAN_OR_EQUAL = 'lte';
    const OPERATOR_NULL = 'null';
    const OPERATOR_NOT_NULL = 'notnull';

    const LOGICAL_AND = 'and';
    const LOGICAL_OR = 'or';

    /**
     * Simple operator map for attribute criteria queries.
     *
     * @var array
     */
    public static $operatorMap = [

        self::OPERATOR_EQUAL => '=',
        self::OPERATOR_NOT_EQUAL => '!=',
        self::OPERATOR_GREATER_THAN => '>',
        self::OPERATOR_GREATER_THAN_OR_EQUAL => '>=',
        self::OPERATOR_LESS_THAN => '<',
        self::OPERATOR_GREATER_THAN_OR_EQUAL => '=<'
    ];

    /**
     * Record the attribute criteria applied.
     *
     * @var array
     */
    protected array $attributes = [];

    /**
     * The model that this sieve query is for.
     *
     * @var string
     */
    protected string $model;

    /**
     * Record what scopes have been applied.
     *
     * @var array
     */
    protected array $scopes = [];

    /**
     * Relations to be used in this query.
     *
     * @var array
     */
    protected array $relations = [];

    /**
     * Prepare a query for the given model.
     *
     * @var Builder
     */
    protected $query;

    public function __construct(string $model)
    {
        $this->model = $model;
    }

    /**
     * Get the final builder object from this SieveQuery
     *
     * @param Builder $baseQuery Relational queries provide a builder to start with
     *
     * @return Builder
     */
    public function resolve(Builder $baseQuery = null) : Builder
    {
        /* Start the query as required */
        $this->query = $baseQuery ?: $this->model::withoutGlobalScopes();

        $this->resolveAttributes();
        $this->resolveScopes();
        $this->resolveRelations();

        return $this->query;
    }

    /**
     * Add a simple attribute check to this query.
     *
     * @param string $field    The attribute/field name
     * @param string $operator The operator to use (See constants)
     * @param mixed  $value    Optionally provide a value for default operators
     *
     * @return self
     */
    public function addAttribute(string $field, string $operator, $value = null) : self
    {
        if (!isset($this->attributes[$field])) {
            $this->attributes[$field] = [];
        }

        $this->attributes[$field][] = compact('field', 'operator', 'value');

        return $this;
    }

    /**
     * Apply a scope from the model.
     *
     * @param string $scopeName The scope to apply
     *
     * @return self
     */
    public function addScope(string $scopeName) : self
    {
        $this->scopes[] = $scopeName;

        return $this;
    }

    /**
     * Add a relation to this query to resolve.
     *
     * @param string     $name     The relation to apply the query to
     * @param SieveQuery $subQuery The query/criteria to use with the relation
     * @return self
     */
    public function addRelation($name, SieveQuery $subQuery) : self
    {
        $this->relations[$name] = [
            'loaded' => false,
            'query' => $subQuery
        ];

        return $this;
    }

    /**
     * Resolve the attribute criteria applied to this query.
     *
     * @return void
     */
    protected function resolveAttributes() : void
    {
        foreach ($this->attributes as $configs) {
            foreach ($configs as $config) {
                switch ($config['operator']) {
                    case self::OPERATOR_NULL:
                        $this->query->whereNull(sprintf("%s.%s", (new $this->model)->getTable(), $config['field']));
                        break;
                    case self::OPERATOR_NOT_NULL:
                        $this->query->whereNotNull(sprintf("%s.%s", (new $this->model)->getTable(), $config['field']));
                        break;

                    default:
                        $this->query->where(
                            sprintf('%s.%s', (new $this->model)->getTable(), $config['field']),
                            self::$operatorMap[$config['operator']],
                            $config['value']
                        );
                }
            }
        }
    }

    /**
     * Resolve the scopes that have been applied to this query.
     *
     * @return void
     */
    protected function resolveScopes() : void
    {
        foreach ($this->scopes as $scopeName) {
            $this->query->{$scopeName}();
        }
    }

    /**
     * Resolve the relational criteria applied to this query.
     *
     * @return void
     */
    protected function resolveRelations() : void
    {
        foreach ($this->relations as $name => $config) {
            $this->query->whereHas(
                $name,
                function ($query) use ($config) {
                    return $config['query']->resolve($query->withoutGlobalScopes());
                }
            );
        }
    }
}
