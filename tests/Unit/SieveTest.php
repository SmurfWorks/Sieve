<?php

namespace SmurfWorks\SieveTests\Unit;

class SieveTest extends \SmurfWorks\SieveTests\TestCase
{
    /**
     * Test a simple query will return a response successfully.
     *
     * @return void
     */
    public function testSimpleQuery() : void
    {
        /**
         * Create the sieve service and pass a query as expected.
         *
         * @var array $result
         */
        $result = app('sieve')->handle($this->testPayload('simple'));

        $this->assertInstanceOf(
            \Illuminate\Pagination\CursorPaginator::class,
            $result['data']['page']
        );
    }
}
