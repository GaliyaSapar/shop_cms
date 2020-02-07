{include file="header.tpl"}
<div class="row">
    <div class="col-6 mb-4"><a href="/product/edit.php" class="btn btn-success">Добавить товар</a></div>
    <div class="col-6 mb-4">
            <div>
{*                <label for="search_type">Искать по: </label>*}
{*                <select class="form-control" name="search_option" id="search_type">*}
{*                    <option value="id">ID</option>*}
{*                    <option selected value="name">Название</option>*}
{*                    <option value="price">Цена</option>*}
{*                </select>*}
                <div>Искать по: </div>
                <label><input checked type="radio" name="search_type" value="id"'; > ID </label>
                <label><input type="radio" name="search_type" value="name"'; > Название </label>
                <label><input type="radio" name="search_type" value="price"'; > Цена </label>

            </div>

            {literal}
            <script>
                $("input[name=search_type]").change(function () {
                    if ($(this).val() == 'name') {
                        $('.by-name').css('display', 'block');
                        $('.by-price').css('display', 'none');
                        $('.by-id').css('display', 'none');
                    } else if ($(this).val() == 'id') {
                        $('.by-id').css('display', 'block');
                        $('.by-name').css('display', 'none');
                        $('.by-price').css('display', 'none');
                    } else if ($(this).val() == 'price') {
                        $('.by-name').css('display', 'none');
                        $('.by-price').css('display', 'block');
                        $('.by-id').css('display', 'none');
                    }
                });



                // $('#search_type').change(function () {
                //     if ($('#search_type :selected').val() == 'name') {
                //         $('.by-name').css('display', 'block');
                //         $('.by-price').css('display', 'none');
                //         $('.by-id').css('display', 'none');
                //     } else if ($('#search_type :selected').val() == 'id') {
                //         $('.by-id').css('display', 'block');
                //         $('.by-name').css('display', 'none');
                //         $('.by-price').css('display', 'none');
                //     } else if ($('#search_type :selected').val() == 'price') {
                //         $('.by-name').css('display', 'none');
                //         $('.by-price').css('display', 'block');
                //         $('.by-id').css('display', 'none');
                //     }
                // });
            </script>
            {/literal}
            <p></p>

            <div>
                <form action="product/search.php" method="get" class="by-name">
                    {*        <input type="hidden" name="product_id" value="{$product->getId()}">*}
                    <div class="form-group" style="display: inline-block">
                        <input id="product_name" type="text" name="product_name" class="form-control" style="display:block">
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Найти</button>
                </form>
                <form action="product/search.php" method="get" class="by-id" style="display: none">
                    <div class="form-group" style="display: inline-block">
                        <input id="product_id" type="number" name="product_id" class="form-control" >
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Найти</button>
                </form>
                <form action="product/search.php" method="get" class="by-price" style="display: none">
                    <div class="form-group" style="display: inline-block">
                        <label> От:
                            <input id="product_price_from" type="text" name="product_price_from" class="form-control">
                        </label>
                        <label> До:
                            <input id="product_price_to" type="text" name="product_price_to" class="form-control">
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Найти</button>
                </form>
            </div>

    </div>
</div>
<p>Всего товаров: {$products.count}</p>
<nav>
    <ul class="pagination pagination-sm">
        {section start="1" loop=$paginator.pages+1 name="paginator"}
            <li class="page-item {if $smarty.section.paginator.iteration == $paginator.current}active{/if}">
                {if $smarty.section.paginator.iteration == $paginator.current}
                    <span class="page-link">{$smarty.section.paginator.iteration}</span>
                {else}
                    <a class="page-link" href="/?page={$smarty.section.paginator.iteration}">{$smarty.section.paginator.iteration}</a>
                {/if}
            </li>
        {/section}
    </ul>
</nav>

<div class="row">
    {foreach from=$products.items item=product}
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img"  aria-label="Placeholder: Thumbnail"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Фото товара</text></svg>
                <div class="card-body">
                    <p class="card-text">{$product->getName()}</p>
                    <p class="card-text"><a href="/product/view.php?product_id={$product->getId()}">{$product->getName()}</a></p>
                    <p>
                    <ul>
                        <li>Кол-во товара: {$product->getAmount()}</li>
                        {assign var=product_vendor_id value=$product->getVendorId()}
                        <li>Производитель: {$vendors[$product_vendor_id]->getName()}</li>
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