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
     * @param string[] $publicExceptionClassNames
     * @param bool $debugMode
     */
    public function __construct(
        string $defaultErrorMessage = null,
        array $publicExceptionClassNames = [],
        bool $debugMode = false
    ) {
        $this->errorLimit = null;
        $this->debugMode = $debugMode;
        $this->publicExceptionClassNames = $publicExceptionClassNames;
        $this->defaultErrorMessage = $defaultErrorMessage;
    }
}
