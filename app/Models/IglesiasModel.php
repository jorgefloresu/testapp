<?php

namespace App\Models;

use App\Libraries\DatabaseConnector;

class IglesiasModel {
    private $iglesiasCollection;
    private $eventosCollection;

    function __construct() {
        $connection = new DatabaseConnector();
        $database = $connection->getDatabase();
        $this->iglesiasCollection = $database->Iglesias;
        $this->eventosCollection = $database->Eventos;
    }

    function getIglesias($limit = 10) {
        try {
            $cursor = $this->iglesiasCollection->find([], ['limit' => $limit]);
            $iglesias = $cursor->toArray();

            return $iglesias;
        } catch(\MongoDB\Exception\RuntimeException $ex) {
            show_error('Error while fetching iglesias: ' . $ex->getMessage(), 500);
        }
    }

    function getEventos($limit = 10) {
        try {
            $cursor = $this->eventosCollection->find([], ['limit' => $limit]);
            $eventos = $cursor->toArray();

            return $eventos;
        } catch(\MongoDB\Exception\RuntimeException $ex) {
            show_error('Error while fetching eventos: ' . $ex->getMessage(), 500);
        }
    }

    function getIglesiaEventos($iglesia_id) {
        try {

            $horaArray = array('_id'=>'', 'hora'=>'');
            $initEventos = array(
                'tipo'=>'', 'Lunes'=>array(), 'Martes'=>array(), 'Miércoles'=>array(), 'Jueves'=>array(),
                'Viernes'=>array(), 'Sábado'=>array(), 'Domingo'=>array()
            );

            $cursor = $this->eventosCollection->find(['iglesia_id' => $iglesia_id]);
            $eventos = $cursor->toArray();

            if (count($eventos) == 0) {
                $iglesiaEventos[0] = $initEventos;
            } else {
                usort($eventos, array($this, "byTipo"));

                $tipo_antes = "";
                $i = 0;
                $p = 0;
                foreach ($eventos as $evento) {
                    if ($tipo_antes != $evento['tipo']) {
                        $iglesiaEventos[$i] = $initEventos;
                        $iglesiaEventos[$i]['tipo'] = $evento['tipo'];
                        $tipo_antes = $evento['tipo'];
                        $p = $i;
                        $i++;
                    }
                    if ( isset($evento['dia']) ) {
                        $hora = isset($evento['hora']) ? $evento['hora'] : "-";
                        $hora = ($hora == "-") ? "-": date('h:i a', strtotime($hora));
                        $hora = ltrim($hora, "0");
                        $comentario = isset($evento['comentario']) ? $evento['comentario'] : "";
                        $iglesiaEventos[$p][$evento['dia']][] = array('_id'=>$evento['_id'], 'hora'=>$hora, 'comentario'=>$comentario);
                    }
                }    
            }

            return $iglesiaEventos;
        } catch(\MongoDB\Exception\RuntimeException $ex) {
            show_error('Error while fetching eventos: ' . $ex->getMessage(), 500);
        }
    }

    function byTipo ($a, $b) {
        return ($a['tipo']< $b['tipo']);
    }

    function getIglesia($id) {
        try {
            //$iglesia = $this->iglesiasCollection->findOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);
            $iglesia = $this->iglesiasCollection->findOne(['iglesia_id' => $id]);

            return $iglesia;
        } catch(\MongoDB\Exception\RuntimeException $ex) {
            show_error('Error while fetching iglesia with ID: ' . $id . $ex->getMessage(), 500);
        }
    }

    function insertIglesia($iglesia_id, $nombre, $direccion, $confesiones, $telefono, $lat, $lng) {
        try {
            $insertOneResult = $this->iglesiasCollection->insertOne([
                'iglesia_id' => $iglesia_id,
                'nombre' => $nombre,
                'direccion' => $direccion,
                'confesiones' => $confesiones,
                'telefono' => $telefono,
                'ubicacion' => ['lat' => $lat, 'lng' => $lng],
            ]);

            if($insertOneResult->getInsertedCount() == 1) {
                return true;
            }

            return false;
        } catch(\MongoDB\Exception\RuntimeException $ex) {
            show_error('Error while creating a Iglesia: ' . $ex->getMessage(), 500);
        }
    }

    function getMaxIglesiaId () {
        try {
            $pipeline = [
                [
                    '$group' => [
                        '_id' => null, 
                        'max_id' => [
                            '$max' => '$iglesia_id'
                        ]
                    ]
                ],
                [
                    '$project' => ['_id' => 0]
                ]
            ];
            $cursor = $this->iglesiasCollection->aggregate($pipeline);
            $max = $cursor->toArray();
            $max_id = json_decode(json_encode($max),true);

            return $max_id[0]['max_id'];
        } catch(\MongoDB\Exception\RuntimeException $ex) {
            show_error('Error while calculate max on iglesia ' . $ex->getMessage(), 500);
        }

    }

    function insertEvento($iglesia_id, $dia, $hora, $tipo, $comentario='') {
        try {
            $record = [
                'dia' => $dia,
                'hora' => $hora,
                'tipo' => $tipo,
                'iglesia_id' => $iglesia_id,
            ];

            if ($comentario) {
                $record['comentario'] = $comentario;
            }

            $insertOneResult = $this->eventosCollection->insertOne($record);

            if($insertOneResult->getInsertedCount() == 1) {
                return true;
            }

            return false;
        } catch(\MongoDB\Exception\RuntimeException $ex) {
            show_error('Error while creating a Iglesia: ' . $ex->getMessage(), 500);
        }
    }

    function insertEventos($iglesia_id, $dia, $hora, $tipo) {
        try {
            $insertManyResult = $this->eventosCollection->insertMany([
                'dia' => $dia,
                'hora' => $hora,
                'tipo' => $tipo,
                'iglesia_id' => $iglesia_id,
            ]);

            if($insertOneResult->getInsertedCount() == 1) {
                return true;
            }

            return false;
        } catch(\MongoDB\Exception\RuntimeException $ex) {
            show_error('Error while creating a Iglesia: ' . $ex->getMessage(), 500);
        }
    }

    function updateIglesia($iglesia_id, $nombre, $direccion, $confesiones, $telefono, $lat, $lng) {
        try {
            // '_id' => new \MongoDB\BSON\ObjectId($id)
            $result = $this->iglesiasCollection->updateOne(
                ['iglesia_id' => $iglesia_id],
                ['$set' => [
                    'nombre' => $nombre,
                    'direccion' => $direccion,
                    'confesiones' => $confesiones,
                    'telefono.fijo' => $telefono,
                    'ubicacion.lat' => $lat,
                    'ubicacion.lng' => $lng,
                ]]
            );

            if($result->getModifiedCount()) {
                return true;
            }

            return false;
        } catch(\MongoDB\Exception\RuntimeException $ex) {
            show_error('Error while updating a iglesia with ID: ' . $iglesia_id . $ex->getMessage(), 500);
        }
    }

    function deleteIglesia($iglesia_id) {
        try {
            //['_id' => new \MongoDB\BSON\ObjectId($id)]
            $result = $this->iglesiasCollection->deleteOne(['iglesia_id' => $iglesia_id]);

            if($result->getDeletedCount() == 1) {
                return true;
            }

            return false;
        } catch(\MongoDB\Exception\RuntimeException $ex) {
            show_error('Error while deleting a iglesia with ID: ' . $iglesia_id . $ex->getMessage(), 500);
        }
    }

    function getEvento($id) {
        try {
            $evento = $this->eventosCollection->findOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);

            return $evento;
        } catch(\MongoDB\Exception\RuntimeException $ex) {
            show_error('Error while fetching evento with ID: ' . $id . $ex->getMessage(), 500);
        }
    }

    function existsEvento($iglesia_id, $dia, $hora, $tipo) {
        try {
            $evento = $this->eventosCollection->findOne([
                'iglesia_id' => $iglesia_id, 
                'dia' => $dia,
                'hora' => $hora,
                'tipo' => $tipo
            ]);

            return $evento;
        } catch(\MongoDB\Exception\RuntimeException $ex) {
            show_error('Error while fetching evento with ID: ' . $iglesia_id . $ex->getMessage(), 500);
        }

    }

    function deleteEvento($id) {
        try {
            $result = $this->eventosCollection->deleteOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);

            if($result->getDeletedCount() == 1) {
                return true;
            }

            return false;
        } catch(\MongoDB\Exception\RuntimeException $ex) {
            show_error('Error while deleting a iglesia with ID: ' . $id . $ex->getMessage(), 500);
        }
    }

    function textSearch($txt) {
        try {
            $criteria = [[
                '$search' => [
                    'index' => 'nombreIndex',
                    'text' => [
                        'path' => ['wildcard' => '*'],
                        'query' => $txt
                    ]
                ]
            ]];
            $cursor = $this->iglesiasCollection->aggregate($criteria);
            //$sorted = $cursor->score(
            //    ['score' => ['$meta' => 'textScore']]
            //);
            $similar = $cursor->toArray();
            return $similar;
        } catch(\MongoDB\Exception\RuntimeException $ex) {
            show_error('Error while searching iglesias: ' . $ex->getMessage(), 500);
        }
    }

}