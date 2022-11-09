<?php

namespace SmurfWorks\SieveTests\Feature;

use SmurfWorks\SieveTests\TestCase;

class ApplicationTest extends TestCase
{
    /**
     * Test the index route is returning a view.
     *
     * @return void
     */
    public function testIndex() : void
    {
        /**
         * Get the route response.
         *
         * @var TestResponse $response
         */
        $response = $this->get(route('sieve.index'));
        $response->assertStatus(200);
    }

    /**
     * Test the given query returns a result.
     *
     * @return void
     */
    public function testResultSuccess() : void
    {
        /**
         * Get the route response.
         *
         * @var TestResponse $response
         */
        $response = $this->postJson(route(
            'sieve.result'),
            ['query' => $this->testPayload('simple')]
        );

        $response->assertStatus(200);
        $response->assertJsonPath('status', 200);
    }
}
