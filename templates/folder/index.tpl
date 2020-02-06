{include file="header.tpl"}

<div class="row">
    <div class="col-6 mb-4">
        <a class="btn btn-success" href="/folder/edit.php">Добавить категорию</a>
    </div>
</div>

<div class="row">
    <table class="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col" width="1">#</th>
            <th scope="col">Название</th>
            <th scope="col" width="1">&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        {foreach from=$folders item=folder}
            <tr>
                <th scope="row"></th>
                <td>{$folder->getName()}</td>
                <td style="white-space: nowrap;"><a href="/folder/edit.php?folder_id={$folder->getId()}" class="btn btn-sm btn-primary">Редактировать</a>
                    <form style="display:inline-block;" action="/folder/delete.php" method="post"><input type="hidden" name="folder_id" value="{$folder->getId()}"><input type="submit" class="btn btn-sm btn-danger ml-2" value="Удалить"/></form></td>
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>


{include file="bottom.tpl"}