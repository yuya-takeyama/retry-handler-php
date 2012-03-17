<?php
namespace RetryHandler;

use \RetryHandler\Proc;

use \Exception;

class ProcTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function retry_should_call_its_callable_once_if_no_exception_is_thrown()
    {
        $count = 0;
        $counter = function () use (&$count) {
            $count++;
        };
        $proc = new Proc($counter);
        $proc->retry(3);
        $this->assertEquals(1, $count);
    }

    /**
     * @test
     */
    public function retry_should_call_its_callable_repeatedly_until_its_max_count_if_exception_is_thrown()
    {
        $count = 0;
        $counter = function () use (&$count) {
            $count++;
            throw new Exception;
        };
        $proc = new Proc($counter);
        $proc->retry(3);
        $this->assertEquals(3, $count);
    }
}
