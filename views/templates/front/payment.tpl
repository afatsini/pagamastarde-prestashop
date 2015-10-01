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


{if $version4}
    <p class="payment_module">
        <a href="javascript:$('#paylater_form').submit();" title="{l s='Pay later' mod='paylater'}">
            <img id="logo_pagamastarde" src="{$module_dir|escape:htmlall}views/img/logo_pagamastarde.png" alt="{l s='Logo Paga Mas Tarde' mod='paylater'}"
                    style="max-width: 80px"/>
            {l s='Buy now, pay later' mod='paylater'}
        </a>
    </p>

{else}
    <div class="row">
        <div class="col-xs-12">
            {if version_compare($smarty.const._PS_VERSION_,'1.6.0.0','<')}
                <div class="payment_module">
                    <a href="javascript:$('#paylater_form').submit();" title="{l s='Pay later' mod='paylater'}">
                        <img id="logo_pagamastarde" src="{$module_dir|escape:htmlall}views/img/logo_pagamastarde.png" alt="{l s='Logo Paga Mas Tarde' mod='paylater'}"
                             style="max-width: 80px"/>
                        <div class="paylater_text">
                            {l s='Buy now, pay later' mod='paylater'}
                        </div>
                    </a>
                </div>
            {else}
                <p class="payment_module" id="paylater_payment_button">
                    <a href="javascript:$('#paylater_form').submit();" title="{l s='Pay later' mod='paylater'}">
                        {l s='Buy now, pay later' mod='paylater'}
                    </a>
                </p>

            {/if}
        </div>
    </div>
{/if}



<form action="{$endpoint|escape:'html':'UTF-8'}" method="post" id="paylater_form">
    <input type="hidden" name="account_id" value="{$account_id|escape:'html':'UTF-8'}" />
    <input type="hidden" name="currency" value="{$currency|escape:'html':'UTF-8'}" />
    <input type="hidden" name="ok_url" value="{$ok_url|escape:'html':'UTF-8'}" />
    <input type="hidden" name="nok_url" value="{$nok_url|escape:'html':'UTF-8'}" />
    <input type="hidden" name="order_id" value="{$order_id|intval}" />
    <input type="hidden" name="amount" value="{$amount|intval}" />
    <input type="hidden" name="signature" value="{$signature|escape:'html':'UTF-8'}" />
    <input type="hidden" name="description" value="{$description|escape:'html':'UTF-8'}" />
    <input name="locale" type="hidden" value="{$locale|escape:'html':'UTF-8'}" />
    <input name="full_name" type="hidden" value="{$customer_name|escape:'html':'UTF-8'}">
    <input name="email" type="hidden" value="{$customer_email|escape:'html':'UTF-8'}">

    {foreach from=$items item=item name=items}
        <input name="items[{$smarty.foreach.items.iteration-1}][description]" type="hidden" value="{$item.name|escape:'html':'UTF-8'}">
        <input name="items[{$smarty.foreach.items.iteration-1}][quantity]" type="hidden" value="{$item.cart_quantity|intval}">
        <input name="items[{$smarty.foreach.items.iteration-1}][amount]" type="hidden" value="{$item.total_wt|floatval}">
    {/foreach}
</form>





