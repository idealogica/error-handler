<?php
namespace Idealogica\ErrorHandler;

use Idealogica\ErrorHandler\Formatter\AbstractFormatter;
use Idealogica\ErrorHandler\Formatter\FormatterTrait;
use Idealogica\ErrorHandler\Handler\AbstractHandler;
use League\BooBoo\BooBoo;
use League\BooBoo\Exception\NoFormattersRegisteredException;
use League\BooBoo\Formatter\FormatterInterface;
use League\BooBoo\Formatter\NullFormatter;
use League\BooBoo\Handler\HandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Idealogica\ErrorHandler\Handler\HandlerTrait;

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
     * @var HandlerInterface[]
     */
    protected $handlers = [];

    /**
     * @var string|null
     */
    protected $errorLogFileName;

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
     * @param array $handlers
     * @param string|null $errorLogFileName
     * @param bool|null $debugMode
     * @param string|null $publicExceptionClassName
     * @param string|null $defaultErrorMessage
     */
    public function __construct(
        ServerRequestInterface $serverRequest,
        array $sapiFormatterCollections = [],
        array $cliFormatters = [],
        array $handlers = [],
        string $errorLogFileName = null,
        bool $debugMode = null,
        string $publicExceptionClassName = null,
        string $defaultErrorMessage = null
    ) {
        $this->serverRequest = $serverRequest;
        $this->sapiFormatterCollections = $sapiFormatterCollections ?: [[new NullFormatter()]];
        $this->cliFormatters = $cliFormatters ?: [new NullFormatter()];
        $this->handlers = $handlers;
        $this->errorLogFileName = $errorLogFileName;
        // set default values for sapi formatters
        foreach ($this->sapiFormatterCollections as $sapiFormatterCollection) {
            foreach ($sapiFormatterCollection as $sapiFormatter) {
                if ($sapiFormatter instanceof AbstractFormatter ||
                    in_array(FormatterTrait::class, class_uses($sapiFormatter))
                ) {
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
            if ($cliFormatter instanceof AbstractFormatter ||
                in_array(FormatterTrait::class, class_uses($cliFormatter))
            ) {
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
        // set default values for handlers
        foreach ($this->handlers as $handler) {
            if ($handler instanceof AbstractHandler ||
                in_array(HandlerTrait::class, class_uses($handler))
            ) {
                if (isset($debugMode)) {
                    $handler->setDebugMode($debugMode);
                }
                if (isset($publicExceptionClassName)) {
                    $handler->setPublicExceptionClassName($publicExceptionClassName);
                }
                $handler->setDefaultErrorLevel();
            }
        }
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function register(): self
    {
        if (isset($this->sapiMode) ? !$this->sapiMode : (php_sapi_name() === "cli")) {
            $this->booboo = new BooBoo($this->cliFormatters, $this->handlers);
        } else {
            $requestUriPath = $this->serverRequest->getUri()->getPath();
            foreach ($this->sapiFormatterCollections as $regexp => $formatters) {
                if ($regexp && is_string($regexp) && preg_match('#' . $regexp . '#i', $requestUriPath)) {
                    $this->booboo = new BooBoo($formatters, $this->handlers);
                    break;
                }
            }
            if (!$this->booboo) {
                $this->booboo = new BooBoo(
                    array_shift($this->sapiFormatterCollections),
                    $this->handlers
                );
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
