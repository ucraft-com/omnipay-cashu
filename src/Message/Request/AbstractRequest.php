<?php

declare(strict_types = 1);

namespace Omnipay\Cashu\Message\Request;

use Omnipay\Cashu\Traits\AuthParamsTrait;
use Omnipay\Cashu\Traits\ParamsTrait;
use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;

/**
 * Base class of all Request classes
 */
abstract class AbstractRequest extends BaseAbstractRequest
{
    use AuthParamsTrait;
    use ParamsTrait;

    protected const ENCRYPTION_TYPE_DEFAULT = 'default';
    protected const ENCRYPTION_TYPE_FULL = 'full';
    protected const ENCRYPTION_TYPES = [self::ENCRYPTION_TYPE_DEFAULT, self::ENCRYPTION_TYPE_FULL];

    /**
     * Generate request token
     *
     * @param string $amount
     * @param string $currency
     *
     * @return string
     */
    protected function generateRequestToken(string $amount, string $currency) : string
    {
        if ($this->getEncryptionType() === self::ENCRYPTION_TYPE_DEFAULT) {
            $token = md5(strtolower($this->getMerchantId()).':'.$amount.':'.$currency.':'.$this->getEncryptionKeyword());
        } else {
            $token = md5(strtolower($this->getMerchantId()).':'.$amount.':'.$currency.':'.$this->getTransactionId().':'.$this->getEncryptionKeyword());
        }

        return $token;
    }
}
