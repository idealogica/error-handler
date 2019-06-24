<?php
namespace Idealogica\ErrorHandler\Handler;

use Idealogica\LogX;

/**
 * Class HtmlFormatter
 * @package Idealogica\ErrorHandler\Formatter
 */
class LogHandler extends \League\BooBoo\Handler\LogHandler
{
    use HandlerTrait;

    /**
     * LogHandler constructor.
     *
     * @param string $logFilePath
     * @param string|null $publicExceptionClassName
     * @param bool $debugMode
     *
     * @throws \Exception
     */
    public function __construct(
        string $logFilePath,
        string $publicExceptionClassName = null,
        bool $debugMode = false
    ) {
        parent::__construct(new LogX($logFilePath, false));
        $this->publicExceptionClassName = $publicExceptionClassName;
        $this->debugMode = $debugMode;
    }

    /**
     * @param \Throwable $e
     */
    public function handle($e)
    {
        if ($this->publicExceptionClassName && $e instanceof $this->publicExceptionClassName) {
            return;
        }
        parent::handle($e);
    }

    /**
     * @param \ErrorException $e
     *
     * @return bool
     */
    protected function handleErrorException(\ErrorException $e)
    {
        if ($this->getErrorLimit() & $e->getSeverity()) {
            return parent::handleErrorException($e);
        }
        return true;
    }
}
