<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\ProdukModel;

class Produk extends ResourceController
{
    protected $modelName = 'App\Models\ProdukModel';

    public function __construct()
    {
        $this->Produk = new ProdukModel;
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $data = [
            'Produk' => $this->model->findAll(),
            'title' =>"Data Produk",
            'sub_title' =>"List Data Produk",
        ];

        return view('produk/index', $data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        $data = [
            'title' => "Data Produk",
            'sub_title' => "Tambah Data Produk",
        ];
        return view('produk/new', $data);
    }
    
    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        
        if (!$this->validate([
            'kelompok_barang' => 'required',
            'nama_barang' => 'required',
            'berat' => 'required',
            'harga' => 'required',
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            
            return redirect()->back()->withInput();
        }
        
        $request = [
            'kode_barang' => $this->generateCode(),
            'kelompok_barang' => $this->request->getPost('kelompok_barang'),
            'nama_barang' => $this->request->getPost('nama_barang'),
            'berat'     => $this->request->getPost('berat'),
            'harga'     => $this->request->getPost('harga'),
            'admin_id'    => 1,
        ];

        $result = $this->model->save($request);
        
        if ($result) {
            session()->setFlashdata('message', 'Tambah Data Berhasil');
        } else {
            session()->setFlashdata('error', 'Tambah Data Tidak Berhasil');
        }
        
        return redirect()->to('produk');
    }
    
    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        $data = [
            'Produk' => $this->model->first($id),
            'title' => "Data Produk",
            'sub_title' => "Edit Data Produk",
        ];
        return view('produk/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        if (!$this->validate([
            'kelompok_barang' => 'required',
            'nama_barang' => 'required',
            'berat' => 'required',
            'harga' => 'required',
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());

            return redirect()->back()->withInput();
        }

        $request = [
            'kelompok_barang' => $this->request->getPost('kelompok_barang'),
            'nama_barang' => $this->request->getPost('nama_barang'),
            'berat'     => $this->request->getPost('berat'),
            'harga'     => $this->request->getPost('harga'),
        ];

        $result = $this->model->update($id, $request);

        if ($result) {
            session()->setFlashdata('message', 'Update Data Berhasil');
        } else {
            session()->setFlashdata('error', 'Update Data Tidak Berhasil');
        }

        return redirect()->to('produk');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        if ($this->model->delete($id)) {
            session()->setFlashdata('message', 'Hapus Data Berhasil');
        } else {
            session()->setFlashdata('error', 'Hapus Data Tidak Berhasil');
        }

        return redirect()->to('produk');
    }


    public function generateCode()
    {
        $count = $this->model->countAll();
        $data = $this->model->select('kode_barang')->orderBy('id DESC')->limit('1')->first();

        if ($count > 0) {
            $code = substr($data->kode_barang, 4);
            $code = $code + 1;
            $code = 'BRG' . sprintf("%04s", $code);
        } else {
            $code = 'BRG0001';
        }

        return $code;
    }
}
