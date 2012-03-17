<?php
namespace RetryHandler;

use \RetryHandler\Proc;

use \Exception;

class InvocationCounter
{
    protected $_count = 0;

    protected $_proc;

    public function __construct($proc = NULL)
    {
        if ($proc) {
            $this->_proc = $proc;
        }
    }

    public function __invoke()
    {
        $this->_count++;
        if ($this->_proc) {
            return call_user_func_array($this->_proc, func_get_args());
        }
    }

    public function getCount()
    {
        return $this->_count;
    }
}

class ProcTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function retry_should_call_its_callable_once_if_no_exception_is_thrown()
    {
        $counter = new InvocationCounter;
        $proc = new Proc($counter);
        $proc->retry(3);
        $this->assertEquals(1, $counter->getCount());
    }

    /**
     * @test
     */
    public function retry_should_call_its_callable_repeatedly_until_its_max_count_if_exception_is_thrown()
    {
        $counter = new InvocationCounter(function () {
            throw new Exception;
        });
        $proc = new Proc($counter);
        $proc->retry(3);
        $this->assertEquals(3, $counter->getCount());
    }
}
