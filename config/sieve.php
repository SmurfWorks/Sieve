<?php

return [
    /**
     * Where your models are located. This is recursively scanned for models,
     * so if you don't have a specific model directory, just use your root namespace.
     *
     * @var string|array
     */
    'namespaces' => [
        'App\\Models'
    ],

    /**
     * This part of the configuration allows you to enable/disable or customise the sieve
     * routes for the web application provided by default.
     *
     * @var array
     */
    'routes' => [
        /**
         * This will stop the routes from being registered.
         *
         * @var boolean
         */
        'enabled' => true,

        /**
         * The prefix use on all of the sieve application routes.
         *
         * @var string
         */
        'prefix' => 'sieve',

        /**
         * Add your middleware for sieve to this array.
         *
         * @var array
         */
        'middleware' => []
    ],

    /**
     * Allow override of any views used, to allow you to integrate sieve into your application
     * however you choose.
     *
     * @var array
     */
    'views' => [
        'index' => 'sieve::index'
    ],

    /**
     * Customise how the results of the Sieve service are returned.
     *
     * @var array
     */
    'result' => [
        /**
         * The size of the result to return per-page.
         *
         * @var int
         */
        'size' => 20
    ]
];
