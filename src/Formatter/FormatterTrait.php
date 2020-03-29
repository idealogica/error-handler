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
     * @param string $template
     *
     * @return string
     */
    protected function extractMessage(\Throwable $e, string $template = ''): string
    {
        $classes = [get_class($e)];
        $classes = array_merge($classes, array_values((array)class_parents($e)));
        $message = $this->publicExceptionClassNames && !array_intersect($classes, $this->publicExceptionClassNames) && !$this->debugMode ?
            ($this->defaultErrorMessage ?: 'A critical error occurred. Please contact support') :
            $e->getMessage();
        return $template ? sprintf($template, $message) : $message;
    }
}
