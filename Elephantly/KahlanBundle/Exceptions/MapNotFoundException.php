<?php

/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 19/02/17
 * Time: 22:43
 */
class MapNotFoundException extends InvalidArgumentException
{
    /**
     * TreeNotFoundException constructor.
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}