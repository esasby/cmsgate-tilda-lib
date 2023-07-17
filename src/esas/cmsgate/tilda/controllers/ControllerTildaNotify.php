<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 22.03.2018
 * Time: 14:13
 */

namespace esas\cmsgate\tilda\controllers;

use esas\cmsgate\tilda\CmsConnectorTilda;
use esas\cmsgate\tilda\protocol\ProtocolTilda;
use esas\cmsgate\tilda\protocol\TildaNotifyRq;
use esas\cmsgate\tilda\protocol\TildaNotifyRs;
use esas\cmsgate\wrappers\OrderWrapper;
use Exception;
use Throwable;

class ControllerTildaNotify extends ControllerTilda
{
    /**
     * @param OrderWrapper $orderWrapper
     * @return TildaNotifyRs
     * @throws Throwable
     */
    public function process($orderWrapper)
    {
        try {
            $this->checkOrderWrapper($orderWrapper);
//            if (!empty($orderWrapper->getExtId())) {
//                throw new Exception("Tilda was already notified");
//            }
            $loggerMainString = "Order[" . $orderWrapper->getOrderNumberOrId() . "]: ";
            $this->logger->info($loggerMainString . "Controller started");
            $notifyTildaRq = new TildaNotifyRq();
            $notifyTildaRq->setOrderId($orderWrapper->getOrderId());
            $notifyTildaRq->setAmount($orderWrapper->getAmount());
            $notifyTildaRq->setCurrency($orderWrapper->getCurrency());
            $protocol = new ProtocolTilda(CmsConnectorTilda::fromRegistry()->getNotificationURL());
            $resp = $protocol->notifyOnOrderPayed($notifyTildaRq);
            if ($resp->hasError()) {
                $this->logger->error($loggerMainString . "Can not notify Tilda CMS...");
                throw new Exception($resp->getResponseMessage(), $resp->getResponseCode());
            } else {
                $this->logger->info($loggerMainString . "Tilda CMS was successfully notified...");
            }
            return $resp;
        } catch (Throwable $e) {
            $this->logger->error($loggerMainString . "Controller exception! ", $e);
            throw $e;
        } catch (Exception $e) { // для совместимости с php 5
            $this->logger->error($loggerMainString . "Controller exception! ", $e);
            throw $e;
        }
    }

}