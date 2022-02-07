<?php

namespace esas\cmsgate\wrappers;

use esas\cmsgate\OrderStatus;
use esas\cmsgate\tilda\RequestParamsTilda;
use esas\cmsgate\utils\StringUtils;
use Throwable;

class OrderWrapperTilda extends OrderWrapperCached
{
    protected $products;

    /**
     * @param $order
     */
    public function __construct($orderCache)
    {
        parent::__construct($orderCache);
    }

    /**
     * Уникальный идентификатор заказ в рамках CMS.
     * В Tilda это <номеро проекта>:<номер заказа>
     * @return string
     */
    public function getOrderIdUnsafe()
    {
        $tildaProjectAndOrderId = $this->orderCache->getOrderData()[RequestParamsTilda::ORDER_ID];
        return $tildaProjectAndOrderId;
    }

    /**
     * Для более удобной работы клиента со счетом, убираем из идентификатора заказ номер проекта
     * @return string
     */
    public function getOrderNumberUnsafe()
    {
        $tildaProjectAndOrderId = $this->orderCache->getOrderData()[RequestParamsTilda::ORDER_ID];
        return StringUtils::substrAfter($tildaProjectAndOrderId, ":");
    }

    /**
     * Полное имя покупателя
     * @return string
     */
    public function getFullNameUnsafe()
    {
        return $this->orderCache->getOrderData()[RequestParamsTilda::CUSTOMER_FIO];
    }

    /**
     * Мобильный номер покупателя для sms-оповещения
     * (если включено администратором)
     * @return string
     */
    public function getMobilePhoneUnsafe()
    {
        return $this->orderCache->getOrderData()[RequestParamsTilda::CUSTOMER_PHONE];
    }

    /**
     * Email покупателя для email-оповещения
     * (если включено администратором)
     * @return string
     */
    public function getEmailUnsafe()
    {
        return $this->orderCache->getOrderData()[RequestParamsTilda::CUSTOMER_EMAIL];
    }

    /**
     * Физический адрес покупателя
     * @return string
     */
    public function getAddressUnsafe()
    {
        return $this->orderCache->getOrderData()['customer.address']; // can not be sent by tilda
    }

    /**
     * Общая сумма товаров в заказе
     * @return string
     */
    public function getAmountUnsafe()
    {
        return $this->orderCache->getOrderData()[RequestParamsTilda::ORDER_AMOUNT];
    }


    public function getShippingAmountUnsafe()
    {
        return 0; //todo
    }

    /**
     * Валюта заказа (буквенный код)
     * @return string
     */
    public function getCurrencyUnsafe()
    {
        return $this->orderCache->getOrderData()[RequestParamsTilda::ORDER_CURRENCY];
    }

    /**
     * Массив товаров в заказе
     * @return \esas\cmsgate\wrappers\OrderProductWrapperTilda[]
     */
    public function getProductsUnsafe()
    {
        if ($this->products != null)
            return $this->products;
        $items = json_decode($this->orderCache->getOrderData()[RequestParamsTilda::ORDER_ITEMS], true);
        foreach ($items as $basketItem)
            $this->products[] = new OrderProductWrapperTilda($basketItem);
        return $this->products;
    }

    /**
     * Текущий статус заказа в CMS
     * @return mixed
     */
    public function getStatusUnsafe()
    {
        return OrderStatus::pending();
    }
    

    /**
     * Идентификатор клиента
     * @return string
     */
    public function getClientIdUnsafe()
    {
        return 'UNKNOWN';
    }

}