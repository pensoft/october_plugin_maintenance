<?php

namespace Pensoft\Maintenance\Exceptions;

use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MaintenanceModeException extends HttpException
{
    public Carbon $wentDownAt;
    public ?int $retryAfter;
    public ?Carbon $willBeAvailableAt;

    public function __construct(int $time, ?int $retryAfter = null, ?string $message = null, ?\Throwable $previous = null, int $code = 0)
    {
        $this->wentDownAt = Carbon::createFromTimestamp($time);
        $this->willBeAvailableAt = null;

        $headers = [];
        if ($retryAfter) {
            $this->retryAfter = $retryAfter;
            $headers = ['Retry-After' => $this->retryAfter];
            $this->willBeAvailableAt = Carbon::createFromTimestamp($time)->addSeconds($this->retryAfter);
        }

        parent::__construct(503, $message, $previous, $headers, $code);
    }
}