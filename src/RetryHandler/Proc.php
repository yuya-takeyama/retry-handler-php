<?php
namespace RetryHandler;

use \Exception;

class Proc
{
    const DEFAULT_MAX_RETRY = 3;
    const DEFAULT_WAIT_TIME = 1;
    const DEFAULT_ACCEPTED_EXCEPTION = 'RetryHandler\RetryException';

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

    public function retry($options, $moreOptions = array())
    {
        if (is_int($options)) {
            $options = array('max' => $options);
        }
        $options = array_merge($options, $moreOptions);

        $max = isset($options['max']) ? $options['max'] : self::DEFAULT_MAX_RETRY;
        $wait = isset($options['wait']) ? $options['wait'] : self::DEFAULT_WAIT_TIME;
        $exception = isset($options['accepted_exception']) ?
            $options['accepted_exception'] :
            self::DEFAULT_ACCEPTED_EXCEPTION;

        return $this->_retry($max, $wait, $exception);
    }

    protected function _retry($max, $wait, $acceptedException)
    {
        for ($i = 0; $i < $max; $i++) {
            try {
                return call_user_func($this->_proc);
            } catch (Exception $e) {
            }
        }
    }
}
