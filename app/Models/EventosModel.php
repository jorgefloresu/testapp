<?php

namespace App\Models;

use App\Libraries\DatabaseConnector;

class EventosModel {
    private $eventosCollection;

    function __construct() {
        $connection = new DatabaseConnector();
        $database = $connection->getDatabase();
        $this->eventosCollection = $database->Eventos;
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

    function getEvento($id) {
        try {
            $evento = $this->eventosCollection->findOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);

            return $evento;
        } catch(\MongoDB\Exception\RuntimeException $ex) {
            show_error('Error while fetching evento with ID: ' . $id . $ex->getMessage(), 500);
        }
    }

    function insertEvento($nombre, $telefono, $direccion, $confesiones) {
        try {
            $insertOneResult = $this->eventosCollection->insertOne([
                'nombre' => $nombre,
                'direccion' => $direccion,
                'confesiones' => $confesiones,
            ]);

            if($insertOneResult->getInsertedCount() == 1) {
                return true;
            }

            return false;
        } catch(\MongoDB\Exception\RuntimeException $ex) {
            show_error('Error while creating a evento: ' . $ex->getMessage(), 500);
        }
    }

    function updateEvento($id, $nombre, $direccion, $confesiones) {
        try {
            $result = $this->eventosCollection->updateOne(
                ['_id' => new \MongoDB\BSON\ObjectId($id)],
                ['$set' => [
                    'nombre' => $nombre,
                    'direccion' => $direccion,
                    'confesiones' => $confesiones
                ]]
            );

            if($result->getModifiedCount()) {
                return true;
            }

            return false;
        } catch(\MongoDB\Exception\RuntimeException $ex) {
            show_error('Error while updating a evento with ID: ' . $id . $ex->getMessage(), 500);
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
            show_error('Error while deleting a evento with ID: ' . $id . $ex->getMessage(), 500);
        }
    }
}