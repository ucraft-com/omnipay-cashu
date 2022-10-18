<?php

declare(strict_types = 1);

namespace Omnipay\Cashu\Traits;

trait AuthParamsTrait
{
    /**
     * @return string
     */
    public function getEncryptionType() : string
    {
        return (string) $this->getParameter('encryptionType');
    }

    /**
     * @param string $value
     *
     * @return static
     */
    public function setEncryptionType(string $value) : static
    {
        return $this->setParameter('encryptionType', $value);
    }

    /**
     * @return string
     */
    public function getEncryptionKeyword() : string
    {
        return (string) $this->getParameter('encryptionKeyword');
    }

    /**
     * @param string $value
     *
     * @return static
     */
    public function setEncryptionKeyword(string $value) : static
    {
        return $this->setParameter('encryptionKeyword', $value);
    }

    /**
     * @return string
     */
    public function getMerchantId() : string
    {
        return (string) $this->getParameter('merchantId');
    }

    /**
     * @param string $value
     *
     * @return static
     */
    public function setMerchantId(string $value) : static
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * @return string
     */
    public function getDisplayText() : string
    {
        return (string) $this->getParameter('displayText');
    }

    /**
     * @param string $value
     *
     * @return static
     */
    public function setDisplayText(string $value) : static
    {
        return $this->setParameter('displayText', $value);
    }
}
