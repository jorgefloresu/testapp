<h2><?= esc($header) ?></h2>

<?= session()->getFlashdata('error') ?>
<?= service('validation')->listErrors() ?>

<form action="/iglesias/create" method="post">
<!--<?//= form_open('/iglesias/create', ['csrf_id' => 'csrf']) ?>-->    

    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf"/>

    <div class="mt-3">
        <label class="form-label" for="iglesia_id">ID</label>
        <input class="form-control" type="input" name="iglesia_id" value="<?= esc($newId) ?>" id="iglesia_id" readonly/>
    </div>

    <div class="mt-3">
        <label class="form-label" for="nombre">Nombre</label>
        <input class="form-control" type="input" name="nombre" id="nombre" />
    </div>
    <div class="mt-2" id="collapsible" style="display:none">
        <p>
            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                Consultar nombres similares
            </button>
        </p>
        <div class="collapse" id="collapseExample">
            <!--<div class="card card-body" id="collapseText">

            </div>-->
            Espere...
        </div>
    </div>
    <div class="mt-3">
        <label class="form-label" for="direccion">Direccion</label>
        <input class="form-control" type="input" name="direccion" id="direccion" />
    </div>

    <div class="mt-3">
        <label class="form-label" for="confesiones">Confesiones</label>
        <input class="form-control" type="input" name="confesiones" id="confesiones" />
    </div>

    <div class="mt-3">
        <label class="form-label" for="telefono">Telefono</label>
        <input class="form-control" type="input" name="telefono" placeholder="Agrega varios números separados por una coma" id="telefono" />
    </div>

    <div class="mt-3">
        <label for="lat" class="form-label">Ubicación en Google Maps</label>
        <div class="input-group">
            <span class="input-group-text" id="ubicacion">Latitud y longitud</span>
            <input type="text" aria-label="latitud" class="form-control" name="lat">
            <input type="text" aria-label="longitud" class="form-control" name="lng">
        </div>
    </div>

    <input class="btn btn-success mt-4" type="submit" name="submit" value="Agregar Iglesia" />

</form>

<script src="https://unpkg.com/@yaireo/tagify"></script>
<script src="https://unpkg.com/@yaireo/tagify@3.1.0/dist/tagify.polyfills.min.js"></script>

<script>
// The DOM element you wish to replace with Tagify
var input = document.querySelector('input[name=telefono]');

// initialize Tagify on the above input node reference
new Tagify(input)

</script>


<script>

var myCollapsible = document.getElementById('collapseExample');
var collapsible = document.getElementById('collapsible');

var xmlhttp = new XMLHttpRequest();
var str = document.getElementById("nombre");
var csrf = document.getElementById("csrf");
var csrfHash = csrf.value;
var csrfName = csrf.name;

str.addEventListener('keyup', function(){
    if (str.value == '') {
        hideToggle("collapsible", true);
    } else {
        hideToggle("collapsible", false);
    }   
})

myCollapsible.addEventListener('hidden.bs.collapse', function () {
    document.getElementById("collapseExample").innerHTML = "Espere...";
})

myCollapsible.addEventListener('shown.bs.collapse', function () {
    xmlhttp.open("POST", "checkNombre");
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("nombre=" + str.value + "&" + csrfName + "=" + csrfHash);
});

xmlhttp.onload = function() {
    let resp = JSON.parse(this.responseText);
    let str = '';
    if (resp.resp.length > 0) {
        //resp.resp.forEach(item => str += item.nombre);
        resp.resp.forEach(item => str += '<span class="badge bg-secondary" style="margin-right:4px">'+item.nombre+'</span>');
        document.getElementById("collapseExample").innerHTML = str;
    } else {
        document.getElementById("collapseExample").innerHTML = "No hay coincidencias";
    }
    document.getElementById("csrf").value = resp.csrf_hash;
    csrfHash = csrf.value;
    console.log(csrfHash);
}

function hideToggle(elem, hide) {
  var x = document.getElementById(elem);
  if (hide) {
    x.style.display = "none";
  } else {
    x.style.display = "block";
    console.log(csrfHash);
  }
}


</script>