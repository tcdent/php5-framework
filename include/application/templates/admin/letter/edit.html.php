<div id="column_left">
    <form action="<?= SITE_ROOT ?>/admin/vehicles/<?= empty($vehicle->id)? 'save' : 'update' ?>" method="post" enctype="multipart/form-data" accept-charset="utf-8" id="edit_vehicle_form">
        <? if(!empty($vehicle->id)): ?><input type="hidden" name="id" value="<?= $vehicle->id ?>"><? endif; ?>
        <p>
            <label for="license">License Plate</label>
            <input type="text" name="license" class="title" value="<?= empty($vehicle->license)? '' : $vehicle->license ?>">
        </p>
        <p>
            <label for="state">State</label>
            <select name="state">
<?          foreach(Vehicle::$STATES as $state): ?>
                <option name="<?= $state ?>"<? if(!empty($vehicle->state) && $vehicle->state == $state): ?> selected="selected"<? endif; ?>><?= $state ?></option>
<?          endforeach; ?>
            </select>
        </p>
        <p>
            <label for="phone_number">Phone Number</label>
            <input type="text" name="phone_number" value="<?= empty($vehicle->phone_number)? '' : $vehicle->phone_number ?>">
        </p>
        <p>
            <label for="paused">Paused</label>
            <input type="checkbox" name="paused" value="1"<? if($vehicle->paused): ?> checked="checked"<? endif; ?>>
        </p>
        
        <div class="actions">
            <a href="#" onclick="$('edit_vehicle_form').submit()" class="button"><span>Save</span></a>
            <a href="<?= SITE_ROOT ?>/admin/vehicles" class="button"><span>Cancel</span></a>
        </div>
    </form>
</div>

<div id="column_right"></div>