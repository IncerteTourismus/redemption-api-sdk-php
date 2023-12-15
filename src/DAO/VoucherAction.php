<?php
/**
 * @created raphael.berger 14.12.2023
 */

namespace Incert\RedemptionApi\DAO;

abstract class VoucherAction
{
    /** @var array  */
    protected $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
