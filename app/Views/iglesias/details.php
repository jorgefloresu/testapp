<h2><?= esc($header) ?></h2>
<?php if (isset($lat)): ?>
    <p><a href="map/<?= esc($iglesia_id) ?>/<?= esc($header) ?>/<?= esc($lat) ?>/<?= esc($lng) ?>">Mapa</a></p>
<?php endif ?>    
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
            <?php if (count($eventos) == 0): ?>
                <tr><td colspan="8" class="text-center">Sin horario registrado</td></tr>
            <?php else: ?>
                <?php foreach ($eventos as $evento): ?>
                <tr>
                    <?php foreach ($evento as $key => $horas): ?>
                        <?php if ($key == 'tipo'): ?>
                            <td><?= esc($horas) ?></td>
                        <?php else: ?>
                        <!--<td class="text-end"><?php //echo implode("<br>", $horas) ?></td> -->
                            <td class="text-end">
                            <?php foreach ($horas as $hora): ?>
                                <span class="d-block">
                                    <?= esc($hora['hora']) ?>
                                    <a href="deleteHorario/<?= esc($hora['_id']) ?>"><i class='bi-trash'></i></a><br>
                                    <span class="badge bg-light text-dark"><?= esc($hora['comentario']) ?></span>
                                </span>
                            <?php endforeach ?>
                            </td>
                        <?php endif ?>
                    <?php endforeach ?>
                </tr>
                <?php endforeach ?>
            <?php endif ?>
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

    <div class="mt-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addHorarioModal">Agregar una hora a varios días</button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addHorarioXdia">Agregar varias hora a un día</button>
</div>

<a href=".." class="btn btn-link mt-3">Regresar</a>

<div class="modal fade" id="addHorarioModal" tabindex="-1" aria-labelledby="addHorarioModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addHorarioModalLabel">Agregar horario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/iglesias/addHorario" class="needs-validation" method="post" novalidate>
            <?= csrf_field() ?>
            <input type="hidden" name="iglesia_id" value="<?= esc($iglesia_id)?>">
            <div class="mb-3">
                <select class="form-select" name="tipo" aria-label="Seleccion tipo" aria-describedby="misa-selected" required>
                    <option selected disabled value="">Selecciona tipo de evento</option>
                    <option value="Misa">Misa</option>
                    <option value="Hora Santa">Hora Santa</option>
                    <option value="Exposición del Santísimo">Exposición del Santísimo</option>
                </select>
                <div id="misa-selected" class="invalid-feedback">
                    Favor selecciona un evento
                </div>
            </div>            

            <div class="mb-3">
                <p>Dia del evento</p>
                <div class="card">
                    <div class="card-body">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="dia[]" value="Lunes" id="lunesCheck">
                            <label class="form-check-label" for="lunesCheck">
                                Lunes
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="dia[]" value="Martes" id="martesCheck">
                            <label class="form-check-label" for="martesCheck">
                                Martes
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="dia[]" value="Miércoles" id="miercolesCheck">
                            <label class="form-check-label" for="miercolesCheck">
                                Miércoles
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="dia[]" value="Jueves" id="juevesCheck">
                            <label class="form-check-label" for="juevesCheck">
                                Jueves
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="dia[]" value="Viernes" id="viernesCheck">
                            <label class="form-check-label" for="viernesCheck">
                                Viernes
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="dia[]" value="Sábado" id="sabadoCheck">
                            <label class="form-check-label" for="sabadoCheck">
                                Sábado
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="dia[]" value="Domingo" id="domingoCheck">
                            <label class="form-check-label" for="domingoCheck">
                                Domingo
                            </label>
                        </div>
                        <!-- <select class="form-select" name="dia" aria-label="Seleccion tipo" aria-describedby="dia-selected" required>
                            <option selected disabled value="">Selecciona el dia del evento</option>
                            <option value="Lunes">Lunes</option>
                            <option value="Martes">Martes</option>
                            <option value="Miércoles">Miércoles</option>
                            <option value="Jueves">Jueves</option>
                            <option value="Viernes">Viernes</option>
                            <option value="Sábado">Sábado</option>
                            <option value="Domingo">Domingo</option>
                        </select>
                        <div id="dia-selected" class="invalid-feedback">
                            Favor selecciona un día
                        </div> -->
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="horasList" class="form-label">Hora (en formato de 24hrs.) </label>
                <input class="form-control" list="horalistOptions" id="horasList" name="hora" 
                    placeholder="Digita para buscar..." aria-describedby="hora-selected" onblur="validateHhMm(this);" required>
                <datalist id="horalistOptions">
                    <option value="6:00">
                    <option value="7:00">
                    <option value="8:00">
                    <option value="9:00">
                    <option value="10:00">
                    <option value="11:00">
                    <option value="12:00">
                    <option value="13:00">
                    <option value="14:00">
                    <option value="15:00">
                    <option value="16:00">
                    <option value="17:00">
                    <option value="18:00">
                    <option value="19:00">
                    <option value="20:00">
                </datalist>
                <div id="hora-selected" class="invalid-feedback">
                    Favor agregar una hora
                </div>
            
            </div>

            <div class="mb-3">
                <label class="form-label" for="comentario">Comentario (opcional)</label>
                <input class="form-control" type="input" name="comentario" placeholder="Escribe algún detalle sobre esta hora del evento" />
            </div>

            <button type="submit" class="btn btn-primary">Agregar</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="addHorarioXdia" tabindex="-1" aria-labelledby="addHorarioXdiaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addHorarioXdiaLabel">Agregar horario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/iglesias/addHorarioXdia" class="needs-validation" method="post" novalidate>
            <?= csrf_field() ?>
            <input type="hidden" name="iglesia_id" value="<?= esc($iglesia_id)?>">
            <div class="mb-3">
                <select class="form-select" name="tipo" aria-label="Seleccion tipo" aria-describedby="misa-selected" required>
                    <option selected disabled value="">Selecciona tipo de evento</option>
                    <option value="Misa">Misa</option>
                    <option value="Hora Santa">Hora Santa</option>
                    <option value="Exposición del Santísimo">Exposición del Santísimo</option>
                </select>
                <div id="misa-selected" class="invalid-feedback">
                    Favor selecciona un evento
                </div>
            </div>            

            <div class="mb-3">
                <p>Dia del evento</p>
                <select class="form-select" name="dia" aria-label="Seleccion dia" aria-describedby="misa-selected" required>
                    <option selected disabled value="">Selecciona dia del evento</option>
                    <option value="Lunes">Lunes</option>
                    <option value="Martes">Martes</option>
                    <option value="Miércoles">Miércoles</option>
                    <option value="Jueves">Jueves</option>
                    <option value="Viernes">Viernes</option>
                    <option value="Sábado">Sábado</option>
                    <option value="Domingo">Domingo</option>
                </select>

            </div>

            <div class="mb-3">
                <label class="form-label" for="horas">Hora (en formato de 24hrs.) </label>
                <input class="form-control" type="input" name="horaxdia" placeholder="Agrega los horarios separados por una coma" id="horas" />
            
                <div id="hora-selected" class="invalid-feedback">
                    Favor agregar una hora
                </div>
            
            </div>

            <div class="mb-3">
                <label class="form-label" for="comentario">Comentario (opcional)</label>
                <input class="form-control" type="input" name="comentario" placeholder="Escribe algún detalle sobre esta hora del evento" />
            </div>

            <button type="submit" class="btn btn-primary">Agregar</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>

</div>

<script src="https://unpkg.com/@yaireo/tagify"></script>
<script src="https://unpkg.com/@yaireo/tagify@3.1.0/dist/tagify.polyfills.min.js"></script>

<script>
// The DOM element you wish to replace with Tagify
var input = document.querySelector('input[name=horaxdia]');

// initialize Tagify on the above input node reference
new Tagify(input)

</script>

<script>

    (function () {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation');
    var field_hora = document.getElementById("horasList");

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                console.log(field_hora.style.backgroundColor);
                if (!form.checkValidity() || field_hora.style.backgroundColor == 'red') {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()

    function validateHhMm(inputField) {
        var isValid = /^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/.test(inputField.value);
        if (isValid) {
            inputField.style.backgroundColor = 'green';
        } else {
            inputField.style.backgroundColor = 'red';
        }
        return isValid;
    }


</script>