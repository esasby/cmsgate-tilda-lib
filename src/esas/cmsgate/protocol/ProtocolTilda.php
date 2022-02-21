<?php

namespace esas\cmsgate\protocol;

use esas\cmsgate\CloudRegistry;
use esas\cmsgate\Registry;
use esas\cmsgate\tilda\RequestParamsTilda;
use esas\cmsgate\utils\CloudSessionUtils;
use esas\cmsgate\utils\StringUtils;
use Exception;
use Throwable;

class ProtocolTilda extends ProtocolCurl
{
    /**
     * @throws Exception
     */
    public function __construct($notificationUrl)
    {
        parent::__construct($notificationUrl, $notificationUrl);
    }

    /**
     * Добавляет новый счет в систему
     *
     * @param TildaNotifyRq $notifyRq
     * @return TildaNotifyRs
     * @throws Exception
     */
    public function notifyOnOrderPayed(TildaNotifyRq $notifyRq)
    {
        $resp = new TildaNotifyRs();
        $loggerMainString = "Order[" . $notifyRq->getOrderId() . "]: ";
        try {// формируем xml
            $this->logger->debug($loggerMainString . "notify started");
            $postData = array();
            $postData[RequestParamsTilda::ORDER_ID] = $notifyRq->getOrderId();
            $postData[RequestParamsTilda::ORDER_AMOUNT] = $notifyRq->getAmount();
            $postData[RequestParamsTilda::ORDER_CURRENCY] = $notifyRq->getCurrency();
            $postData[RequestParamsTilda::PAYMENT_STATUS] = 'payed';
            if ($notifyRq->getSignature() == null || $notifyRq->getSignature() == '') {
                $postData[RequestParamsTilda::SIGNATURE] = $this->generateNotificationSignature($postData);
            } else
                $postData[RequestParamsTilda::SIGNATURE] = $notifyRq->getSignature();
            // запрос
            $rsStr = $this->requestPost('', $postData, RsType::_STRING);
            if ($rsStr == null) {
                throw new Exception("Null response!", TildaRs::ERROR_NULL_RESP);
            }
            if (!StringUtils::endsWith($rsStr,'OK')) {
                $resp->setResponseCode(TildaRs::ERROR_RESP_NOT_OK);
                $resp->setResponseMessage('Got ERROR from Tilda');
            }
            $this->logger->debug($loggerMainString . "notify ended");
        } catch (Throwable $e) {
            $this->logger->error($loggerMainString . "notify exception", $e);
            $resp->setResponseCode($e->getCode());
            $resp->setResponseMessage($e->getMessage());
        } catch (Exception $e) { // для совместимости с php 5
            $this->logger->error($loggerMainString . "notify exception", $e);
            $resp->setResponseCode($e->getCode());
            $resp->setResponseMessage($e->getMessage());
        }
        return $resp;
    }

    private function generateNotificationSignature($request) {
        $secret = CloudSessionUtils::getConfigCacheObj()->getSecret();
        $line = $request[RequestParamsTilda::ORDER_ID]
            . '|' . $request[RequestParamsTilda::PAYMENT_STATUS]
            . '|' . $request[RequestParamsTilda::ORDER_AMOUNT]
            . '|' . $secret
            . '|' . $request[RequestParamsTilda::ORDER_CURRENCY];
        $this->logger->info('Sign values: ' . $line);
        return hash('sha256', $line);
    }

    /**
     * Подключение GET, POST или DELETE
     *
     * @param string $path
     * @param string $data Сформированный для отправки XML
     * @param int $request
     * @param $rsType
     *
     * @return mixed
     * @throws Exception
     */
    protected function send($method, $data, $rqMethod, $rsType)
    {
        try {
            $url = $this->connectionUrl . $method;
            $this->defaultCurlInit($url);
            curl_setopt($this->ch, CURLOPT_HEADER, $this->configurationWrapper->isSandbox()); // включение заголовков в выводе
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false); // не проверять сертификат узла сети
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false); // проверка существования общего имени в сертификате SSL
            switch ($rqMethod) {
                case RqMethod::_GET:
                    $headers[] = 'Content-Length: ' . strlen($data);
                    break;
                case RqMethod::_POST:
                    curl_setopt($this->ch, CURLOPT_POST, true);
                    curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
                    break;
                case RqMethod::_PUT:
                    curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
                    break;
                case RqMethod::_DELETE:
                    curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                    break;
            }
            if (isset($headers) && is_array($headers))
                curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers); // Массив устанавливаемых HTTP-заголовков
            // для безопасности прячем пароли из лога
            $logStr = $data;
            if (is_array($logStr))
                $logStr = json_encode($logStr);
            $this->logger->info('Sending ' . RqMethod::toString($rqMethod) . ' request[' . $logStr . "] to url[" . $url . "]");
            $response = $this->execCurlAndLog();
        } finally {
            curl_close($this->ch);
        }
        return $this->convertRs($response, $rsType);
    }

}