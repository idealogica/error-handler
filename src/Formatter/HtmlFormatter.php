<?php
namespace Idealogica\ErrorHandler\Formatter;

use Idealogica\GoodView\ViewFactory;

/**
 * Class HtmlFormatter
 * @package Idealogica\ErrorHandler\Formatter
 */
class HtmlFormatter extends AbstractFormatter
{
    const VIEW_EXCEPTION = 'exception';

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
     * @param string|null $defaultErrorMessage
     * @param string[] $publicExceptionClassNames
     * @param bool $debugMode
     */
    public function __construct(
        ViewFactory $viewFactory,
        string $templateName = 'error',
        string $defaultErrorMessage = null,
        array $publicExceptionClassNames = [],
        bool $debugMode = false
    ) {
        parent::__construct($defaultErrorMessage, $publicExceptionClassNames, $debugMode);
        $this->viewFactory = $viewFactory;
        $this->templateName = $templateName;
    }

    /**
     * @param \Throwable $e
     * @param string $template
     * @param bool $sendErrorOutput
     *
     * @return \Psr\Http\Message\StreamInterface|string
     */
    public function format($e, string $template = '', bool $sendErrorOutput = true)
    {
        $message = $this->extractMessage($e, $template);
        $errorView = $this->viewFactory->create($this->templateName, [
            self::VIEW_EXCEPTION => $e,
            self::VIEW_MESSAGE => strip_tags($message, '<a><p>'),
            self::VIEW_MESSAGE_TYPE => $this->debugMode ? get_class($e) : '',
            self::VIEW_TRACE => $this->debugMode ? $e->getTraceAsString() : '',
        ]);
        // we don't need to show other errors in json mode so we stop here
        if ($sendErrorOutput) {
            echo($errorView());
            exit(255);
        }
        return $errorView();
    }
}
