<?php
namespace Idealogica\ErrorHandler\Formatter;

/**
 * Class AbstractFormatter
 * @package Idealogica\ErrorHandler\Formatter
 */
abstract class AbstractFormatter extends \League\BooBoo\Formatter\AbstractFormatter
{
    /**
     * @var null|int
     */
    protected $errorLimit = null;

    /**
     * @var string
     */
    protected $defaultErrorMessage;

    /**
     * @var string
     */
    protected $publicExceptionClassName;

    /**
     * @var bool
     */
    protected $debugMode = false;

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
        $this->debugMode = $debugMode;
        $this->publicExceptionClassName = $publicExceptionClassName;
        $this->defaultErrorMessage = $defaultErrorMessage;
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
        if (!isset($this->errorLimit)) {
            $this->setErrorLimit($this->debugMode ?
                E_ALL :
                E_ALL ^ E_NOTICE ^ E_USER_NOTICE ^ E_DEPRECATED ^ E_USER_DEPRECATED ^ E_STRICT
            );
        }
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
