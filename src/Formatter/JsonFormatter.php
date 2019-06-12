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
     * @param bool $debugMode
     * @param string|null $publicExceptionClassName
     * @param string|null $defaultErrorMessage
     * @param array $jsonStructure
     */
    public function __construct(
        array $jsonStructure = [],
        bool $debugMode = false,
        string $publicExceptionClassName = null,
        string $defaultErrorMessage = null
    ) {
        parent::__construct($debugMode, $publicExceptionClassName, $defaultErrorMessage);
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
        return json_encode($jsonData, JSON_PRETTY_PRINT);
    }
}