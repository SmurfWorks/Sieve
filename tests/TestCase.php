<?php

namespace SmurfWorks\SieveTests;

use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    /**
     * Seed the tests.
     *
     * @var boolean
     */
    protected $seed = true;

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application $app The application context
     *
     * @return array<int, string>
     */
    protected function getPackageProviders($app)
    {
        return [
            'SmurfWorks\ModelFinder\ModelFinderProvider',
            'SmurfWorks\Sieve\SieveProvider'
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app The application context
     *
     * @return void
     */
    protected function defineEnvironment($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set(
            'database.connections.testbench',
            [
                'driver'   => 'sqlite',
                'database' => ':memory:',
                'prefix'   => '',
            ]
        );

        $app['config']->set('sieve', require sprintf('%s/../config/sieve.php', __DIR__));
        $app['config']->set('sieve.namespaces', 'SmurfWorks\\ModelFinderTests\\SampleModels');
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(
            sprintf('%s/../vendor/smurfworks/model-finder/tests/database/migrations', __DIR__)
        );
    }

    /**
     * Get a test payload from a json file based on the key.
     *
     * @param string $key The file key
     *
     * @return array
     */
    protected function testPayload(string $key) : array
    {
        return json_decode(file_get_contents(sprintf('%s/payloads/%s.json', __DIR__, $key)), true);
    }
}
