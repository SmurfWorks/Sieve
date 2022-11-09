<?php

namespace SmurfWorks\SieveTests\Unit;

use Illuminate\Database\Eloquent\Builder;
use SmurfWorks\Sieve\SieveParser;

class SieveQueryTest extends \SmurfWorks\SieveTests\TestCase
{
    /**
     * Test a simple query will return the expected Eloquent Builder object.
     *
     * @return void
     */
    public function testSimpleQuery() : void
    {
        /**
         * Get the sieve query object's eloquent query.
         *
         * @var Builder $result
         */
        $result = ((new SieveParser)->handle($this->testPayload('simple')))->resolve();

        $this->assertInstanceOf(Builder::class, $result);
        $this->assertEquals(1, $result->count());
    }

    /**
     * Test an attribute query will return the expected Eloquent Builder object.
     *
     * @return void
     */
    public function testAttributesQuery() : void
    {
        /**
         * Get the sieve query object's eloquent query.
         *
         * @var Builder $result
         */
        $result = ((new SieveParser)->handle($this->testPayload('attributes')))->resolve();

        $this->assertInstanceOf(Builder::class, $result);
        $this->assertStringContainsString('"name" = ?', $result->toSql());

        $this->assertEquals(0, $result->count());
    }

    /**
     * Test a scoped query will return the expected Eloquent Builder object,
     * with a scoped applied.
     *
     * @return void
     */
    public function testScopedQuery() : void
    {
        /**
         * Get the sieve query object's eloquent query.
         *
         * @var Builder $result
         */
        $result = ((new SieveParser)->handle($this->testPayload('scoped')))->resolve();

        $this->assertInstanceOf(Builder::class, $result);
        $this->assertStringContainsString('"password" is not null', $result->toSql());
        $this->assertStringContainsString('"receive_newsletter" = ?', $result->toSql());

        $this->assertEquals(2, $result->count());
    }

    /**
     * Test a nested query will return the expected Eloquent Builder object,
     * with a scoped applied, with nested relations with attribute checks.
     *
     * @return void
     */
    public function testNestedQuery() : void
    {
        /**
         * Get the sieve query object's eloquent query.
         *
         * @var Builder $result
         */
        $result = ((new SieveParser)->handle($this->testPayload('nested')))->resolve();

        $this->assertInstanceOf(Builder::class, $result);
        $this->assertEquals(1, $result->count());
    }
}
