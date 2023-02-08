<?php

namespace App\Controllers\Admin;

use CodeIgniter\RESTful\ResourceController;

use App\Models\AdminModel;

class Admin extends ResourceController
{
    protected $modelName = 'App\Models\AdminModel';

    public function __construct()
    {
        $this->Admin = new AdminModel;
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $data = [
            'Admin' => $this->model->findAll(),
            'title' =>"Data Admin",
            'sub_title' =>"List Data Admin",
        ];

        return view('admin/index', $data);
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
            'title' => "Data Admin",
            'sub_title' => "Tambah Data Admin",
        ];
        return view('admin/new', $data);
    }
    
    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        
        if (!$this->validate([
            'username' => 'required',
            'password' => 'required|min_length[5]',
            'role' => 'required',
            ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            
            return redirect()->back()->withInput();
        }
        
        $request = [
            // 'admin_id' => $this->generateCode(),
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'role'     => $this->request->getPost('role'),
        ];

        $result = $this->model->save($request);
        
        if ($result) {
            session()->setFlashdata('message', 'Tambah Data Berhasil');
        } else {
            session()->setFlashdata('error', 'Tambah Data Tidak Berhasil');
        }
        
        return redirect()->to('admin/admin');
    }
    
    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        $data = [
            'Admin' => $this->model->where('admin',$id)->first(),
            'title' => "Data Admin",
            'sub_title' => "Edit Data Admin",
        ];
        return view('admin/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        if (!$this->validate([
            'username' => 'required',
            'role' => 'required',
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());

            return redirect()->back()->withInput();
        }

        $request = [
            'username' => $this->request->getPost('username'),
            'role' => $this->request->getPost('role'),
        ];
        if($this->request->getPost('password') != ""){
            $request = [
                'password' => md5($this->request->getPost('password')),
                'username' => $this->request->getPost('username'),
                'role' => $this->request->getPost('role'),
            ];

        }

        $result = $this->model->update($id, $request);

        if ($result) {
            session()->setFlashdata('message', 'Update Data Berhasil');
        } else {
            session()->setFlashdata('error', 'Update Data Tidak Berhasil');
        }

        return redirect()->to('admin/admin');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $Admin = $this->model->first($id);
        if($Admin->role == "Admin"){
            session()->setFlashdata('error', 'Data Ini Tidak Bisa Dihapus!');
            return redirect()->to('admin/admin');
        }
        
        if ($this->model->delete($id)) {
            session()->setFlashdata('message', 'Hapus Data Berhasil');
        } else {
            session()->setFlashdata('error', 'Hapus Data Tidak Berhasil');
        }

        return redirect()->to('admin/admin');
    }


    public function generateCode()
    {
        $count = $this->model->countAll();
        $data = $this->model->select('admin_id')->orderBy('admin_id DESC')->limit('1')->first();

        if ($count > 0) {
            $code = substr($data->admin_id, 4);
            $code = $code + 1;
            $code = 'ADM' . sprintf("%04s", $code);
        } else {
            $code = 'ADM0001';
        }

        return $code;
    }
}
