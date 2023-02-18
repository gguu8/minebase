<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Mine\Framework\Exception;


use Hyperf\Server\Exception\ServerException;
use Mine\Framework\Constants\ErrorCode;

class CommonException extends ServerException
{
    public function __construct(int $code = 0, string $message = null, \Throwable $previous = null)
    {
        if (is_null($message)) {
            $message = ErrorCode::getMessage($code);
            if (! $message && class_exists(\App\Constants\AppErrCode::class)) {
                $message = \App\Constants\AppErrCode::getMessage($code);
            }
        }
        parent::__construct($message, $code, $previous);
    }
}
