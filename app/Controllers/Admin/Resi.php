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
        date_default_timezone_set("asia/jakarta");
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
            'tanggal_pencatatan'  => $this->request->getPost('tanggal_pencatatan')." ".date("H:i:s"),
            'admin_id'    => session('admin_id'),
        ];

        $result = $this->model->save($request);
        
        if ($result) {
            $message = "Hallo Kak 👋\r\nberikut rincian pembelian di *Dewa Store* yaa\r\n";
            $message .= "\r\n*Nama : " . trim($this->request->getPost('nama_customer')) . "*";
            $message .= "\r\n*No resi : " . $this->request->getPost('no_resi') . "*";
            $message .= "\r\n*Barang : " . $this->Produk->where('kode_barang', $this->request->getPost('kode_barang'))->first()->nama_barang . "*";
            $message .= "\r\n*Status Resi : Aktif*";
            $message .= "\r\n*Update Resi : -*";
            $message .= "\r\n\r\n*Dan untuk estimasi paket akan datang 2-3 hari pulau jawa dan 3-5 hari Untuk Luar pulau Jawa kak, Pengirimannya JNT EXPRES  ya kakak*";
            $message .= "\r\n*";
            $message .= "\r\n*No Resinya bisa digunakan untuk cek dan melacak pakatnya sudah sampai mana*";
            $message .= "\r\n*";
            $message .= "\r\n*Jika mungkin ada telpon dari nomor tidak dikenal, mohon dijawab, karena itu mungkin telpon dari kurir pengiriman*";
            $message .= "\r\n*";
            $message .= "\r\n\r\n*agar jika ada problem atau pemesanan selanjutnya kakak bisa langsung hubungi Admin Yang kaka Pesan Barangnya Karna Whatsapp ini Hanya untuk Tracking Resi 🤗🤗*";
            $message .= "\r\n\r\n*Terimakasi 😊*";
            $message .= "\r\n\r\n_Ini adalah pesan otomatis, tolong jangan balas pesan ini, jika ada pertanyaan langsung tanyakan ke admin yaa :))_";
            
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

            $r = $this->Resi->where('resi_id', $id)->countAllResults();
            if($r > 0){
                $this->Resi->where('resi_id', $id)->delete();
            }

            session()->setFlashdata('message', 'Hapus Data Berhasil');
        } else {
            session()->setFlashdata('error', 'Hapus Data Tidak Berhasil');
        }

        return redirect()->to('admin/resi');
    }

    public function import()
		{
            $db = \Config\Database::connect();

			$file_excel = $this->request->getFile('berkas');
			$ext = $file_excel->getClientExtension();

			if($ext == 'xls') {
				$render = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
			} else {
				$render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			}

			$spreadsheet = $render->load($file_excel);
	
			$data = $spreadsheet->getActiveSheet()->toArray();
            $rows = [];
            $no = 0;
			foreach($data as $x => $row) {
				if ($x == 0) {
					continue;
				}
				
                $cekResi = $db->table('tbl_resi')->getWhere(['no_resi' => $row[2]]);

                if ($cekResi->getNumRows() == 0){
                    $getProduct = $db->table('tbl_produk')->getWhere(['nama_barang' => $row[3]])->getRow();

                    $rows[$no] = array(
                        'nama_customer' => $row[0],
                        'no_telp'       => $row[1],
                        'no_resi'       => $row[2],
                        'kode_barang'   => $getProduct->kode_barang,
                        'ekspedisi'     => $row[4],
                        'harga'         => $row[5],
                        'tanggal_pencatatan'=> date('Y-m-d H:i:s'),
                        'sendWhatsapp'  => '0'
                    );
                }

                $no++;

            }
            // var_dump($rows);
            if (!empty($rows)){
                $db->table('tbl_resi')->insertBatch($rows);
                session()->setFlashdata('message', 'Import Data Berhasil');
            } else {
                session()->setFlashdata('error', 'Import Data Tidak Berhasil');
            }
			return redirect()->to('/admin/resi');
		}
}
