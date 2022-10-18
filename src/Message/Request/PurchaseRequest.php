<?php

declare(strict_types = 1);

namespace Omnipay\Cashu\Message\Request;

use Omnipay\Cashu\Enums\PurchaseErrorResponse;
use Omnipay\Cashu\Message\Response\PurchaseResponse;
use Omnipay\Common\Exception\InvalidRequestException;
use RuntimeException;
use SoapClient;
use SoapFault;

/**
 * @method PurchaseResponse send()
 */
class PurchaseRequest extends AbstractRequest
{
    /**
     * @inheritDoc
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('encryptionType', 'encryptionKeyword', 'merchantId', 'displayText', 'currency', 'amount', 'language', 'transactionId', 'txt1', 'testMode');

        $encryptionType = $this->getEncryptionType();

        if (!in_array($encryptionType, self::ENCRYPTION_TYPES, true)) {
            throw new InvalidRequestException('The encryptionType parameter is not valid');
        }

        $data = [];
        $data['merchantId'] = $this->getMerchantId();
        $data['token'] = $this->generateRequestToken($this->getAmount(), $this->getCurrency());
        $data['displayText'] = $this->getDisplayText();
        $data['currency'] = $this->getCurrency();
        $data['amount'] = $this->getAmount();
        $data['language'] = $this->getLanguage();
        $data['sessionId'] = $this->getTransactionId();
        $data['txt1'] = $this->getTxt1();
        $data['txt2'] = $this->getTxt2();
        $data['txt3'] = $this->getTxt3();
        $data['txt4'] = $this->getTxt4();
        $data['txt5'] = $this->getTxt5();
        $data['testMode'] = $this->getTestMode() ? '0' : '1';
        $data['serviceName'] = $this->getServiceName();

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function sendData($data) : PurchaseResponse
    {
        try {
            $client = new SoapClient($this->getWsdlUrl(), [
                'trace'      => 1,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'user_agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:20.0) Gecko/20100101 Firefox/20.0'
            ]);
        } catch (SoapFault $ex) {
            return $this->response = new PurchaseResponse($this, ['message' => $ex->getMessage(), 'code' => (string) $ex->getCode()]);
        }

        $response = (string) $client->DoPaymentRequest(
            $data['merchantId'],
            $data['token'],
            $data['displayText'],
            $data['currency'],
            $data['amount'],
            $data['language'],
            $data['sessionId'],
            $data['txt1'],
            $data['txt2'],
            $data['txt3'],
            $data['txt4'],
            $data['txt5'],
            $data['testMode'],
            $data['serviceName'],
        );

        try {
            $transactionCode = $this->getTransactionCodeFromResponse($response);
        } catch (RuntimeException $ex) {
            return $this->createResponse(['message' => $ex->getMessage(), 'code' => $response]);
        }

        return $this->createResponse(['Transaction_Code' => $transactionCode]);
    }

    /**
     * @return string
     */
    protected function getWsdlUrl() : string
    {
        if ($this->getTestMode()) {
            return 'https://sandbox.cashu.com/secure/payment.wsdl';
        }

        return 'https://secure.cashu.com/payment.wsdl';
    }

    /**
     * Validate response and get transaction code
     *
     * @param string $response
     *
     * @return string
     */
    protected function getTransactionCodeFromResponse(string $response) : string
    {
        if (!$response) {
            throw new RuntimeException('Invalid response. Response is empty.');
        }

        $errorMessage = PurchaseErrorResponse::getErrorMessageByCode($response);

        if (null !== $errorMessage) {
            throw new RuntimeException($errorMessage);
        }

        $transactionCodeWithPrefix = strstr($response, '=');

        // transaction code with '=' prefix
        if (!$transactionCodeWithPrefix) {
            throw new RuntimeException("Invalid response. Code: $response");
        }

        return substr($transactionCodeWithPrefix, 1);
    }

    /**
     * @param array $data
     *
     * @return \Omnipay\Cashu\Message\Response\PurchaseResponse
     */
    protected function createResponse(array $data) : PurchaseResponse
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}
