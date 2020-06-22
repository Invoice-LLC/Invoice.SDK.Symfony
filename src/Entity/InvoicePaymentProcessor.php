<?php


namespace invoice\payment\Entity;


use invoice\payment\Entity\sdk\common\ORDER;
use invoice\payment\Entity\sdk\common\REFUND_INFO;
use invoice\payment\Entity\sdk\common\SETTINGS;
use invoice\payment\Entity\sdk\CREATE_PAYMENT;
use invoice\payment\Entity\sdk\CREATE_REFUND;
use invoice\payment\Entity\sdk\CREATE_TERMINAL;
use invoice\payment\Entity\sdk\GET_PAYMENT_BY_ORDER;
use invoice\payment\Entity\sdk\RestClient;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InvoicePaymentProcessor
{
    private $restClient;

    public function __construct($api_key, $login)
    {
        $this->restClient = new RestClient($login, $api_key);
    }

    /**
     * @param string $orderId
     * @param $amount
     * @param array $receipt
     * @param string $fail_url
     * @param string $success_url
     * @return sdk\PaymentInfo
     * @throws \ErrorException
     */
    public function createPayment(string $orderId, $amount, array $receipt, $fail_url = "", $success_url = "") {
        $tid = $this->getTerminal();
        if($tid == null or empty($tid)) {
            $tid = $this->createTerminal("Symfony Terminal");
        }
        $order = new ORDER($amount);
        $order->id = $orderId;

        $settings = new SETTINGS($tid);
        $settings->fail_url = $fail_url;
        $settings->success_url = $success_url;

        $request = new CREATE_PAYMENT($order, $settings, $receipt);

        $response = $this->restClient->CreatePayment($request);
        if($response == null) throw new \ErrorException("Payment not created!");
        if(isset ($response->error) and $response->error != null) throw new \ErrorException($response->description, $response->error);

        return $response;
    }

    /**
     * @param string $name
     * @param string $description
     * @return sdk\TerminalInfo
     * @throws \ErrorException
     */
    public function createTerminal(string $name, string $description = "") {
        $request = new CREATE_TERMINAL($name);
        $request->type = "dynamical";
        $request->description = $description;

        $response = $this->restClient->CreateTerminal($request);
        if($response == null) throw new \ErrorException("Terminal not created!");
        if(isset ($response->error) and $response->error != null) throw new \ErrorException("Terminal Error", $response->error);

        $this->saveTerminal($response->id);

        return $response;
    }

    /**
     * @param string $orderId
     * @param $amount
     * @param string $refundReason
     * @return sdk\RefundInfo
     * @throws \ErrorException
     */
    public function createRefund(string $orderId, $amount, $refundReason = "") {
        $payment = $this->getPayment($orderId);

        $refund = new REFUND_INFO($amount, $refundReason);
        $request = new CREATE_REFUND($payment->id, $refund);

        $response = $this->restClient->CreateRefund($request);

        if($response == null) throw new \ErrorException("Refund not created!");
        if(isset ($response->error) and $response->error != null) throw new \ErrorException($response->description, $response->error);

        return $response;
    }

    /**
     * @param string $orderId
     * @return sdk\PaymentInfo
     * @throws \ErrorException
     */
    public function getPayment(string $orderId) {
        $request = new GET_PAYMENT_BY_ORDER($orderId);
        $response = $this->restClient->GetPaymentByOrder($request);

        if($response == null) throw new \ErrorException("Payment not created!");
        if(isset ($response->error) and $response->error != null) throw new \ErrorException($response->description, $response->error);

        return $response;
    }

    private function saveTerminal($id) {

        file_put_contents("invoice_tid", $id);
    }

    private function getTerminal() {
        if(!file_exists("invoice_tid")) return "";
        return file_get_contents("invoice_tid");
    }
}