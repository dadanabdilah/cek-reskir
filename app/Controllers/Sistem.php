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

    public function update_resi()
    {
        // SICEPAT DELIVERED,
        // JNT 1
        $raja_key  = "c77bd09e66015ea8b62e826ab0c71cca";

        $Resi = $this->Resi->findAll();
        $rows = [];
        
        if($this->Resi->countAllResults() == 0){
            die();
        }

        foreach($Resi as $keys => $values){
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
                            'resi_id' => $values->resi_id,
                            'status' => $val->manifest_code,
                        ];

                        $this->Resi->save($update);

                        $message = "Halo kak, berikut informasi dari resi kaka :\r\n\r\nTanggal : ". $val->manifest_date . " " . $val->manifest_time . "\r\nKeterangan : " . $val->manifest_description;
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
                $message .= "\r\n*Nama : " . $values->nama_customer . "*";
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
        }

        dd($rows);
    }
}
