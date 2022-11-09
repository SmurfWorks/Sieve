<?php

namespace SmurfWorks\Sieve;

use Illuminate\Database\Eloquent\Builder;

class Sieve
{
    /**
     * Load the Sieve result from the given services.
     *
     * @param array $query The incoming query to parse and interpret
     *
     * @return array
     */
    public function handle(array $query) : array
    {
        try {
            /**
             * Interpret the given query string as an eloquent query.
             *
             * @var Builder $prepared
             */
            $prepared = ((new SieveParser)->handle($query))->resolve();

            /**
             * Now load the results using the cursor.
             *
             * @var CursorPaginator $result
             */
            $result = $prepared->cursorPaginate(config('sieve.result.size', 20));

            $result->makeVisible(
                array_keys(
                    cache()->rememberForever(
                        'sieve.schema',
                        function () {
                            return app('model-finder')
                                ->configure(config('sieve.namespaces'))
                                ->discover();
                        }
                    )[$query['model']]['attributes']
                )
            );

        } catch (\Exception $e) {
            return [
                'status' => 400,
                'data' => $e->getMessage(),
            ];
        }

        return [
            'status' => 200,
            'data' => [
                'query' => $prepared->toSql(),
                'page' => $result,
            ]
        ];
    }
}
