<?php
/**
 * Payment OK process
 *
 * @category  payment_gateway
 * @license   http://www.yameveo.com/license
 */

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');

$version5 = _PS_VERSION_ < '1.6';
$context = Context::getContext();
$status  = Tools::getValue('status');

if($status == "ok"){
    $cart_id = Tools::getValue('c');
    $order_id = Order::getOrderByCartId($cart_id);
    $order = new Order($order_id);
    $context->smarty->assign(array('total' => $order->total_paid));
    $context->smarty->display(_PS_MODULE_DIR_.'paylater/views/templates/front/confirmation.tpl');
}else{
    $context->smarty->display(_PS_MODULE_DIR_.'paylater/views/templates/front/error.tpl');
}

include(dirname(__FILE__).'/../../footer.php');

?>