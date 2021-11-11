<?php
namespace Idealogica\ErrorHandler\Formatter;

use League\BooBoo\Formatter\CommandLineFormatter as BooBooCommandLineFormatter;

/**
 * Class CommandLineFormatter
 * @package Idealogica\ErrorHandler\Formatter
 */
class CommandLineFormatter extends BooBooCommandLineFormatter
{
    use FormatterTrait;

    /**
     * @param \Exception $e
     *
     * @return void
     */
    public function format($e)
    {
        echo (parent::format($e));
        exit (255);
    }
}
