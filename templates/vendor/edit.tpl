{include file="header.tpl"}

<form action="/vendor/editing.php" method="post">
    <input type="hidden" name="vendor_id" value="{$vendor->getId()}">
    <div class="form-group">
        <label for="name">Название производителя</label>
        <input id="name" type="text" name="name" class="form-control" required value="{$vendor->getName()}">
    </div>
    <div class="form-group">
        <label for="description">Описание: </label>
        <textarea rows="8" id="name" name="description" class="form-control" required>{$vendor->getDescription()}</textarea>
    </div>

    <button type="submit" class="btn btn-primary mb-2">Сохранить</button>
</form>

{include file="bottom.tpl"}