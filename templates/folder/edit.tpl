{include file="header.tpl"}

<form action="/folder/editing.php" method="post">
    <input type="hidden" name="folder_id" value="{$folder->getId()}">
    <div class="form-group">
        <label for="name">Название категории</label>
        <input id="name" type="text" name="name" class="form-control" required value="{$folder->getName()}">
    </div>

    <button type="submit" class="btn btn-primary mb-2">Сохранить</button>
</form>

{include file="bottom.tpl"}