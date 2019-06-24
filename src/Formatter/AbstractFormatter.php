<?php
namespace Idealogica\ErrorHandler\Formatter;

/**
 * Class AbstractFormatter
 * @package Idealogica\ErrorHandler\Formatter
 */
abstract class AbstractFormatter extends \League\BooBoo\Formatter\AbstractFormatter
{
    use FormatterTrait;

    /**
     * AbstractFormatter constructor.
     *
     * @param string|null $defaultErrorMessage
     * @param string|null $publicExceptionClassName
     * @param bool $debugMode
     */
    public function __construct(
        string $defaultErrorMessage = null,
        string $publicExceptionClassName = null,
        bool $debugMode = false
    ) {
        $this->errorLimit = null;
        $this->debugMode = $debugMode;
        $this->publicExceptionClassName = $publicExceptionClassName;
        $this->defaultErrorMessage = $defaultErrorMessage;
    }
}
