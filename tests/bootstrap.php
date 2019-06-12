<?php
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Uri;
use Idealogica\ErrorHandler\ErrorHandler;
use Idealogica\ErrorHandler\Formatter\HtmlFormatter;
use Idealogica\ErrorHandler\Formatter\JsonFormatter;
use Idealogica\GoodView\ViewFactory;
use League\BooBoo\Formatter\CommandLineFormatter;

const DISPLAY_FORMATTER_OUTPUT = false;

chdir(__DIR__);
include('../vendor/autoload.php');

/**
 * @var ErrorHandler $handler
 * @var string $testName
 * @var \Closure $checkFunction
 */
$handler = null;
$testName = '';
$checkFunction = function ($contents) {};

register_shutdown_function(function ()  use (&$handler, &$testName, &$checkFunction) {
    $contents = ob_get_contents();
    ob_end_clean();
    $res = $checkFunction($contents);
    if (DISPLAY_FORMATTER_OUTPUT) {
        echo($contents);
    }
    echo('----------------------------------------------------------' . PHP_EOL);
    echo(($res ? $testName . ' test is OK' : $testName . ' test is failed') . PHP_EOL . PHP_EOL);
});
ob_start();

/**
 * @param bool $sapiMode
 * @param bool $debugMode
 * @param bool $apiMode
 * @param string $newTestName
 * @param callable $newCheckFunction
 * @param callable $errorFunction
 *
 * @throws \League\BooBoo\Exception\NoFormattersRegisteredException
 */
function testErrorHandler(
    bool $sapiMode,
    bool $debugMode,
    bool $apiMode,
    string $newTestName,
    callable $newCheckFunction,
    callable $errorFunction
) {
    global $handler;
    global $testName;
    global $checkFunction;
    $handler = new ErrorHandler(
        new ServerRequest('GET', new Uri($apiMode ? 'https://www.server.test/api/endpoint' : 'https://www.server.test/page')),
        [
            '/api/.*' => [new JsonFormatter()],
            '.*' => [new HtmlFormatter(ViewFactory::createStringViewFactory())]
        ],
        [
            new CommandLineFormatter()
        ],
        $debugMode,
        InvalidArgumentException::class
    );
    $handler->setSapiMode($sapiMode)->register();
    $testName = $newTestName;
    $checkFunction = $newCheckFunction;
    $errorFunction();
}
