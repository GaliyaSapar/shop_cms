{include file="header.tpl"}
<div class="row">
    <div class="col-6 mb-4"><a href="/product/edit.php" class="btn btn-success">Добавить товар</a></div>
</div>

<div class="row">
    {foreach from=$products item=product}
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img"  aria-label="Placeholder: Thumbnail"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Фото товара</text></svg>
                <div class="card-body">
                    <p class="card-text">{$product->getName()}</p>
                    <p class="card-text"><a href="/product/view.php?product_id={$product->getId()}">{$product->getName()}</a>"></p>
                    <p>
                    <ul>
                        <li>Кол-во товара: {$product->getAmount()}</li>
                        {assign var=product_vendor_id value=$product->getVendorId()}
                        <li>Производитель: {$vendors[product_vendor_id]->getName()}</li>
                        <li>Категории: {foreach from=$product->getFolderIds() item=folder_id name=product_folder_ids}
                                {$folders[$folder_id]->getName()}{if !$smarty.foreach.product_folder_ids.last}, {/if}{foreachelse}&ndash;{/foreach}</li>
                    </ul>
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                            {*								<button type="button" class="btn btn-sm btn-outline-secondary">View</button>*}
                            <a href="/product/edit.php?product_id={$product->getId()}" class="btn btn-sm btn-outline-secondary">Редактировать</a>
                        </div>
                        <small class="text-muted">{$product->getPrice()}</small>
                    </div>
                </div>
            </div>
        </div>
    {/foreach}
</div>

{include file="bottom.tpl"}