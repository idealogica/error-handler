<?php
namespace Idealogica\ErrorHandler;

use Idealogica\ErrorHandler\Formatter\AbstractFormatter;
use League\BooBoo\BooBoo;
use League\BooBoo\Exception\NoFormattersRegisteredException;
use League\BooBoo\Formatter\FormatterInterface;
use League\BooBoo\Formatter\NullFormatter;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ErrorHandler
 * @package Idealogica\ErrorHandler
 */
class ErrorHandler
{
    /**
     * @var ServerRequestInterface
     */
    protected $serverRequest;

    /**
     * @var array[]
     */
    protected $sapiFormatterCollections = [];

    /**
     * @var FormatterInterface[]
     */
    protected $cliFormatters = [];

    /**
     * @var bool
     */
    protected $sapiMode;

    /**
     * @var BooBoo
     */
    protected $booboo;

    /**
     * ErrorHandler constructor.
     *
     * @param ServerRequestInterface $serverRequest
     * @param array $sapiFormatterCollections
     * @param array $cliFormatters
     * @param bool|null $debugMode
     * @param string|null $publicExceptionClassName
     * @param string|null $defaultErrorMessage
     */
    public function __construct(
        ServerRequestInterface $serverRequest,
        array $sapiFormatterCollections = [],
        array $cliFormatters = [],
        bool $debugMode = null,
        string $publicExceptionClassName = null,
        string $defaultErrorMessage = null
    ) {
        $this->serverRequest = $serverRequest;
        $this->sapiFormatterCollections = $sapiFormatterCollections ?: [[new NullFormatter()]];
        $this->cliFormatters = $cliFormatters ?: [new NullFormatter()];
        // set default values for sapi formatters
        foreach ($this->sapiFormatterCollections as $sapiFormatterCollection) {
            foreach ($sapiFormatterCollection as $sapiFormatter) {
                if ($sapiFormatter instanceof AbstractFormatter) {
                    if (isset($debugMode)) {
                        $sapiFormatter->setDebugMode($debugMode);
                    }
                    if (isset($publicExceptionClassName)) {
                        $sapiFormatter->setPublicExceptionClassName($publicExceptionClassName);
                    }
                    if (isset($defaultErrorMessage)) {
                        $sapiFormatter->setDefaultErrorMessage($defaultErrorMessage);
                    }
                    $sapiFormatter->setDefaultErrorLevel();
                }
            }
        }
        // set default values for cli formatters
        foreach ($this->cliFormatters as $cliFormatter) {
            if ($cliFormatter instanceof AbstractFormatter) {
                if (isset($debugMode)) {
                    $cliFormatter->setDebugMode($debugMode);
                }
                if (isset($publicExceptionClassName)) {
                    $cliFormatter->setPublicExceptionClassName($publicExceptionClassName);
                }
                if (isset($defaultErrorMessage)) {
                    $cliFormatter->setDefaultErrorMessage($defaultErrorMessage);
                }
                $cliFormatter->setDefaultErrorLevel();
            }
        }
    }

    /**
     * @return $this
     */
    public function register(): self
    {
        if (isset($this->sapiMode) ? !$this->sapiMode : (php_sapi_name() === "cli")) {
            $this->booboo = new BooBoo($this->cliFormatters);
        } else {
            $requestUriPath = $this->serverRequest->getUri()->getPath();
            foreach ($this->sapiFormatterCollections as $regexp => $formatters) {
                if ($regexp && is_string($regexp) && preg_match('#' . $regexp . '#i', $requestUriPath)) {
                    $this->booboo = new BooBoo($formatters);
                    break;
                }
            }
            if (!$this->booboo) {
                $this->booboo = new BooBoo(array_shift($this->sapiFormatterCollections));
            }
        }
        $this->booboo->silenceAllErrors(false);
        try {
            $this->booboo->register();
        } catch (NoFormattersRegisteredException $e) {}
        return $this;
    }

    /**
     * @param bool $sapiMode
     *
     * @return $this
     */
    public function setSapiMode(bool $sapiMode = true): self
    {
        $this->sapiMode = $sapiMode;
        return $this;
    }

    /**
     * @return $this
     */
    public function unregister(): self
    {
        if ($this->booboo) {
            $this->booboo->deregister();
        }
        return $this;
    }
}
