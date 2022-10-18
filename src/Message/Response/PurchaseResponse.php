<?php

declare(strict_types = 1);

namespace Omnipay\Cashu\Message\Response;

use Omnipay\Cashu\Message\Request\PurchaseRequest;

/**
 * @method PurchaseRequest getRequest()
 */
class PurchaseResponse extends AbstractResponse
{
    /**
     * When you do a `purchase` the request is never successful because
     * you need to redirect off-site to complete the purchase.
     *
     * {@inheritdoc}
     */
    public function isSuccessful() : bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getRedirectUrl() : string
    {
        if (!$this->isRedirect()) {
            return '';
        }

        if ($this->getRequest()->getTestMode()) {
            return 'https://sandbox.cashu.com/cgi-bin/payment/pcashu.cgi';
        }

        return 'https://www.cashu.com/cgi-bin/payment/pcashu.cgi';
    }

    /**
     * @inheritDoc
     */
    public function getRedirectMethod() : string
    {
        return 'POST';
    }

    /**
     * @inheritDoc
     */
    public function getRedirectData() : array
    {
        if ($this->isRedirect()) {
            return $this->getData();
        }

        return [];
    }

    /**
     * @inheritDoc
     */
    public function isRedirect() : bool
    {
        return !empty($this->data['Transaction_Code']);
    }
}
