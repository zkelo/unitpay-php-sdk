<?php

namespace zkelo\Unitpay\Models;

use InvalidArgumentException;
use zkelo\Unitpay\Interfaces\RequestInterface;

/**
 * Request model
 *
 * @version 1.0.0
 */
class Request implements RequestInterface
{
    /**
     * Метод входящего запроса: CHECK
     *
     * Проверка возможности оказания услуг абоненту. Запрос выполняется до выполнения оплаты
     */
    const METHOD_CHECK = 'check';

    /**
     * Метод входящего запроса: PAY
     *
     * Уведомление об успешном платеже
     */
    const METHOD_PAY = 'pay';

    /**
     * Метод входящего запроса: PREAUTH
     *
     * Уведомление о платеже с преавторизацией, когда средства были успешно заблокированы
     */
    const METHOD_PREAUTH = 'preAuth';

    /**
     * Метод входящего запроса: ERROR
     *
     * Ошибка платежа на любой из этапов. Если ошибка вызвана пустым или ошибочным ответом сервера партнёра, то запрос не будет отправлен. Следует учесть, что данный статус не конечный и возможны ситуации, когда после запроса ERROR может последовать запрос PAY
     */
    const METHOD_ERROR = 'error';

    /**
     * Request method name
     *
     * @var string
     */
    protected $method = '';

    /**
     * Returns supported request methods
     *
     * @return array
     */
    public static function methods(): array
    {
        return [
            static::METHOD_CHECK,
            static::METHOD_PAY,
            static::METHOD_PREAUTH,
            static::METHOD_ERROR
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function __construct(string $method)
    {
        if (!in_array($method, static::methods())) {
            throw new InvalidArgumentException('This request method is not supported');
        }

        $this->method = $method;
    }

    /**
     * {@inheritDoc}
     */
    public function isWaiting(): bool
    {
        return $this->method === static::METHOD_CHECK;
    }

    /**
     * {@inheritDoc}
     */
    public function isSuccess(): bool
    {
        return $this->method === static::METHOD_PAY;
    }

    /**
     * {@inheritDoc}
     */
    public function isPreAuth(): bool
    {
        return $this->method === static::METHOD_PREAUTH;
    }

    /**
     * {@inheritDoc}
     */
    public function hasFailed(): bool
    {
        return $this->method === static::METHOD_ERROR;
    }
}