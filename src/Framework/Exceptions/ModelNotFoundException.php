<?php

namespace Shiblati\Framework\Exceptions;

use RuntimeException;

class ModelNotFoundException extends RuntimeException
{
    protected mixed $model;

    const MESSAGE_FORMAT = 'Model not found: %s';

    const FAILURE_MESSAGE_FORMAT = 'Failed with message: "%s"';

    public static function createFromModel(mixed $model, $previous = null): ModelNotFoundException
    {
        $error = (null !== $previous)
            ? $previous->getMessage()
            : null;

        $code  = (null !== $previous)
            ? $previous->getCode()
            : null;

        $message = sprintf(static::MESSAGE_FORMAT, $model);
        $message .= ' ' . sprintf(static::FAILURE_MESSAGE_FORMAT, $error);

        $exception = new static($message, $code, $previous);
        $exception->setModel($model);

        return $exception;
    }


    protected function setModel(mixed $model): static
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getModel(): mixed
    {
        return $this->model;
    }
}