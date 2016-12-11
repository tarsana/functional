<?php namespace Tarsana\UnitTests\Functional;

/**
 * The parent class for unit tests.
 */
abstract class UnitTest extends \PHPUnit_Framework_TestCase {
    /**
     * Checks all assertions.
     * @param  array $assertions [[expected1, value1], [expected2, value2], ...]
     * @return void
     */
    protected function assertAll($assertions) {
        foreach ($assertions as $assertion) {
            $this->assertEquals($assertion[0], $assertion[1]);
        }
    }

    /**
     * Assert that the result is an error with the provided message.
     * @param  mixed $result
     * @param  string $msg
     * @return void
     */
    protected function assertError($result, $msg) {
        $this->assertTrue($result instanceof Error);
        $this->assertEquals($msg, $result->getMessage());
    }

    /**
     * Asserts that an Error with a specific message is thrown when runing a function.
     * @param  callable $fn
     * @param  string $msg
     * @return void
     */
    protected function assertErrorThrown($fn, $msg) {
        try {
            $fn();
            $this->assertTrue(false); // is executed then no exception was thrown !
        } catch (\Exception $e) {
            $this->assertError($e, $msg);
        }
    }

}
