<?php
namespace Idealogica\ErrorHandler\Formatter;

/**
 * Class HtmlFormatter
 * @package Idealogica\ErrorHandler\Formatter
 */
abstract class AbstractFormatter extends \League\BooBoo\Formatter\AbstractFormatter
{
    /**
     * @var bool
     */
    protected $debugMode = false;

    /**
     * @var string
     */
    protected $publicExceptionClassName;

    /**
     * @var string
     */
    protected $defaultErrorMessage;

    /**
     * HtmlFormatter constructor.
     *
     * @param bool $debugMode
     * @param string|null $publicExceptionClassName
     * @param string|null $defaultErrorMessage
     */
    public function __construct(
        bool $debugMode = false,
        string $publicExceptionClassName = null,
        string $defaultErrorMessage = null
    ) {
        $this->debugMode = $debugMode;
        $this->publicExceptionClassName = $publicExceptionClassName;
        $this->defaultErrorMessage = $defaultErrorMessage;
        $this->setDefaultErrorLevel();
    }

    /**
     * @param bool $debugMode
     *
     * @return AbstractFormatter
     */
    public function setDebugMode(bool $debugMode): AbstractFormatter
    {
        $this->debugMode = $debugMode;
        return $this;
    }

    /**
     * @param string $defaultErrorMessage
     *
     * @return AbstractFormatter
     */
    public function setDefaultErrorMessage(string $defaultErrorMessage): AbstractFormatter
    {
        $this->defaultErrorMessage = $defaultErrorMessage;
        return $this;
    }

    /**
     * @param string $publicExceptionClassName
     *
     * @return AbstractFormatter
     */
    public function setPublicExceptionClassName(string $publicExceptionClassName): AbstractFormatter
    {
        $this->publicExceptionClassName = $publicExceptionClassName;
        return $this;
    }

    /**
     * @return $this
     */
    public function setDefaultErrorLevel()
    {
        $this->setErrorLimit($this->debugMode ?
            E_ALL :
            E_ALL ^ E_NOTICE ^ E_USER_NOTICE ^ E_DEPRECATED ^ E_USER_DEPRECATED ^ E_STRICT
        );
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
