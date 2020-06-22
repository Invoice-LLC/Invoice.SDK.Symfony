<?php
namespace invoice\payment\Entity\sdk;

use invoice\payment\Entity\sdk\common\REFUND_INFO;

class CREATE_REFUND
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var REFUND_INFO
     */
    public $refund;
    /**
     * @var array(ITEM)
     */
    public $receipt;

    /**
     * CREATE_REFUND constructor.
     * @param $id string
     * @param $refund REFUND_INFO
     */
    public function __construct($id, $refund)
    {
        $this->id = $id;
        $this->refund = $refund;
    }

}