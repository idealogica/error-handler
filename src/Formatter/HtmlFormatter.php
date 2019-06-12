<?php
namespace Idealogica\ErrorHandler\Formatter;

use Idealogica\GoodView\ViewFactory;

/**
 * Class HtmlFormatter
 * @package Idealogica\ErrorHandler\Formatter
 */
class HtmlFormatter extends AbstractFormatter
{
    const VIEW_MESSAGE = 'message';

    const VIEW_MESSAGE_TYPE = 'messageType';

    const VIEW_TRACE = 'trace';

    /**
     * @var ViewFactory
     */
    protected $viewFactory;

    /**
     * @var string
     */
    protected $templateName;

    /**
     * HtmlFormatter constructor.
     *
     * @param ViewFactory $viewFactory
     * @param string $templateName
     * @param bool $debugMode
     * @param string|null $publicExceptionClassName
     * @param string|null $defaultErrorMessage
     */
    public function __construct(
        ViewFactory $viewFactory,
        string $templateName = 'error',
        bool $debugMode = false,
        string $publicExceptionClassName = null,
        string $defaultErrorMessage = null
    ) {
        parent::__construct($debugMode, $publicExceptionClassName, $defaultErrorMessage);
        $this->viewFactory = $viewFactory;
        $this->templateName = $templateName;
    }

    /**
     * @param \Throwable $e
     *
     * @return \Psr\Http\Message\StreamInterface|string
     */
    public function format($e)
    {
        $message = $this->extractMessage($e);
        $errorView = $this->viewFactory->create($this->templateName, [
            self::VIEW_MESSAGE => strip_tags($message, '<a><p>'),
            self::VIEW_MESSAGE_TYPE => $this->debugMode ? get_class($e) : '',
            self::VIEW_TRACE => $this->debugMode ? $e->getTraceAsString() : ''
        ]);
        return $errorView();
    }
}
