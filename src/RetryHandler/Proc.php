<?php
namespace RetryHandler;

use \Exception;

class Proc
{
    /**
     * @var callable
     */
    protected $_proc;

    /**
     * Constructor.
     *
     * Wraps something callable.
     *
     * @param  callable $proc
     */
    public function __construct($proc)
    {
        $this->_proc = $proc;
    }

    public function retry($max)
    {
        for ($i = 0; $i < $max; $i++) {
            try {
                return call_user_func($this->_proc);
            } catch (Exception $e) {
            }
        }
    }
}
