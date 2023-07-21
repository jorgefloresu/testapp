<h2><?= esc($header) ?></h2>

<?= session()->getFlashdata('error') ?>
<?= service('validation')->listErrors() ?>

<form action="<?= '/iglesias/edit/' . $iglesia['iglesia_id'] ?>" method="post" autocomplete="off">
    <?= csrf_field() ?>

    <div class="form-floating mb-3">
        <input value="<?= $iglesia['nombre'] ?>" class="form-control" type="text" name="nombre" placeholder="Nombre" required>
        <label for="nombre">Nombre</label>
    </div>

    <div class="form-floating mb-3">
        <input value="<?= $iglesia['direccion'] ?>" class="form-control" type="text" name="direccion" placeholder="Direccion" required>
        <label for="direccion">Dirección</label>
    </div>

    <div class="form-floating mb-3">
        <input value="<?= $iglesia['confesiones'] ?>" class="form-control" type="text" name="confesiones" placeholder="Confesiones">
        <label for="confesiones">Confesiones</label>
    </div>

    <div class="mb-3">
        <label class="form-label" for="telefono">Teléfono</label>
        <?php $fijo = json_decode(json_encode($iglesia['telefono']['fijo'], true)) ?>
        <input name='telefono' value='<?= implode(',', $fijo) ?>' class="form-control">
    </div>

    <div class="mb-3">
        <label for="lat" class="form-label">Ubicación en Google Maps</label>
        <div class="input-group">
            <span class="input-group-text" id="ubicacion">Latitud y longitud</span>
            <input type="text" aria-label="latitud" class="form-control" name="lat" value="<?= $iglesia['ubicacion']['lat'] ?>">
            <input type="text" aria-label="longitud" class="form-control" name="lng" value="<?= $iglesia['ubicacion']['lng'] ?>">
        </div>
    </div>

    <button class="btn btn-primary" name="submit">Guardar Iglesia</button>

</form>

<script src="https://unpkg.com/@yaireo/tagify"></script>
<script src="https://unpkg.com/@yaireo/tagify@3.1.0/dist/tagify.polyfills.min.js"></script>

<script>
    // The DOM element you wish to replace with Tagify
    var input = document.querySelector('input[name=telefono]');

    // initialize Tagify on the above input node reference
    new Tagify(input)
</script>
