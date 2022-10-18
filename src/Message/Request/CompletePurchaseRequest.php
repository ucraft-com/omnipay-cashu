<?php

declare(strict_types = 1);

namespace Omnipay\Cashu\Message\Request;

use Omnipay\Cashu\Message\Response\CompletePurchaseResponse;
use Omnipay\Common\Exception\InvalidRequestException;

/**
 * @method CompletePurchaseResponse send()
 */
class CompletePurchaseRequest extends AbstractRequest
{
    /**
     * @inheritDoc
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('encryptionType', 'encryptionKeyword', 'merchantId', 'currency', 'amount', 'originalCurrency', 'originalAmount', 'language', 'transactionId', 'txt1', 'testMode', 'token', 'transactionReference', 'verificationString');

        $encryptionType = $this->getEncryptionType();

        if (!in_array($encryptionType, self::ENCRYPTION_TYPES, true)) {
            throw new InvalidRequestException('The encryptionType parameter is not valid.');
        }

        return [];
    }

    /**
     * @inheritDoc
     */
    public function sendData($data) : CompletePurchaseResponse
    {
        try {
            $this->validateData();
        } catch (InvalidRequestException $ex) {
            return $this->createResponse(['message' => $ex->getMessage()]);
        }

        return $this->createResponse(['success' => true]);
    }

    /**
     * @return string
     */
    public function getToken() : string
    {
        return (string) $this->getParameter('token');
    }

    /**
     * @param string $value
     *
     * @return static
     */
    public function setToken($value) : static
    {
        return $this->setParameter('token', (string) $value);
    }

    /**
     * @param string $value
     *
     * @return static
     */
    public function setTrnId(string $value) : static
    {
        return $this->setTransactionReference($value);
    }

    /**
     * @return string
     */
    public function getVerificationString() : string
    {
        return (string) $this->getParameter('verificationString');
    }

    /**
     * @param string $value
     *
     * @return static
     */
    public function setVerificationString(string $value) : static
    {
        return $this->setParameter('verificationString', $value);
    }

    /**
     * @param string $value
     *
     * @return static
     */
    public function setSessionId(string $value) : static
    {
        return $this->setTransactionId($value);
    }

    /**
     * @param string $value
     *
     * @return static
     */
    public function setServicesName(string $value) : static
    {
        return $this->setServiceName($value);
    }

    /**
     * @return string
     */
    public function getTrnDate() : string
    {
        return (string) $this->getParameter('trnDate');
    }

    /**
     * @param string $value
     *
     * @return static
     */
    public function setTrnDate(string $value) : static
    {
        return $this->setParameter('trnDate', $value);
    }

    /**
     * @return string
     */
    public function getNetAmount() : string
    {
        return (string) $this->getParameter('netAmount');
    }

    /**
     * @param string $value
     *
     * @return static
     */
    public function setNetAmount(string $value) : static
    {
        return $this->setParameter('netAmount', $value);
    }

    /**
     * @return string
     */
    public function getOriginalCurrency() : string
    {
        return (string) $this->getParameter('originalCurrency');
    }

    /**
     * @param string $value
     *
     * @return static
     */
    public function setOriginalCurrency(string $value) : static
    {
        return $this->setParameter('originalCurrency', strtolower($value));
    }

    /**
     * @return string
     */
    public function getOriginalAmount() : string
    {
        return (string) $this->getParameter('originalAmount');
    }

    /**
     * @param float|string $value
     *
     * @return static
     */
    public function setOriginalAmount(float|string $value) : static
    {
        return $this->setParameter('originalAmount', (string) $value);
    }

    /**
     * Validate data from off-site gateways after purchase
     *
     * @return void
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    protected function validateData() : void
    {
        $calculatedVerificationString = sha1(strtolower($this->getMerchantId()).':'.$this->getTransactionReference().':'.$this->getEncryptionKeyword());

        if ($calculatedVerificationString !== $this->getVerificationString()) {
            throw new InvalidRequestException('Invalid verification string.');
        }

        $token = $this->generateRequestToken($this->getOriginalAmount(), $this->getOriginalCurrency());

        if ($token !== $this->getToken()) {
            throw new InvalidRequestException('Invalid token.');
        }
    }

    /**
     * @param array $data
     *
     * @return \Omnipay\Cashu\Message\Response\CompletePurchaseResponse
     */
    protected function createResponse(array $data) : CompletePurchaseResponse
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
