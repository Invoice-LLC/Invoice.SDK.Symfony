<?php


namespace invoice\payment\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractNotificationController extends AbstractController
{
    /**
     * @Route("/notify")
     * @return JsonResponse
     */
    public function notify() : JsonResponse {
        $postData = file_get_contents('php://input');
        $notification = json_decode($postData, true);
        $key = $this->getApiKey();

        if($notification == null) return $this->json(["ERROR"]);

        $type = $notification["notification_type"];
        $id = $notification["order"]["id"];

        if(!isset($notification['status'])) return $this->json(["ERROR"]);
        if($notification['signature'] != $this->getSignature($notification['id'], $notification["status"], $key))
            return $this->json(["WRONG SIGNATURE"]);

        if($type == "pay") {
            switch ($notification['status']) {
                case "successful":
                    $this->onPay($id, $notification['order']['amount']);
                    break;
                case "failed":
                    $this->onFail($id);
                    break;
            }
        }

        if($type == "refund") {
            $this->onRefund($id);
        }

        return $this->json(["OK"]);
    }

    abstract function getApiKey(): string;
    abstract function onPay($orderId, $amount);
    abstract function onRefund($orderId);
    abstract function onFail($orderId);

    private function getSignature($id, $status, $key) {
        return md5($id.$status.$key);
    }
}