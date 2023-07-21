<h2><?= esc($header) ?></h2>

<?= session()->getFlashdata('error') ?>
<?= service('validation')->listErrors() ?>

<form action="/iglesias/create" method="post">
    <?= csrf_field() ?>

    <div class="mt-3">
        <label class="form-label" for="nombre">Dia</label>
        <input class="form-control" type="input" name="nombre" />
    </div>
    <div class="mt-3">
        <label class="form-label" for="nombre">Hora</label>
        <input class="form-control" type="input" name="nombre" />
    </div>
    <div class="mt-3">
        <label class="form-label" for="nombre">Tipo</label>
        <input class="form-control" type="input" name="nombre" />
    </div>
</form>
<div class="mt-4">
    <table class="table mb-0">
        <thead>
            <tr>
            <?php foreach ($eventos[0] as $key => $value): ?>
                <?php if ($key == 'tipo'): ?>
                    <th><?= esc($key) ?></th>
                <?php else: ?>
                    <th class="text-end"><?= esc($key) ?></th>
                <?php endif ?>
            <?php endforeach ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($eventos as $evento): ?>
            <tr>
                <?php foreach ($evento as $key => $horas): ?>
                    <?php if ($key == 'tipo'): ?>
                        <td><?= esc($horas) ?></td>
                    <?php else: ?>
                        <td class="text-end"><?php echo implode("<br>", $horas) ?></td>
                    <!-- <?php //foreach ($horas as $hora): ?>
                        <td><?//= esc($hora) ?></td>
                    <?php //endforeach ?> -->
                    <?php endif ?>
                <?php endforeach ?>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <!-- <p id="reading-progress">Your reading progress:</p>
    <div class="progress">
        <div
            class="progress-bar"
            role="progressbar"
            style="width: <?//= $iglesia['progress'] ?>%"
            aria-valuenow="<?//= $iglesia['progress'] ?>"
            aria-valuemin="0"
            aria-valuemax="100"
            aria-labelledby="reading-progress"
        ><?//= $iglesia['progress'] ?>%</div>
    </div> -->

    <a href=".." class="btn btn-link mt-3">Go back</a>
</div>