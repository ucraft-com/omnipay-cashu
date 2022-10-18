<?php

declare(strict_types = 1);

namespace Omnipay\Cashu\Message\Response;

class CompletePurchaseResponse extends AbstractResponse
{
    /**
     * @inheritDoc
     */
    public function isSuccessful() : bool
    {
        return !empty($this->data['success']);
    }
}
