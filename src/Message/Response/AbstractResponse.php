<?php

declare(strict_types = 1);

namespace Omnipay\Cashu\Message\Response;

use Omnipay\Common\Message\AbstractResponse as BaseAbstractResponse;

/**
 * Base class of all Response classes
 */
abstract class AbstractResponse extends BaseAbstractResponse
{
    /**
     * @inheritDoc
     */
    public function getMessage() : ?string
    {
        return $this->data['message'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getCode() : ?string
    {
        return $this->data['code'] ?? null;
    }
}
