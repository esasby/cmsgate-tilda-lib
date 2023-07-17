<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 14.04.2020
 * Time: 14:59
 */

namespace esas\cmsgate\tilda\protocol;


class RequestParamsTilda
{
    const LANG = 'lang';
    const ORDER_ID = 'order_id';
    const ORDER_AMOUNT = 'order_amount';
    const ORDER_CURRENCY = 'order_currency';
    const ORDER_ITEMS = 'order_items';
    const CUSTOMER_FIO = 'customer_fio';
    const CUSTOMER_PHONE = 'customer_phone';
    const CUSTOMER_EMAIL = 'customer_email';
    const SIGNATURE = 'signature';
    const PAYMENT_STATUS = 'payment_status';
    const NOTIFICATION_URL = 'tilda_notification_url';
    const SUCCESS_URL = 'tilda_success_url';
    const FAILED_URL = 'tilda_failed_url';
    const SECRET = 'secret';
}