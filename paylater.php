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

if (!defined('_PS_VERSION_'))
	exit;

class Paylater extends PaymentModule
{
    protected $output = '';
    public static $modulePath;

    public function __construct()
    {
        $this->name = 'paylater';
        $this->tab = 'payments_gateways';
        $this->version = '3.0.1';
        $this->author = 'Pagantis';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->currencies = true;
        $this->currencies_mode = 'checkbox';

        self::initModuleAccess();

        parent::__construct();

        $this->displayName = $this->l('Pay later');
        $this->description = $this->l('Customers can pay later.');

        /* Backward compatibility */
        if (version_compare(_PS_VERSION_, "1.5", "<")) {
            require(_PS_MODULE_DIR_ . $this->name . '/backward_compatibility/backward.php');
        }
    }

    public function install()
    {
        Configuration::updateValue('PAYLATER_URL', 'https://pmt.pagantis.com/v1/installments');
        Configuration::updateValue('PAYLATER_ENVIRONMENT', 0);
        Configuration::updateValue('PAYLATER_ACCOUNT_ID_TEST', '');
        Configuration::updateValue('PAYLATER_ACCOUNT_KEY_TEST', '');
        Configuration::updateValue('PAYLATER_ACCOUNT_ID_LIVE', '');
        Configuration::updateValue('PAYLATER_ACCOUNT_KEY_LIVE', '');
				Configuration::updateValue('PAYLATER_DISCOUNT', 'false');
        //Configuration::updateValue('PAYLATER_CURRENCY', 'EUR');
//        Configuration::updateValue('PAYLATER_MIN_AMOUNT', 100);


        if(version_compare(_PS_VERSION_, "1.5", ">=")){
            $this->registerHook('displayPaymentEU');
        }

        return parent::install() &&
                $this->registerHook('header') &&
                $this->registerHook('payment') &&
                $this->registerHook('paymentReturn');
    }

    public function uninstall()
    {
        Configuration::deleteByName('PAYLATER_URL');
        Configuration::deleteByName('PAYLATER_ENVIRONMENT');
        Configuration::deleteByName('PAYLATER_ACCOUNT_ID_TEST');
        Configuration::deleteByName('PAYLATER_ACCOUNT_KEY_TEST');
        Configuration::deleteByName('PAYLATER_ACCOUNT_ID_LIVE');
        Configuration::deleteByName('PAYLATER_ACCOUNT_KEY_LIVE');
				Configuration::deleteByName('PAYLATER_DISCOUNT');
        //Configuration::deleteByName('PAYLATER_CURRENCY');
//        Configuration::deleteByName('PAYLATER_MIN_AMOUNT');

        return parent::uninstall();
    }

    public function postProcess() {
        if (Tools::isSubmit('submitPaylaterSettings')) {
            $error = '';
            Configuration::updateValue('PAYLATER_ENVIRONMENT', Tools::getValue('PAYLATER_ENVIRONMENT'));
            Configuration::updateValue('PAYLATER_ACCOUNT_ID_TEST', Tools::getValue('PAYLATER_ACCOUNT_ID_TEST'));
            Configuration::updateValue('PAYLATER_ACCOUNT_KEY_TEST', Tools::getValue('PAYLATER_ACCOUNT_KEY_TEST'));
            Configuration::updateValue('PAYLATER_ACCOUNT_ID_LIVE', Tools::getValue('PAYLATER_ACCOUNT_ID_LIVE'));
            Configuration::updateValue('PAYLATER_ACCOUNT_KEY_LIVE', Tools::getValue('PAYLATER_ACCOUNT_KEY_LIVE'));
						Configuration::updateValue('PAYLATER_DISCOUNT', Tools::getValue('PAYLATER_DISCOUNT'));
            //Configuration::updateValue('PAYLATER_CURRENCY', Tools::getValue('PAYLATER_CURRENCY'));

            /*if (!Validate::isInt(Tools::getValue('PAYLATER_MIN_AMOUNT')))
                $error .= $this->l('The minimun amount must be integer.');
            else
                Configuration::updateValue('PAYLATER_MIN_AMOUNT', Tools::getValue('PAYLATER_MIN_AMOUNT'));*/

            if ($error != '')
                $this->output .= $this->displayError($error);
            else
                $this->output .= $this->displayConfirmation($this->l('The settings updated ok.'));
        }
    }

    public function getContent()
    {
        $this->postProcess();

        $this->context->smarty->assign('module_dir', $this->_path);

        if(version_compare(_PS_VERSION_, "1.5", "<")){
            $this->context->smarty->assign(array(
                    'formAction' => $_SERVER['REQUEST_URI'],
                    'formConfigValues' => $this->getConfigFormValues(),
                    'selectValues' => array(0, 1),
                    'outputEnvironment' => array('TEST', 'REAL'),
                ));
            $this->output .= $this->display(__FILE__, 'views/templates/admin/config_14.tpl');
        }
        else{
            $this->output .= $this->context->smarty->fetch($this->local_path.'views/templates/admin/information.tpl');
            $this->output .= $this->displayFormSettings();
        }


        return $this->output;
    }

    public function displayFormSettings() {

        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language)
            $languages[$k]['is_default'] = (int)$language['id_lang'] == Configuration::get('PS_LANG_DEFAULT');

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = 'paylater';
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $languages;
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = true;
        $helper->toolbar_scroll = true;
        //$helper->toolbar_btn = $this->initToolbar();
        $helper->title = $this->displayName;
        $helper->submit_action = 'submitPaylaterSettings';

        $this->fields_form[0]['form'] = array(
            'tinymce' => false,
            'legend' => array(
                'title' => $this->l('Pay later settings')
            ),
            'input' => array(
                array(
                    'type' => 'select',
                    'name' => 'PAYLATER_ENVIRONMENT',
                    'is_bool' => true,
                    'label' => $this->l('Choose environment'),
                    'options' => array(
                        'query' => array(
                            array(
                                'id_env' => 0,
                                'name' => $this->l('Test')
                            ),
                            array(
                                'id_env' => 1,
                                'name' => $this->l('Real')
                            )
                        ),
                        'id' => 'id_env',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Account ID for test environment'),
                    'name' => 'PAYLATER_ACCOUNT_ID_TEST',
                    'required' => false,
                    'lang' => false,
                    'col' => 4,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Account key for test environment'),
                    'name' => 'PAYLATER_ACCOUNT_KEY_TEST',
                    'required' => false,
                    'lang' => false,
                    'col' => 4,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Account ID for live environment'),
                    'name' => 'PAYLATER_ACCOUNT_ID_LIVE',
                    'required' => false,
                    'lang' => false,
                    'col' => 4,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Account key for live environment'),
                    'name' => 'PAYLATER_ACCOUNT_KEY_LIVE',
                    'required' => false,
                    'lang' => false,
                    'col' => 4,
                ),
								array(
										'type' => 'select',
										'name' => 'PAYLATER_DISCOUNT',
										'is_bool' => true,
										'label' => $this->l('Discount'),
										'options' => array(
												'query' => array(
														array(
																'id_discount' => 'false',
																'name' => $this->l('False')
														),
														array(
																'id_discount' => 'true',
																'name' => $this->l('True')
														)
												),
												'id' => 'id_discount',
												'name' => 'name'
										)
								),
                /*array(
                    'type' => 'text',
                    'label' => $this->l('Currency'),
                    'name' => 'PAYLATER_CURRENCY',
                    'required' => false,
                    'lang' => false,
                    'col' => 2,
                ),*/
                /*array(
                    'type' => 'text',
                    'label' => $this->l('Minimum amount'),
                    'name' => 'PAYLATER_MIN_AMOUNT',
                    'desc' => $this->l('Cart minimum amount to pay later.'),
                    'required' => false,
                    'lang' => false,
                    'col' => 2,
                ),*/
            ),
            'submit' => array(
                'name' => 'submitPaylaterSettings',
                'title' => $this->l('Save'),
                'class' => 'button pull-right'
            ),
        );

        $helper->fields_value['PAYLATER_ENVIRONMENT'] = Configuration::get('PAYLATER_ENVIRONMENT');
        $helper->fields_value['PAYLATER_ACCOUNT_ID_TEST'] = Configuration::get('PAYLATER_ACCOUNT_ID_TEST');
        $helper->fields_value['PAYLATER_ACCOUNT_KEY_TEST'] = Configuration::get('PAYLATER_ACCOUNT_KEY_TEST');
        $helper->fields_value['PAYLATER_ACCOUNT_ID_LIVE'] = Configuration::get('PAYLATER_ACCOUNT_ID_LIVE');
        $helper->fields_value['PAYLATER_ACCOUNT_KEY_LIVE'] = Configuration::get('PAYLATER_ACCOUNT_KEY_LIVE');
				$helper->fields_value['PAYLATER_DISCOUNT'] = Configuration::get('PAYLATER_DISCOUNT');
        //$helper->fields_value['PAYLATER_CURRENCY'] = Configuration::get('PAYLATER_CURRENCY');
//        $helper->fields_value['PAYLATER_MIN_AMOUNT'] = Configuration::get('PAYLATER_MIN_AMOUNT');

        return $helper->generateForm($this->fields_form);
    }


    /**
     * Retrocompatibility PS 1.4 get config values
     * @return array
     */
    protected function getConfigFormValues()
    {
        return array(
            'PAYLATER_ENVIRONMENT' => Configuration::get('PAYLATER_ENVIRONMENT'),
            'PAYLATER_ACCOUNT_ID_TEST' => Configuration::get('PAYLATER_ACCOUNT_ID_TEST'),
            'PAYLATER_ACCOUNT_KEY_TEST' => Configuration::get('PAYLATER_ACCOUNT_KEY_TEST'),
            'PAYLATER_ACCOUNT_ID_LIVE' => Configuration::get('PAYLATER_ACCOUNT_ID_LIVE'),
            'PAYLATER_ACCOUNT_KEY_LIVE' => Configuration::get('PAYLATER_ACCOUNT_KEY_LIVE'),
			'PAYLATER_DISCOUNT' => Configuration::get('PAYLATER_DISCOUNT')
//            'PAYLATER_MIN_AMOUNT' => Configuration::get('PAYLATER_MIN_AMOUNT')
        );
    }

    public function hookHeader()
    {
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    public function hookPayment($params)
    {
			header( "X-Content-Security-Policy: allow 'self'; options inline-script eval-script; frame-ancestors https://pmt.pagantis.com " );
        /*if ($this->context->cart->getOrderTotal() < Configuration::get('PAYLATER_MIN_AMOUNT'))
            return;*/

        $customer = new Customer((int)($this->context->cart->id_customer));
        $cart_products = $this->context->cart->getProducts();
        $items = array();

				$description=array();
        foreach ($cart_products as $p) {
            $items[] = array(
                        'name' => $p['name']. " (".$p['cart_quantity'].")",
                        'cart_quantity' => $p['cart_quantity'],
                        'total_wt' => number_format($p['total_wt'], 2, '.', '')
            );
		     $desciption[]=  $p['name']. " (".$p['cart_quantity'].")";
        }

		//address
		$address = new Address($this->context->cart->id_address_delivery);
		$street=$address->address1.' '.$address->address2;
		$city=$address->city;
		$state = new state ($address->id_state);
		$province=$state->name;
		$zipcode=$address->postcode;

		//dni
		$dni=$address->dni;

		//dynamic CallbackFilterIterator
		$callback_url= $this->getPagantisCallbackUrl('validation.php', array());

        if(version_compare(_PS_VERSION_, "1.5", "<")){
            $shippingCost = $this->context->cart->getOrderShippingCost();
        }else{
            $shippingCost = $this->context->cart->getTotalShippingCost(null, true, null);
        }

        $url_OK = $this->getPagantisLink('confirmation.php', array('status'=>'ok', 'c' => $this->context->cart->id));
        $url_NOK = $this->getPagantisLink('confirmation.php', array('status'=>'ko'));

        if ($shippingCost > 0){
            $items[] = array(
                        'name' => $this->l('Shipping cost'),
                        'cart_quantity' => 1,
                        'total_wt' => number_format($shippingCost, 2, '.', '')
            );
			$desciption[]= $this->l('Shipping cost');
		}
        if (Configuration::get('PAYLATER_ENVIRONMENT') == 1) {
            //mode live
            $account_id = Configuration::get('PAYLATER_ACCOUNT_ID_LIVE');
        }
        else {
            //mode test
            $account_id = Configuration::get('PAYLATER_ACCOUNT_ID_TEST');
        }
		//discount
		$discount = Configuration::get('PAYLATER_DISCOUNT');

        $endpoint = Configuration::get('PAYLATER_URL');

        $order_id = $this->context->cart->id;

        //description
        //$description = $this->l('Pedido').' '.$order_id;
		$description = implode(',',$desciption);

        //if ($this->context->cart->getTotalShippingCost(null, true, null) > 0)
            //$description .= ' '.$this->l('+ shipping cost');

        $amount = number_format(Tools::convertPrice((($this->context->cart->getOrderTotal(true, 3))), $this->context->currency), 2, '.', '');
        $amount = str_replace('.','',$amount);

        //clave_de_firma + account_id + order_id + amount + currency + ok_url + nok_url

        //$cypher_method = "SHA1";

        if (Configuration::get('PAYLATER_ENVIRONMENT') == 1)
            $key_to_use = Configuration::get('PAYLATER_ACCOUNT_KEY_LIVE');
        else
            $key_to_use = Configuration::get('PAYLATER_ACCOUNT_KEY_TEST');

        //d($key_to_use.$account_id.$order_id.$amount.$this->context->currency->iso_code.$url_OK.$url_NOK);
        $signature = sha1($key_to_use.$account_id.$order_id.$amount.$this->context->currency->iso_code.$url_OK.$url_NOK.$callback_url.$discount);
				$this->smarty->assign(array(
            'endpoint' => $endpoint,
            'account_id' => $account_id,
            'currency' => $this->context->currency->iso_code,
            'ok_url' => $url_OK,
            'nok_url' => $url_NOK,
            'order_id' => $order_id,
            'amount' => $amount,
            'description' => $description,
            'items' => $items,
            'signature' => $signature,
            'customer_name' => ($this->context->cookie->logged ? $this->context->cookie->customer_firstname.' '.$this->context->cookie->customer_lastname : false),
            'customer_email' => ($this->context->cookie->logged ? $this->context->cookie->email : false),
            'locale' => $this->context->language->iso_code,
            'cart_products' => $cart_products,
						'street' => $street,
						'city' => $city,
						'province' => $province,
						'zipcode' => $zipcode,
						'dni' => $dni,
						'callback_url' => $callback_url,
						'discount' => $discount,
			      'iframe' => 'true',
						'installment2' => $this->instAmount($amount,2),
						'installment3' => $this->instAmount($amount,3),
						'installment4' => $this->instAmount($amount,4),
						'installment5' => $this->instAmount($amount,5),
						'installment6' => $this->instAmount($amount,6),
			            'version4' => version_compare(_PS_VERSION_, "1.5", "<"),
			        ));

        return $this->display(__FILE__, 'views/templates/front/payment.tpl');
    }

		/*
		* function instAmount
		* calculate the price of the installment
		* param $amount : amount in cents of the total loan
		* param $num_installments: number of installments, integer
		* return float with amount of the installment
		*/
		public function instAmount ($amount, $num_installments) {
			$discount = Configuration::get('PAYLATER_DISCOUNT');
			if ( $discount == "true"){
				return ($amount/100) / $num_installments;
			}
		  $r = 0.25/365; #daily int
		  $X = $amount/100; #total loan
		  $aux = 1;  #first inst value
		  for ($i=0; $i< $num_installments-2;$i++) {
		    $aux = $aux + pow(1/(1+$r) ,(45+30*$i));
		  }
		  return ($X/$aux);
		}

    public function hookDisplayPaymentEU($params)
    {
        return $this->hookPayment($params);
    }

    /**
     * Not used because pagantis redirect return to ok_url & nok_url
     * @param $params
     * @return mixed
     */
    public function hookPaymentReturn($params)
    {
        if ($this->active == false)
                return;

        $order = $params['objOrder'];

        if ($order->getCurrentOrderState()->id != Configuration::get('PS_OS_ERROR'))
                $this->smarty->assign('status', 'ok');

        $this->smarty->assign(array(
                'id_order' => $order->id,
                'reference' => $order->reference,
                'params' => $params,
                'total' => Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false),
        ));

        return $this->display(__FILE__, 'views/templates/front/confirmation.tpl');
    }

    /**
     * Retrocomatibility prestashop 1.4, it's necesary file path because doesn't exists ModuleFrontController
     * @param $file
     * @param array $params
     * @return string
     */
    public function getPagantisLink($file,array $params = array())
	{
			return Tools::getShopDomainSsl(true)._MODULE_DIR_.$this->name.'/'.$file.'?'.htmlspecialchars_decode(http_build_query($params));
	}

	/**
	 * Retrocomatibility prestashop 1.4, it's necesary file path because doesn't exists ModuleFrontController
	 * @param $file
	 * @param array $params
	 * @return string
	 */
		public function getPagantisCallbackUrl($file,array $params = array())
	{
			return Tools::getShopDomainSsl(true)._MODULE_DIR_.$this->name.'/'.$file.'?'.http_build_query($params);
	}

    public static function initModuleAccess()
    {
        paylater::$modulePath = _PS_MODULE_DIR_.'paylater/';
    }
}
