RetryHandler
============

master: [![Build Status](https://secure.travis-ci.org/yuya-takeyama/retry-handler-php.png?branch=master)](http://travis-ci.org/yuya-takeyama/retry-handler-php)
develop: [![Build Status](https://secure.travis-ci.org/yuya-takeyama/retry-handler-php.png?branch=develop)](http://travis-ci.org/yuya-takeyama/retry-handler-php)

Retrys any procedure.

Heavily inspired by @kimoto/retry-handler

Synopsis
--------

### Basic usage

```php
<?php
use \RetryHandler\Proc;
use \RetryHandler\RetryOverException;

$proc = new Proc(function () {
    // Some procedure may cause error.
});
try {
    // Trys to invoke procedure until it succeeded.
    $proc->retry(3);
} catch (RetryOverException $e) {
    // 3 times of trial had been failed.
}
```

### Wait after failure

Waits specified seconds after a trial failed. 1 second by default.

```php
<?php
$proc->retry(3, array('wait' => '5'));
```

### Specify accepted exception

```php
<?php
$proc->retry(3, array('accepted_exception' => 'Guzzle\Http\HttpException'));
```

Author
------

Yuya Takeyama [http://yuyat.jp/](http://yuyat.jp/)
