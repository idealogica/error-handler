<?php
namespace Idealogica\ErrorHandler\Formatter;

use Idealogica\ErrorHandler\ErrorHandlerTrait;

/**
 * Trait HandlerTrait
 * @package Idealogica\ErrorHandler\Handler
 */
trait FormatterTrait
{
    use ErrorHandlerTrait;

    /**
     * @var string
     */
    protected $defaultErrorMessage;

    /**
     * @param string $defaultErrorMessage
     *
     * @return $this
     */
    public function setDefaultErrorMessage(string $defaultErrorMessage): self
    {
        $this->defaultErrorMessage = $defaultErrorMessage;
        return $this;
    }

    /**
     * @param \Throwable $e
     *
     * @return string
     */
    protected function extractMessage(\Throwable $e): string
    {
        return $this->publicExceptionClassName && !$e instanceof $this->publicExceptionClassName && !$this->debugMode ?
            ($this->defaultErrorMessage ?: 'A critical error occurred. Please contact support') :
            $e->getMessage();
    }
}
