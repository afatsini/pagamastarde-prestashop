{*
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
*}

{capture name=path}{l s='Payment error' mod='paylater'}{/capture}

{include file="$tpl_dir./breadcrumb.tpl"}

<h2 style="font-style: normal;">{l s='Your payment could not be completed' mod='paylater'}</h2><br />


<div style="float:right; margin-left: 25px;">
  <img src="{$modules_dir|escape:'html':'UTF-8'}paylater/img/error.png" alt="{l s='Payment error' mod='paylater'}" longdesc="{l s='Payment error' mod='paylater'}" /></td></tr><tr>
</div>

<p>
{l s='We are sorry, but your payment could not be successfully completed. You can try again or choose another payment method. Remember that you can only use Visa and Mastercard credit or debit cards.' mod='paylater'}
</p>

<p>
{l s='There are several reasons for this to happen:' mod='paylater'}
  <ul>
    <li>- {l s='You mistook any of the digits of your credit card. Make sure you entered them correctly.' mod='paylater'}</li>
    <li>- {l s='Make sure your credit card is not expired and has been blocked.' mod='paylater'}</li>
    <li>- {l s='There has been a problem with our payment gateway provider.' mod='paylater'}</li>
  </ul>
</p>

<p>
{l s='In any case, you can contact us by mail or by phone and we will try to fix your problem together.' mod='paylater'}
</p>

<br />

<a href="{$base_dir_ssl|escape:'html':'UTF-8'}index.php?controller=order&step=3" title="{l s='Try again' mod='paylater'}" style="text-transform: uppercase; border: 1px solid green; background-color: green; font-size: 13px; font-weight: bold; color: white; padding: 5px; float: right; margin: 4em 0;">{l s='Try again' mod='paylater'}</a>
