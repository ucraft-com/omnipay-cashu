<?php

declare(strict_types = 1);

namespace Omnipay\Cashu\Enums;

use App\Traits\EnumToArray;

enum PurchaseErrorResponse
{
    use EnumToArray;

    case INSECURE_REQUEST;
    case SYSTEM_NOT_AVAILABLE;
    case INVALID_PARAMETER;
    case INACTIVE_MERCHANT;
    case TOKEN_CHECK_FAILURE;
    case GENERAL_SYSTEM_ERROR;
    case MISSING_REQUIRED_PARAMETER;

    /**
     * Get error message by code
     *
     * @param string $code
     *
     * @return string|null
     */
    public static function getErrorMessageByCode(string $code) : ?string
    {
        $self = self::getByName($code);

        if (null === $self) {
            return null;
        }

        return self::getErrorMessage($self);
    }

    /**
     * Get error message
     *
     * @param self $self
     *
     * @return string
     */
    private static function getErrorMessage(self $self) : string
    {
        return match ($self) {
            self::INSECURE_REQUEST => 'Request was not sent through HTTPS.',
            self::SYSTEM_NOT_AVAILABLE => 'CASHU servers are not responding.',
            self::INVALID_PARAMETER => 'One or more of the required parameters is null or has invalid character.',
            self::INACTIVE_MERCHANT => 'The Merchant Account is inactive.',
            self::TOKEN_CHECK_FAILURE => 'The submitted value for the token parameter is incorrect.',
            self::GENERAL_SYSTEM_ERROR => 'Error happened while processing the transaction.',
            self::MISSING_REQUIRED_PARAMETER => 'Required parameter is missing.',
        };
    }
}
