{include file="header.tpl"}

<div class="h1">Корзина</div>

<div class="row">
    <div class="col-md-12">
        <table class="table">
            <thead>
            <th>Товар</th>
            <th>Цена</th>
            <th>Кол-во</th>
            <th>Сумма</th>
            </thead>
            <tbody>
            {foreach from=$cart->getCartItems() item=cart_item}
                {assign var=product value=$cart_item->getProduct()}
                <tr>
                    <th>{$product->getName()}</th>
                    <td>{$product->getPrice()}</td>
                    <td>{$cart_item->getAmount()}</td>
                    <td>{$cart_item->getPrice()}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
</div>

{include file="bottom.tpl"}