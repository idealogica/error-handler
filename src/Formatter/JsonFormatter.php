<?php
namespace Idealogica\ErrorHandler\Formatter;

/**
 * Class JsonFormatter
 * @package Idealogica\ErrorHandler\Formatter
 */
class JsonFormatter extends AbstractFormatter
{
    const JSON_STATUS = 'status';

    const JSON_RESULT = 'result';

    const JSON_MESSAGE_TYPE = 'messageType';

    const JSON_TRACE = 'trace';

    /**
     * @var array
     */
    protected $jsonStructure = [];

    /**
     * JsonFormatter constructor.
     *
     * @param array $jsonStructure
     * @param string|null $defaultErrorMessage
     * @param string|null $publicExceptionClassName
     * @param bool $debugMode
     */
    public function __construct(
        array $jsonStructure = [],
        string $defaultErrorMessage = null,
        string $publicExceptionClassName = null,
        bool $debugMode = false
    ) {
        parent::__construct($defaultErrorMessage, $publicExceptionClassName, $debugMode);
        $this->jsonStructure = $jsonStructure;
    }

    /**
     * @param \Throwable $e
     *
     * @return \Psr\Http\Message\StreamInterface|string
     */
    public function format($e)
    {
        $message = $this->extractMessage($e);
        $statusParamName = $this->jsonStructure[self::JSON_STATUS] ?? 'status';
        $resultParamName = $this->jsonStructure[self::JSON_RESULT] ?? 'result';
        $messageTypeParamName = $this->jsonStructure[self::JSON_MESSAGE_TYPE] ?? 'messageType';
        $traceParamName = $this->jsonStructure[self::JSON_TRACE] ?? 'trace';
        $jsonData = [$statusParamName => 'error', $resultParamName => $message];
        if ($this->debugMode) {
            $jsonData[$messageTypeParamName] = get_class($e);
            $jsonData[$traceParamName] = $e->getTraceAsString();
        }
        header('Access-Control-Allow-Origin: *', true);
        // we don't need to show other errors in html mode so we stop here
        echo(json_encode($jsonData, JSON_PRETTY_PRINT));
        exit(255);
    }
}
