<?php
/**
* paylater validation page, Prestashop 1.4 don't allow ModuleFrontController
* 
* @category payment
* @author    Victor Lopez <victor.lopez@yameveo.com>
* @copyright Yameveo http://www.yameveo.com
* @license   http://www.yameveo.com/license
*/

$path = $_SERVER['DOCUMENT_ROOT'];
include("{$path}/config/config.inc.php");
include("{$path}/init.php");

require_once('./paylater.php');
$paylater = new Paylater();
// Recoger datos de respuesta
$module_name = $paylater->displayName;
$currency_id = (int)Context::getContext()->currency->id;

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$cart_id = $data["data"]["order_id"];

if ($data["event"] == 'charge.created')
{
    $cart = new Cart((int)$cart_id);
    $customer = new Customer((int)$cart->id_customer);
    $secure_key = $customer->secure_key;

    $payment_status = Configuration::get('PS_OS_PAYMENT');
    $message = null;

    $paylater->validateOrder($cart_id, $payment_status, $cart->getOrderTotal(), $module_name, $message, array(), $currency_id, false, $secure_key);
}