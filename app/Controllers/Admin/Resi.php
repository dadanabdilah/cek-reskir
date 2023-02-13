<?php

namespace App\Controllers\Admin;

use CodeIgniter\RESTful\ResourceController;

use App\Models\ResiModel;
use App\Models\ResiActivityModel;
use App\Models\ResiNotifModel;
use App\Models\ProdukModel;

class Resi extends ResourceController
{
    protected $modelName = 'App\Models\ResiModel';

    public function __construct()
    {
        helper(['my_helper']);
        $this->Resi = new ResiModel;
        $this->Produk = new ProdukModel;
        $this->ResiAct = new ResiActivityModel;
        $this->ResiNotif = new ResiNotifModel;
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $data = [
            'Resi' => $this->model->select('tbl_resi.*, tbl_produk.kode_barang, tbl_produk.nama_barang,')->join('tbl_produk', 'tbl_produk.kode_barang = tbl_resi.kode_barang')->findAll(),
            'title' =>"Data Resi",
            'sub_title' =>"List Data Resi",
        ];

        return view('resi/index', $data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $data = [
            'Resi' => $this->model->where('resi_id', $id)->first(),
            'Resis' => $this->model->select('tbl_resi.*, tbl_resi_activity.*, tbl_produk.kode_barang, tbl_produk.nama_barang,')
                                    ->join('tbl_resi_activity', 'tbl_resi_activity.resi_id = tbl_resi.resi_id')
                                    ->join('tbl_produk', 'tbl_produk.kode_barang = tbl_resi.kode_barang')
                                    ->where('tbl_resi.resi_id',$id)
                                    ->where('tbl_resi_activity.resi_id',$id)
                                    ->orderBy('date DESC')->findAll(),
            'title' => "Data Resi",
            'sub_title' => "Detail Resi",
        ];
        return view('resi/show', $data);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        $data = [
            'Produk' => $this->Produk->findAll(),
            'title' => "Data Resi",
            'sub_title' => "Tambah Data Resi",
        ];
        return view('resi/new', $data);
    }
    
    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        
        if (!$this->validate([
            'kode_barang' => 'required',
            'nama_customer' => 'required',
            'no_telp' => 'required',
            'no_resi' => 'required',
            'ekspedisi' => 'required',
            'harga' => 'required',
            'tanggal_pencatatan' => 'required',
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            
            return redirect()->back()->withInput();
        }
        
        $request = [
            'kode_barang' => $this->request->getPost('kode_barang'),
            'nama_customer' => $this->request->getPost('nama_customer'),
            'no_telp' => $this->request->getPost('no_telp'),
            'no_resi'     => $this->request->getPost('no_resi'),
            'ekspedisi'     => $this->request->getPost('ekspedisi'),
            'harga'     => $this->request->getPost('harga'),
            'tanggal_pencatatan'  => $this->request->getPost('tanggal_pencatatan'),
            'admin_id'    => session('admin_id'),
        ];

        $result = $this->model->save($request);
        
        if ($result) {

            $message = "Hallo Kak, berikut rincian pembelian di *Dewa Spray* yaa\r\n";
            $message .= "\r\n*-Nama : " . $this->request->getPost('nama_customer');
            // $message .= "\r\nAlamat : yyyyy";
            $message .= "\r\nPembelian : " . $this->Produk->where('kode_barang', $this->request->getPost('kode_barang'))->first()->nama_barang;
            $message .= "\r\nNo resi : " . $this->request->getPost('no_resi');
            $message .= "\r\nUpdate Resi: Kurir telah pick up paket*";
            $message .= "\r\nHappy Shoping!\r\n_Ini adalah pesan otomatis, tolong jangan balas pesan ini, jika ada pertanyaan langsung tanyakan ke admin yaa :))_";
            
            sendWa($this->request->getPost('no_telp'), $message);

            session()->setFlashdata('message', 'Tambah Data Berhasil');
        } else {
            session()->setFlashdata('error', 'Tambah Data Tidak Berhasil');
        }
        
        return redirect()->to('admin/resi');
    }
    
    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        $data = [
            'Produk' => $this->Produk->findAll(),
            'Resi' => $this->model->where('resi_id', $id)->first(),
            'title' => "Data Resi",
            'sub_title' => "Edit Data Resi",
        ];
        return view('resi/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        if (!$this->validate([
            'kode_barang' => 'required',
            'nama_customer' => 'required',
            'no_telp' => 'required',
            'no_resi' => 'required',
            'ekspedisi' => 'required',
            'harga' => 'required',
            'tanggal_pencatatan' => 'required',
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());

            return redirect()->back()->withInput();
        }

        $request = [
            'kode_barang' => $this->request->getPost('kode_barang'),
            'nama_customer' => $this->request->getPost('nama_customer'),
            'no_telp' => $this->request->getPost('no_telp'),
            'no_resi'     => $this->request->getPost('no_resi'),
            'ekspedisi'     => $this->request->getPost('ekspedisi'),
            'harga'     => $this->request->getPost('harga'),
            'tanggal_pencatatan'     => $this->request->getPost('tanggal_pencatatan'),
        ];

        $result = $this->model->update($id, $request);

        if ($result) {
            session()->setFlashdata('message', 'Update Data Berhasil');
        } else {
            session()->setFlashdata('error', 'Update Data Tidak Berhasil');
        }

        return redirect()->to('admin/resi');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = 4)
    {
        if ($this->model->delete($id)) {
            $act = $this->ResiAct->where('resi_id', $id)->countAllResults();
            if($act > 0 ){
                $this->ResiAct->where('resi_id', $id)->delete();
            }
            
            $rn = $this->ResiNotif->where('resi_id', $id)->countAllResults();
            if($rn > 0){
                $this->ResiNotif->where('resi_id', $id)->delete();
            }

            session()->setFlashdata('message', 'Hapus Data Berhasil');
        } else {
            session()->setFlashdata('error', 'Hapus Data Tidak Berhasil');
        }

        return redirect()->to('admin/resi');
    }
}
