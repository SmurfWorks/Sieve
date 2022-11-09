<?php

namespace SmurfWorks\Sieve;

use Illuminate\Support\Arr;

class SieveParser
{
    /**
     * Parse the incoming query array into a SieveQuery object.
     *
     * @param array $payload The incoming query array
     *
     * @return SieveQuery
     */
    public function handle(array $payload) : SieveQuery
    {
        return $this->recurse($payload);
    }

    /**
     * Interpret the configuration at the given level, and recurse relations to build
     * sub-query objects.
     *
     * @param array $payload The query payload at the given level
     *
     * @return SieveQuery
     */
    protected function recurse(array $payload) : SieveQuery
    {
        /**
         * Get the model from the payload.
         *
         * @var string $model
         */
        if (!class_exists($model = Arr::get($payload, 'model'))) {
            throw new Exception\SieveParserError('Sieve Query Parser: Invalid model specified.');
        }

        /**
         * Create a query object from the query payload.
         *
         * @var SieveQuery $query
         */
        $query = new SieveQuery($model);

        foreach (Arr::get($payload, 'query.attributes', []) as $config) {
            $query->addAttribute(
                Arr::get($config, 'field'),
                Arr::get($config, 'operator'),
                Arr::get($config, 'value')
            );
        }

        foreach (Arr::get($payload, 'query.scopes', []) as $config) {
            $query->addScope($config);
        }

        foreach (Arr::get($payload, 'query.relations', []) as $config) {
            $query->addRelation(Arr::get($config, 'relation'), $this->recurse($config));
        }

        return $query;
    }
}
