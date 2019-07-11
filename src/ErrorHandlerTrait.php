<?php
namespace Idealogica\ErrorHandler;

/**
 * Trait DefaultErrorLevelTrait
 * @package Idealogica\ErrorHandler
 */
trait ErrorHandlerTrait
{
    /**
     * @var string[]
     */
    protected $publicExceptionClassNames;

    /**
     * @var bool
     */
    protected $debugMode;

    /**
     * @param string[] $publicExceptionClassNames
     *
     * @return $this
     */
    public function setPublicExceptionClassNames(array $publicExceptionClassNames): self
    {
        $this->publicExceptionClassNames = $publicExceptionClassNames;
        return $this;
    }

    /**
     * @param bool $debugMode
     *
     * @return $this
     */
    public function setDebugMode(bool $debugMode): self
    {
        $this->debugMode = $debugMode;
        return $this;
    }

    /**
     * @return $this
     */
    public function setDefaultErrorLevel(): self
    {
        if (!isset($this->errorLimit)) {
            $this->setErrorLimit($this->debugMode ?
                E_ALL :
                E_ALL ^ E_NOTICE ^ E_USER_NOTICE ^ E_DEPRECATED ^ E_USER_DEPRECATED ^ E_STRICT
            );
        }
        return $this;
    }
}
