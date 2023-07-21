<?php

namespace App\Controllers;

//helper('inflector');

use App\Models\IglesiasModel;

class Iglesias extends BaseController
{
    protected $helpers = ['inflector', 'form', 'url'];

    public function index()
    {
        $model = model(IglesiasModel::class);

        $data = [
            'iglesias' => $model->getIglesias(),
        ];
        echo view('templates/header', $data);
        echo view('iglesias/list', $data);
        echo view('templates/footer', $data);
    }

    public function details($segment = null)
    {
        $model = model(IglesiasModel::class);

        $data['eventos'] = $model->getIglesiaEventos($segment);
        if (empty($data['eventos'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find iglesia with ID: ' . $segment);
        }

        $iglesia = $model->getIglesia($segment);
        $data['header'] = $iglesia['nombre'];
        if (isset($iglesia['ubicacion'])) {
            $data['lat'] = $iglesia['ubicacion']['lat'];
            $data['lng'] = $iglesia['ubicacion']['lng'];    
        }
        $data['iglesia_id'] = $segment;
        //$data['book']['progress'] = round($data['book']['pagesRead'] / $data['book']['pages'] * 100, 2);
    
        echo view('templates/header', $data);
        echo view('iglesias/details', $data);
        echo view('templates/footer', $data);
    }

    public function create()
    {
        $model = model(IglesiasModel::class);

        if ($this->request->getMethod() === 'post' && $this->validate([
            'nombre' => 'required|min_length[1]|max_length[255]',
            'direccion' => 'required|min_length[1]|max_length[255]',
            'confesiones' => 'max_length[255]', //'required|is_natural_no_zero',
        ])) {

            $arrayTelefono = $this->M_Array($this->request->getPost('telefono'));
            $fullArray = array('fijo' => $arrayTelefono);

            $model->insertIglesia(
                $this->request->getPost('iglesia_id'),
                $this->request->getPost('nombre'),
                $this->request->getPost('direccion'),
                $this->request->getPost('confesiones'),
                $fullArray,
                $this->request->getPost('lat'),
                $this->request->getPost('lng'),
            );

            return redirect()->to('iglesias');
        } else {
            $newIglesiaId = sprintf('%07d', $model->getMaxIglesiaId() + 1);
            echo view('templates/header');
            echo view('iglesias/create', ['header' => 'Agregar una Iglesia', 'newId' => $newIglesiaId]);
            echo view('templates/footer');
        }
    }

    function checkNombre() {
        $security = \Config\Services::security();

        $model = model(IglesiasModel::class);
        $data['resp'] = $model->textSearch($this->request->getPost('nombre'));

        $data['csrf_hash'] = $security->generateHash();
        return $this->response->setJSON($data);
    }

    public function edit($segment = null)
    {
        $model = model(IglesiasModel::class);

        $data['iglesia'] = $model->getIglesia($segment);

        if (empty($data['iglesia'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find iglesia with ID: ' . $segment);
        }

        $data['header'] = $data['iglesia']['nombre'];

        if ($this->request->getMethod() === 'post' && $this->validate([
            'nombre' => 'required|min_length[1]|max_length[255]',
            'direccion' => 'required|min_length[1]|max_length[255]',
            'confesiones' => 'max_length[255]', //'required|is_natural',
        ])) {
            
            $arrayTelefono = $this->M_Array($this->request->getPost('telefono'));

            $model->updateIglesia(
                $data['iglesia']['iglesia_id'],
                $this->request->getPost('nombre'),
                $this->request->getPost('direccion'),
                $this->request->getPost('confesiones'),
                $arrayTelefono,
                $this->request->getPost('lat'),
                $this->request->getPost('lng'),
            );

           return redirect()->to('iglesias');
        } else {
            echo view('templates/header', $data);
            echo view('iglesias/edit', $data);
            echo view('templates/footer', $data);
        }
    }

    public function delete($segment = null) {
        if (!empty($segment) && $this->request->getMethod() == 'get') {
            $model = model(IglesiasModel::class);
            $model->deleteIglesia($segment);
        }

        return redirect()->to('iglesias');
    }

    public function addHorario() {

        $model = model(IglesiasModel::class);
        //$validation = \Config\Services::validation();
        //$validation->setRules([
        //    'dia.*' => 'required',
        //]);
        //if (! $validation->run([$this->request->getPost('dia')])) {
        //    echo "handle validation errors";
        //}


        if ($this->request->getMethod() === 'post' && $this->validate([
            'tipo' => 'required|min_length[1]|max_length[255]',
            'hora' => 'min_length[4]|max_length[255]', //'required|is_natural_no_zero',
            'dia.*' => 'required',
        ])) {
             $iglesia_id = $this->request->getPost('iglesia_id');
             $hora = $this->request->getPost('hora');
             $tipo = $this->request->getPost('tipo');
             $comentario = $this->request->getPost('comentario');

             foreach ($this->request->getPost('dia') as $dia) {
                $evt = $model->existsEvento($iglesia_id, $dia, $hora, $tipo);
                if (empty($evt)) {
                    $model->insertEvento($iglesia_id, $dia, $hora, $tipo, $comentario);    
                }
            } 
        }
            
        $this->details($this->request->getPost('iglesia_id'));
    }

    public function addHorarioXdia() {

        $model = model(IglesiasModel::class);

        if ($this->request->getMethod() === 'post' && $this->validate([
            'tipo' => 'required|min_length[1]|max_length[255]',
            'horaxdia' => 'required', //'required|is_natural_no_zero',
        ])) {

            $arrayHoras = $this->M_Array($this->request->getPost('horaxdia'));

             $iglesia_id = $this->request->getPost('iglesia_id');
             $dia = $this->request->getPost('dia');
             $tipo = $this->request->getPost('tipo');
             $comentario = $this->request->getPost('comentario');

             foreach ($arrayHoras as $hora) {
                $evt = $model->existsEvento($iglesia_id, $dia, $hora, $tipo);
                if (empty($evt)) {
                    $model->insertEvento($iglesia_id, $dia, $hora, $tipo, $comentario);    
                }
            } 
        }

        return redirect()->to('iglesias/'.$this->request->getPost('iglesia_id'));
            
    }

    public function deleteHorario($segment = null) {
        if (!empty($segment) && $this->request->getMethod() == 'get') {
            $model = model(IglesiasModel::class);
            $evento = $model->getEvento($segment);
            $model->deleteEvento($segment);
        }

        return redirect()->to('iglesias/'.$evento['iglesia_id']);

    }

    public function M_Array($postArray) {
        $json = json_decode($postArray, true);
        foreach ($json as $elem) { 
            $newArray[] = $elem['value']; 
        }

        return $newArray;
    }

    public function map($iglesia_id, $nombre, $lat="", $lng="") {
        $data = [
            'iglesia_id' => $iglesia_id,
            'nombre' => $nombre,
            'lat' => $lat,
            'lng' => $lng,
        ];
        echo view('iglesias/map', $data);

    }
}
