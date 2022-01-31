<?php

namespace esas\cmsgate\protocol;

use esas\cmsgate\epos\protocol\TildaRs;
use esas\cmsgate\epos\RegistryEpos;
use esas\cmsgate\epos\view\admin\AdminViewFieldsEpos;
use esas\cmsgate\epos\wrappers\ConfigWrapperEpos;
use esas\cmsgate\protocol\Amount;
use esas\cmsgate\protocol\ProtocolCurl;
use esas\cmsgate\protocol\ProtocolError;
use esas\cmsgate\protocol\RqMethod;
use esas\cmsgate\protocol\RsType;
use esas\cmsgate\tilda\RequestParamsTilda;
use esas\cmsgate\utils\CMSGateException;
use Exception;
use Throwable;

class ProtocolTilda extends ProtocolCurl
{
    const EPOS_URL_REAL_UPS = 'https://api.e-pos.by/public/'; // рабочий
    const EPOS_URL_REAL_ESAS = 'https://api-epos.hgrosh.by/public/'; // рабочий
    const EPOS_URL_REAL_RRB = 'https://api.e-pos.by/rrb/public/'; // рабочий
    const EPOS_URL_TEST = 'https://api-dev.hgrosh.by/epos/public/'; // тестовый

    /**
     * @var string
     */
    private $authToken;

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
            $postData[RequestParamsTilda::SIGNATURE] = $notifyRq->getSignature();
            $postData[RequestParamsTilda::PAYMENT_STATUES] = 'payed';

            // запрос
            $rsStr = $this->requestPost('', $postData, RsType::_STRING);
            if ($rsStr == null) {
                throw new Exception("Null response!", TildaRs::ERROR_NULL_RESP);
            }
            if ($rsStr !== 'OK') {
                $resp->setResponseCode(TildaRs::ERROR_RESP_NOT_OK);
                $resp->setResponseMessage('Got [' . $rsStr . '] code from Tilda');
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
//            $headers = array();
//            $headers[] = 'Content-Type: application/json';
//            $headers[] = 'Authorization: Bearer ' . $this->authToken;
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