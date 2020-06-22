<?php
namespace invoice\payment\Entity\sdk;

class GET_PAYMENT_BY_ORDER
{
    /**
     * @var string
     * Order ID
     */
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }


}