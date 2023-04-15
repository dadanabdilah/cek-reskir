<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ResiModel;
use App\Models\ResiActivityModel;
use App\Models\ResiNotifModel;
use App\Models\ProdukModel;

class Sistem extends BaseController
{
    function __construct(){
        helper(['my_helper']);
        $this->Resi = new ResiModel;
        $this->ResiAct = new ResiActivityModel;
        $this->ResiNotif = new ResiNotifModel;
        $this->Produk = new ProdukModel;
    }

    public function cekExpired()
    {
        $db      = \Config\Database::connect();
        date_default_timezone_set("asia/jakarta");
        $Resi = $db->table('tbl_resi');
        $Resi->where('status', "DELIVERED");
        $Resi->where('datediff(now(), tanggal_pencatatan) >= 2');

        $no = 0;
        foreach ($Resi->get()->getResult() as $key) {
            $resiDump = $db->table('tbl_resi_clear');
            $resiDump->insert($key);
            $deleteResi = $db->table('tbl_resi');
            $deleteResi->delete(['resi_id' => $key->resi_id]);
            $no++;
        }

        $this->createLog("logExpired.txt", "[".date("Y/m/d H:i:s")."] Telah terhapus $no data.\r\n");
        echo "[".date("Y/m/d H:i:s")."] logExpired.txt updated.";
    }

    public function update_resis()
    {
        $db      = \Config\Database::connect();
        $Resi = $db->table('tbl_resi');
        // SICEPAT DELIVERED,
        // JNT 1
        $raja_key  = "a87a0e777f90d2db9a47f194006dc2ea";

        // $Resi = $this->Resi->findAll();
        $Resi->where('status !=', "DELIVERED");

        // var_dump($Resi->countAll());
        $rows = [];
        
        // if($Resi->countAll() == 0){
        //     die();
        // }

        foreach($Resi->get()->getResult() as $keys => $values){
            var_dump($values);
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://pro.rajaongkir.com/api/waybill",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "waybill=". $values->no_resi ."&courier=" . $values->ekspedisi,
                    CURLOPT_HTTPHEADER => array(
                        "content-type: application/x-www-form-urlencoded",
                        "key: " . $raja_key . ""
                    ),
                ));

                $json = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);
                $result = (object) json_decode($json);

                $rows[] = $result->rajaongkir;

                $result = $result->rajaongkir;

                if(@$result->status->code == 200){
                    if (@$result->result->delivered == true){

                        $update = [
                            'status' => 'DELIVERED',
                        ];

                        $this->Resi->update($values->resi_id, $update);

                    }
                    var_dump($result);
                    foreach($result->result->manifest as $key => $val){
                        
                        $res_act = $this->ResiAct->where('resi_id', $values->resi_id)->where('date', $val->manifest_date . " " . $val->manifest_time)->where('description', $val->manifest_description)->findAll();
                        
                        if(count($res_act) == 0 ){
                            $data = [
                                'resi_id' => $values->resi_id,
                                'date' => $val->manifest_date . " " . $val->manifest_time,
                                'description' => $val->manifest_description,
                                'location' => $val->city_name,
                            ];
            
                            $this->ResiAct->save($data);

                            $update = [
                                'status' => $val->manifest_code,
                            ];

                            $this->Resi->update($values->resi_id,$update);

                            $message = "Halo kak, berikut informasi dari resi kaka :\r\n\r\nTanggal : ". $val->manifest_date . " " . $val->manifest_time . "\r\nKeterangan : " . $val->manifest_description . " " . $val->city_name;
                            sendWa($values->no_telp, $message);
                        }
                    }
                } else if(@$result->status->code == 400){
                    $deskripsi = $result->status->description;
                    $data = [
                        'resi_id' => $values->resi_id,
                        'deskripsi' => $deskripsi,
                    ];

                    $message = "Hallo kak, ini untuk Update resi nya yaa\r\n";
                    $message .= "\r\n*Nama : " . trim($values->nama_customer) . "*";
                    // $message .= "\r\nAlamat : yyyyy";
                    $message .= "\r\n*Pembelian : " . $this->Produk->where('kode_barang', $values->kode_barang)->first()->nama_barang . "*";
                    $message .= "\r\n*No resi : " . $values->no_resi . "*";
                    $message .= "\r\n*Keterangan : " . $deskripsi . "*";
                    $message .= "\r\n\r\n_Ini adalah pesan otomatis, tolong jangan balas pesan ini, jika ada pertanyaan langsung tanyakan ke admin yaa :))_";

                    // $message = "Halo kak, berikut informasi dari resi kaka :\r\nKeterangan : " . $deskripsi;
                    
                    if($this->ResiNotif->where('resi_id', $values->resi_id)->where('deskripsi', $deskripsi)->countAllResults() < 1){
                        $this->ResiNotif->save($data);
                        sendWa($values->no_telp, $message);
                    }
                }
                sleep(30);
        }

        dd($rows);
    }

    public function cekDownServer()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://101.255.119.6/",
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        
    }


    public function cekResi($limit = 4, $offset = 1)
    {
        date_default_timezone_set("asia/jakarta");
        $db      = \Config\Database::connect();

        $apikey  = "ID202302190010290";
        $apikeyBiteShip = "";

        $Resi = $db->table('tbl_resi');
        $Resi->where('status', NULL);
        $Resi->orderby('tanggal_pencatatan', 'DESC');

        $no = 0;
        foreach($Resi->get($limit, $offset)->getResult() as $keys => $values){
            $url = "http://34.135.238.233/api-jnt-tracking/index.php?api_key=".$apikey."&waybill=".$values->no_resi;
            echo "Resi ".$values->no_resi."<br/>".$url."</br>";
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $json = curl_exec($curl);
            // var_dump($json);
            echo $json;
            curl_close($curl);

            $result = json_decode($json);
            var_dump($result);
            // $result = $result->rajaongkir;

            // if($result->info == 200){
            //     if ($result->status == "DELIVERED"){
            //         $update = [
            //             'status' => 'DELIVERED',
            //         ];
            //         $this->Resi->update($values->resi_id, $update);
            //     }
            //     foreach($result->histori as $key => $val){
                    
            //         $res_act = $this->ResiAct->where('resi_id', $values->resi_id)->where('date', $val->time)->where('description', $val->desc)->findAll();
                    
            //         if(count($res_act) == 0 ){
            //             $data = [
            //                 'resi_id' => $values->resi_id,
            //                 'date' => $val->time,
            //                 'description' => $val->desc,
            //                 'location' => $val->position,
            //             ];
            
            //             $this->ResiAct->save($data);

            //         }
            //     }

            //     $no++;
            // } else {
            //     $deskripsi = $result;
            //     $data = [
            //         'resi_id' => $values->resi_id,
            //         'deskripsi' => $deskripsi,
            //     ];

            //     if($this->ResiNotif->where('resi_id', $values->resi_id)->where('deskripsi', $deskripsi)->countAllResults() < 1){
            //         $this->ResiNotif->save($data);
            //     }
            //     $this->createLog("logResiInvalid.txt", "[".date("Y/m/d H:i:s")."] Resi $values->no_resi $deskripsi.\r\n");
            //     echo "[".date("Y/m/d H:i:s")."] logResiInvalid.txt updated.";
            // }

        }


        $this->createLog("logResi.txt", "[".date("Y/m/d H:i:s")."] Telah terubah aktivitas $no resi data.\r\n");
        echo "[".date("Y/m/d H:i:s")."] logResi.txt updated.";
    }

    public function cekWhatsapp($limit = 4, $offset = 1)
    {
        $db      = \Config\Database::connect();
        date_default_timezone_set("asia/jakarta");
        $Resi = $db->table('tbl_resi');
        $Resi->orderby('tanggal_pencatatan', 'DESC');

        $no = 0;
        foreach ($Resi->get($limit, $offset)->getResult() as $key){
            $Activity = $db->table('tbl_resi_activity')->where(['resi_id' => $key->resi_id, 'sendWhatsapp' => '0'])->orderby('date', 'asc');
            foreach ($Activity->get()->getResult() as $value) {
                if ($value->description == "Telah Diambil" || $value->description == "Sedang Diantar" || $value->description == "Terkirim"){
                    // var_dump($value);
                    $message = "Halo kak ".$key->nama_customer.", berikut informasi dari resi kaka :\r\n\r\nTanggal : ". $value->date . "\r\nStatus : Aktif\r\nKeterangan : " . $value->description . " " . $value->location."\r\nNo Resi : " . $key->no_resi."\r\n\r\n_Ini adalah pesan otomatis, tolong jangan balas pesan ini, jika ada pertanyaan langsung tanyakan ke admin yaa :))_";
                    $send = sendWa($key->no_telp, $message);

                    if ($send){
                        $no++;
                    }
                    $update = array(
                        'sendWhatsapp' => 1
                    );

                    $db->table('tbl_resi_activity')->update($update, ['resi_activity_id' => $value->resi_activity_id]);
                }
            }
        }
  
        $this->createLog("logSendWhatsapp.txt", "[".date("Y/m/d H:i:s")."] Telah mengirim $no perubahan resi.\r\n");
        echo "[".date("Y/m/d H:i:s")."] logSendWhatsapp.txt updated.";
    }

    public function cekWhatsappResi($limit = 4, $offset = 1)
    {
        $db      = \Config\Database::connect();
        date_default_timezone_set("asia/jakarta");
        $Resi = $db->table('tbl_resi');
        $Resi->where('sendWhatsapp', '0');
        $Resi->orderby('tanggal_pencatatan', 'DESC');

        $no = 0;
        foreach ($Resi->get($limit, $offset)->getResult() as $key){
                // var_dump($value);
            $message = "Hallo Kak 👋\r\nberikut rincian pembelian di *Dewa Store* yaa\r\n";
            $message .= "\r\n*Nama : " . trim($key->nama_customer) . "*";
            $message .= "\r\n*No resi : " . $key->no_resi . "*";
            $message .= "\r\n*Barang : " . $this->Produk->where('kode_barang', $key->kode_barang)->first()->nama_barang . "*";
            $message .= "\r\n*Status Resi : Aktif*";
            $message .= "\r\n*Update Resi : -*";
            $message .= "\r\n\r\n*Dan untuk estimasi paket akan datang 2-3 hari pulau jawa dan 3-5 hari Untuk Luar pulau Jawa kak*, *Pengirimannya JNT EXPRES ya kakak*";
            $message .= "\r\n*";
            $message .= "\r\n*No Resinya bisa digunakan untuk cek dan melacak pakatnya sudah sampai mana*";
            $message .= "\r\n*";
            $message .= "\r\n*Jika mungkin ada telpon dari nomor tidak dikenal, mohon dijawab, karena itu mungkin telpon dari kurir pengiriman*";
            $message .= "\r\n*";
            $message .= "\r\n\r\n*agar jika ada problem atau pemesanan selanjutnya kakak bisa langsung hubungi Admin Yang kaka Pesan Barangnya Karna Whatsapp ini Hanya untuk Tracking Resi* 🤗🤗";
            $message .= "\r\n\r\n*Terimakasi* 😊";
            $message .= "\r\n\r\n_Ini adalah pesan otomatis, tolong jangan balas pesan ini, jika ada pertanyaan langsung tanyakan ke admin yaa :))_";
            
            sendWa($key->no_telp, $message);

            $update = array(
                'sendWhatsapp' => 1
            );

            $no++;

            $db->table('tbl_resi')->update($update, ['resi_id' => $key->resi_id]);
        }
  
        $this->createLog("logSendWhatsappResi.txt", "[".date("Y/m/d H:i:s")."] Telah mengirim $no resi baru.\r\n");
        echo "[".date("Y/m/d H:i:s")."] logSendWhatsappResi.txt updated.";
    }
    
    function createLog($nameFile = "logDelete.txt", $textFile = "Telah terhapus 1 data.\r\n"){
        $text = "";

        if (file_exists($nameFile) && filesize($nameFile) > 0){
            $fw = fopen($nameFile, "r");  
            $text .= fread($fw, filesize($nameFile));
            fclose($fw);
        }

        $fp = fopen($nameFile, "wb");  
        $text .= $textFile;
        fwrite($fp, $text);
        fclose($fp);
    }

}
