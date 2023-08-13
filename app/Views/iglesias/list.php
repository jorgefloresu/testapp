<?php if (! empty($iglesias) && is_array($iglesias)): ?>

<h1>Directorio de Iglesias</h1>

<table class="table table-striped table-borderless mt-4">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Teléfonos</th>
            <th>Confesiones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($iglesias as $iglesia): ?>
            <tr>
                <td>
                    <a href="<?= '/iglesias/' . $iglesia['iglesia_id'] ?>">
                        <?= esc($iglesia['nombre']) ?>
                    </a>
                </td>
                <td><?= esc($iglesia['direccion']) ?></td>
                <?php if (isset($iglesia['telefono']['fijo'])): ?>
                    <?php $fijo = json_decode(json_encode($iglesia['telefono']['fijo'], true)) ?>
                    <td><?php echo implode("<br>", $fijo) ?></td>
                <?php else: ?>
                    <td> - </td>
                <?php endif ?>
                <td><?= esc($iglesia['confesiones']) ?></td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a class="btn btn-primary btn-sm" href="<?= '/iglesias/edit/' . $iglesia['iglesia_id'] ?>"><i class="bi bi-pencil-square"></i></a>
                        <a class="btn btn-danger btn-sm" href="<?= '/iglesias/delete/' . $iglesia['iglesia_id'] ?>"><i class="bi bi-trash"></i></a>
                    </div>
                </td>
            </tr>
        <?php endforeach ?>
</table>

<?php else: ?>
<h2>No se han agregado Iglesias aun!</h3>
<?php endif ?>

<a href="iglesias/create">
<button class="btn btn-primary mt-4">Agregar Iglesia</button>
</a>