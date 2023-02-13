<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;

use App\Models\ResiModel;
use App\Models\ProdukModel;
use App\Models\AdminModel;

class Dashboard extends BaseController
{
    function __construct(){
        $this->Admin = new AdminModel();
        $this->Produk = new ProdukModel();
        $this->Resi = new ResiModel();
    }

    public function index()
    {
        $data = [
            'admin' => $this->Admin->countAllResults(),
            'produk' => $this->Produk->countAllResults(),
            'resi' => $this->Resi->countAllResults(),
            'title' => 'Dashboard',
        ];
        return view('dashboard/index', $data);
    }
}
