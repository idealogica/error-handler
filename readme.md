# ErrorHandler - Error handler based on Booboo with HTML and JSON support

## 1. Features

- allows to setup formatters based on the request uri
- automatically detects cli mode and uses appropriate formatter
- uses templates for customizable error pages
- can detect exceptions which are not allowed to be shown and show general "server error" message instead of exact exception information

## 2. Installation

```
composer require idealogica/error-handler:~1.0.0
```

## 3. Basic example

```
$handler = new ErrorHandler(
    new ServerRequest('GET', new Uri('https://www.server.test/api/endpoint')),
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
$handler->register();
```

## 4. License

ErrorHandler is licensed under a [MIT License](https://opensource.org/licenses/MIT).
