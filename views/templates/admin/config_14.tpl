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
<style>
    #payment-logo{
        margin: 19px 0;
        max-width: 200px;
        padding-left: 10px;
    }
    #paylater_config .labels{
        width:230px;
    }
</style>

<fieldset id="paylater_config">

    <h2>{l s='Pay later' mod='paylater'}</h2>
    <div class="paylater-header">
        <div class="col-xs-6 col-md-4">
            <p><strong>{l s='Now your customers can buy and pay later!' mod='paylater'}</strong></p><br />
            <p>
                {l s='Please complete the information below. You can find all this informations in API section of ' mod='paylater'}
                <a href="https://bo.pagantis.com" target="_blank">https://bo.pagantis.com</a>
            </p>
        </div>
        <div class="col-xs-12 col-md-4 pull-right text-right">
            <img src="{$module_dir|escape:'html':'UTF-8'}views/img/logo_pagamastarde.png" class="col-xs-6 col-md-4 pull-right" id="payment-logo" />
        </div>
    </div>


    <form action="{$formAction|escape:'htmlall':'UTF-8'}" method="POST">
        <h2>{l s='Configuration settings' mod='paylater'}</h2>

        <label for="PAYLATER_ENVIRONMENT" class="labels"> {l s='Choose environment' mod='paylater'}</label>
        <select name="PAYLATER_ENVIRONMENT" size="1" class="selects">
            {html_options values=$selectValues output=$outputEnvironment selected=$formConfigValues.PAYLATER_ENVIRONMENT}
        </select>
        <br />

        <br />

        <br />
        <label for="PAYLATER_ACCOUNT_ID_TEST" class="labels">{l s='Account ID for test environment' mod='paylater'} </label>
        <input
                id="PAYLATER_ACCOUNT_ID_TEST"
                class="paylater_input"
                type="text"
                name="PAYLATER_ACCOUNT_ID_TEST"
                value="{$formConfigValues.PAYLATER_ACCOUNT_ID_TEST|escape:'htmlall':'UTF-8'}" />
        <br />

        <br />
        <label for="PAYLATER_ACCOUNT_KEY_TEST" class="labels">{l s='Account key for test environment' mod='paylater'} </label>
        <input
                id="PAYLATER_ACCOUNT_KEY_TEST"
                class="paylater_input"
                type="text"
                name="PAYLATER_ACCOUNT_KEY_TEST"
                value="{$formConfigValues.PAYLATER_ACCOUNT_KEY_TEST|escape:'htmlall':'UTF-8'}" />
        <br />

        <br />
        <label for="PAYLATER_ACCOUNT_ID_LIVE" class="labels">{l s='Account ID for live environment' mod='paylater'} </label>
        <input
                id="PAYLATER_ACCOUNT_ID_LIVE"
                class="paylater_input"
                type="text"
                name="PAYLATER_ACCOUNT_ID_LIVE"
                value="{$formConfigValues.PAYLATER_ACCOUNT_ID_LIVE|escape:'htmlall':'UTF-8'}" />
        <br />

        <br />
        <label for="PAYLATER_ACCOUNT_KEY_LIVE" class="labels">{l s='Account key for live environment' mod='paylater'} </label>
        <input
                id="PAYLATER_ACCOUNT_KEY_LIVE"
                class="paylater_input"
                type="text"
                name="PAYLATER_ACCOUNT_KEY_LIVE"
                value="{$formConfigValues.PAYLATER_ACCOUNT_KEY_LIVE|escape:'htmlall':'UTF-8'}" />
        <br />

        <br />
        <label for="PAYLATER_MIN_AMOUNT" class="labels">{l s='Cart minimum amount to pay later.' mod='paylater'} </label>
        <input
                id="PAYLATER_MIN_AMOUNT"
                class="paylater_input"
                type="text"
                name="PAYLATER_MIN_AMOUNT"
                value="{$formConfigValues.PAYLATER_MIN_AMOUNT|escape:'htmlall':'UTF-8'}" />

        <br /><br />

        <input
                class="button"
                name="submitPaylaterSettings"
                value="{l s='Save' mod='paylater'}"
                type="submit"
                style="margin-top: 10px" />
    </form>
</fieldset>