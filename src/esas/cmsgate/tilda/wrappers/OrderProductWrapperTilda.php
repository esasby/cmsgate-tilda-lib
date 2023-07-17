<?php
namespace esas\cmsgate\tilda\wrappers;

use esas\cmsgate\wrappers\OrderProductSafeWrapper;
use Throwable;

class OrderProductWrapperTilda extends OrderProductSafeWrapper
{
    /**
     * @var string[]
     */
    private $orderItem;

    /**
     * OrderProductWrapperTilda constructor.
     * @param array
     */
    public function __construct($orderItem)
    {
        parent::__construct();
        $this->orderItem = $orderItem;
    }


    /**
     * Артикул товара
     * @throws Throwable
     * @return string
     */
    public function getInvIdUnsafe()
    {
        return $this->orderItem['id']; // must be fixed after tilda update, cause field SKU is no available for universal gateway
    }

    /**
     * Название или краткое описание товара
     * @throws Throwable
     * @return string
     */
    public function getNameUnsafe()
    {
        return $this->orderItem['name'];
    }

    /**
     * Количество товароа в корзине
     * @throws Throwable
     * @return mixed
     */
    public function getCountUnsafe()
    {
        return $this->orderItem['quantity'];
    }

    /**
     * Цена за единицу товара
     * @throws Throwable
     * @return mixed
     */
    public function getUnitPriceUnsafe()
    {
        return $this->orderItem['price'];
    }
}