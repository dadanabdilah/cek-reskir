<?php

namespace App\Controllers\Admin;

use CodeIgniter\RESTful\ResourceController;

use App\Models\ProdukModel;

class Variasi extends ResourceController
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
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        
        $data = $this->request->getpost();
        
        $db = \config\Database::connect();

        $cekDB = $db->table('tbl_produk_variasi')->where(['produk_variasi_id' => $data['produk_variasi_id']])->get()->getRow();
        if ($cekDB != null){
            $dataDB = array(
                'nama_variasi'  => $data['nama_variasi'],
                'harga'         => $data['harga']
            );
            $result = $db->table('tbl_produk_variasi')->update($dataDB, ['produk_variasi_id' => $cekDB->produk_variasi_id]);
        }else{
            $dataDB = array(
                'kode_barang'   => $data['kode_barang'],
                'nama_variasi'  => $data['nama_variasi'],
                'harga'         => $data['harga']
            );
            $result = $db->table('tbl_produk_variasi')->insert($dataDB);
        }

        if ($result){
            echo "Data berhasil disimpan";
        }else{
            echo "Data gagal disimpan";
        }
    }
    
    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        $data = [
            'Produk' => $this->model->where('id', $id)->first(),
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

        return redirect()->to('admin/produk');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $db = \config\Database::connect();
        $cekDB = $db->table('tbl_produk_variasi')->join('tbl_produk', 'tbl_produk.kode_barang = tbl_produk_variasi.kode_barang')->where('produk_variasi_id', $id)->get()->getRow();
        if ($db->table('tbl_produk_variasi')->delete(['produk_variasi_id' => $id])) {
            session()->setFlashdata('message', 'Hapus Data Berhasil');
        } else {
            session()->setFlashdata('error', 'Hapus Data Tidak Berhasil');
        }

        return redirect()->to('admin/produk/'.$cekDB->id.'/edit');
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
