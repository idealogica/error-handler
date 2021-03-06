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
     * @param string[] $publicExceptionClassNames
     * @param bool $debugMode
     */
    public function __construct(
        array $jsonStructure = [],
        string $defaultErrorMessage = null,
        array $publicExceptionClassNames = [],
        bool $debugMode = false
    ) {
        parent::__construct($defaultErrorMessage, $publicExceptionClassNames, $debugMode);
        $this->jsonStructure = $jsonStructure;
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
        if ($sendErrorOutput) {
            echo(json_encode($jsonData, JSON_PRETTY_PRINT));
            exit(255);
        } else {
            return json_encode($jsonData, JSON_PRETTY_PRINT);
        }
    }
}
