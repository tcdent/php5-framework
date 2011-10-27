<div id="column_left">
    <table>
<?      foreach($vehicles as $vehicle): ?>
        <tr>
            <td>
                <a href="<?= SITE_ROOT ?>/admin/vehicles/<?= $vehicle->id ?>/edit">
                    <strong><?= $vehicle->license ?> (<?= $vehicle->state ?>)</a></strong></a>
            </td>
            <td>
                <a href="<?= SITE_ROOT ?>/admin/vehicles/<?= $vehicle->id ?>/delete" onclick="return confirm('Are you sure you want to delelte this?');" class="button"><span>Delete</span></a>
                <a href="<?= SITE_ROOT ?>/admin/vehicles/<?= $vehicle->id ?>/edit" class="button"><span>Edit</span></a>
            </td>
        </tr>
<?      endforeach; ?>
    </table>
    
    <div class="pagination">
        <? if($page > 1): ?><a href="?page=<?= $page - 1 ?>">&laquo; Previous</a><? endif; ?>
        <? if($pages > 0): ?>Page <?= $page ?> of <?= $pages ?>.<? endif; ?>
        <? if($page < $pages): ?><a href="?page=<?= $page + 1 ?>">Next &raquo;</a><? endif; ?>
    </div>
</div>

<div id="column_right"></div>