<?php
namespace invoice\payment\Entity\sdk;

class GET_PAYMENT
{
    /**
     * @var string
     * Payment ID
     */
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
}