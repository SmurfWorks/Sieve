<?php

namespace SmurfWorks\SieveTests\Unit;

use SmurfWorks\Sieve\SieveParser;
use SmurfWorks\Sieve\SieveQuery;

class SieveParserTest extends \SmurfWorks\SieveTests\TestCase
{
    /**
     * Test a simple query can be parsed successfully.
     *
     * @return void
     */
    public function testSimpleQuery() : void
    {
        /**
         * Use the parser service to prepare a SieveQuery object.
         *
         * @var SieveQuery $result
         */
        $result = (new SieveParser)->handle($this->testPayload('simple'));

        $this->assertInstanceOf(SieveQuery::class, $result);
    }

    /**
     * Test a attribute query can be parsed successfully.
     *
     * @return void
     */
    public function testAttributesQuery() : void
    {
        /**
         * Use the parser service to prepare a SieveQuery object.
         *
         * @var SieveQuery $result
         */
        $result = (new SieveParser)->handle($this->testPayload('attributes'));

        $this->assertInstanceOf(SieveQuery::class, $result);
    }

    /**
     * Test a scoped query can be parsed successfully.
     *
     * @return void
     */
    public function testScopedQuery() : void
    {
        /**
         * Use the parser service to prepare a SieveQuery object.
         *
         * @var SieveQuery $result
         */
        $result = (new SieveParser)->handle($this->testPayload('scoped'));

        $this->assertInstanceOf(SieveQuery::class, $result);
    }
}
