<?php
namespace Idealogica\ErrorHandler\Handler;

use Idealogica\ErrorHandler\ErrorHandlerTrait;

/**
 * Trait HandlerTrait
 * @package Idealogica\ErrorHandler\Handler
 */
trait HandlerTrait
{
    use ErrorHandlerTrait;

    /**
     * @var int|null
     */
    protected $errorLimit;

    /**
     * @param int $limit
     */
    public function setErrorLimit(int $limit = E_ALL): void
    {
        $this->errorLimit = $limit;
    }

    /**
     * @return int|null
     */
    public function getErrorLimit(): int
    {
        return $this->errorLimit;
    }
}
