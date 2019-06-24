<?php
namespace Idealogica\ErrorHandler\Handler;

use League\BooBoo\Handler\HandlerInterface;

/**
 * Class AbstractHandler
 * @package Idealogica\ErrorHandler\Handler
 */
abstract class AbstractHandler implements HandlerInterface
{
    use HandlerTrait;
}
