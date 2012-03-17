<?php
namespace RetryHandler;

use \RetryHandler\Proc;
use \RetryHandler\RetryOverException;

use \Exception;
use \RuntimeException;
use \LogicException;

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
            throw new RuntimeException;
        };
        $proc = new Proc($counter);
        try {
            $proc->retry(3);
        } catch (RetryOverException $e) {}
        $this->assertEquals(3, $count);
    }

    /**
     * @test
     * @expectedException RetryHandler\RetryOverException
     */
    public function retry_should_throw_RetryOverException_if_all_trial_failed()
    {
        $proc = new Proc(function () {
            throw new RuntimeException;
        });
        $proc->retry(1);
    }

    /**
     * @test
     */
    public function retry_should_wait_specified_seconds_before_next_trial()
    {
        $proc = new Proc(function () {
            throw new RuntimeException;
        });
        $begin = time();
        try {
            $proc->retry(3, array('wait' => '2'));
        } catch (RetryOverException $e) {}
        $end = time();
        $this->assertEquals(2 * 2, $end - $begin);
    }

    /**
     * @test
     * @expectedException LogicException
     */
    public function retry_should_throw_Exception_thrown_if_it_is_not_accepted_exception()
    {
        $proc = new Proc(function () {
            throw new LogicException;
        });
        $proc->retry(3);
    }
}
