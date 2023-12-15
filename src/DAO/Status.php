<?php
/**
 * @created raphael.berger 14.12.2023
 */

namespace Incert\RedemptionApi\DAO;

class Status extends VoucherAction
{
    const STATUS_VOUCHER_VALID = 1;

    /**
     * @return bool
     */
    public function canRedeem(): bool
    {
        return $this->data["status"] === self::STATUS_VOUCHER_VALID;
    }
}
