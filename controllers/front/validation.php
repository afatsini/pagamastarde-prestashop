<?php
/**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class PaylaterValidationModuleFrontController extends ModuleFrontController
{
    
    public function initContent() {
        
        if (!Tools::getValue('redirect')) {
            $module_name = $this->module->displayName;
            $currency_id = (int)Context::getContext()->currency->id;

            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            /*$json = Tools::file_get_contents('php://input');
            $data = Tools::json_decode($json, true);*/

            $order_id = $data["data"]["order_id"];
            $cart_id = $order_id;

            if ($data["event"] == 'charge.created')
            {
                $cart = new Cart((int)$cart_id);
                $customer = new Customer((int)$cart->id_customer);
                $secure_key = $customer->secure_key;

                $payment_status = Configuration::get('PS_OS_PAYMENT');
                $message = null; 

                $this->module->validateOrder($cart_id, $payment_status, $cart->getOrderTotal(), $module_name, $message, array(), $currency_id, false, $secure_key);
                Tools::redirect('index.php?controller=order-confirmation&id_cart='.$cart->id.'&id_module='.$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$secure_key);
            }
        }
    }
}
