<?php
namespace invoice\payment\Entity\sdk;

class GET_REFUND
{
    /**
     * @var string
     * Refund ID
     */
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

}