<!DOCTYPE html>
<!--
 @license
 Copyright 2019 Google LLC. All Rights Reserved.
 SPDX-License-Identifier: Apache-2.0
-->
<html>
  <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Load Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link href="https://unpkg.com/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
        <title>Ubicación en Mapa</title>
        <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>


    <style>
            #map {
                height: 400px; /* The height is 400 pixels */
                width: 100%; /* The width is the width of the web page */
            }
    </style>
    <script>
        // Initialize and add the map
        function initMap() {
            //const position = { lat: 13.715100902455903, lng: -89.17705078711893 };
            const position = { lat: <?= esc($lat) ?>, lng: <?= esc($lng) ?>};

            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 12,
                center: position,
                mapId: "MAP_ID",
            });

            new google.maps.Marker({
                position: position,
                map,
                title: "<?= esc($nombre) ?>",
            });

        }

        window.initMap = initMap;
    </script>
  </head>
  <body>
    <div class="container mt-5">
        <h3><?= esc($nombre) ?></h3>

        <!--The div element for the map -->
        <div id="map"></div>
        <p><a href="/iglesias/<?= esc($iglesia_id) ?>" class="btn btn-link mt-3">Regresar</a></p>
    </div>
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyApVFNwgp0xtDpPZsJ6jiJC9ZQ2JAJBMKo&callback=initMap&v=weekly"
      defer>
    </script>
  </body>
</html>