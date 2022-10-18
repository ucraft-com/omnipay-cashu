<?php

declare(strict_types = 1);

namespace Omnipay\Cashu;

use Omnipay\Cashu\Message\Request\CompletePurchaseRequest;
use Omnipay\Cashu\Message\Request\PurchaseRequest;
use Omnipay\Cashu\Traits\AuthParamsTrait;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * @method NotificationInterface acceptNotification(array $options = array())
 * @method RequestInterface authorize(array $options = array())
 * @method RequestInterface completeAuthorize(array $options = array())
 * @method RequestInterface capture(array $options = array())
 * @method RequestInterface refund(array $options = array())
 * @method RequestInterface fetchTransaction(array $options = [])
 * @method RequestInterface void(array $options = array())
 * @method RequestInterface createCard(array $options = array())
 * @method RequestInterface updateCard(array $options = array())
 * @method RequestInterface deleteCard(array $options = array())
 */
class Gateway extends AbstractGateway
{
    use AuthParamsTrait;

    public const NAME = 'Cashu';

    /**
     * @inheritDoc
     */
    public function getName() : string
    {
        return self::NAME;
    }

    /**
     * @inheritDoc
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function purchase(array $options = []) : PurchaseRequest
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    /**
     * @inheritDoc
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function completePurchase(array $options = []) : CompletePurchaseRequest
    {
        return $this->createRequest(CompletePurchaseRequest::class, $options);
    }
}
