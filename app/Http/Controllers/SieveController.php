<?php

namespace SmurfWorks\SieveApp\Http\Controllers;

class SieveController extends Controller
{
    /**
     * Using the schema, we can deliver the form.
     *
     * @return Response
     */
    public function index()
    {
        /**
         * Get the schema from the model finder.
         *
         * @var array $schema
         */
        $schema = cache()->rememberForever(
            'sieve.schema',
            function () {
                return app('model-finder')
                    ->configure(config('sieve.namespaces'))
                    ->discover();
            }
        );

        return view(config('sieve.views.index', 'sieve::index'), compact('schema'));
    }

    /**
     * Request results for the given query and page.
     *
     * @return Response
     */
    public function result()
    {
        return response(
            $data = app('sieve')->handle(request()->get('query')),
            $data['status']
        );
    }
}
