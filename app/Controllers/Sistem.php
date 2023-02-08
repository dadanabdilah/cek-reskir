<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ResiModel;
use App\Models\ResiActivityModel;

class Sistem extends BaseController
{
    function __construct(){
        helper(['my_helper']);
        $this->Resi = new ResiModel;
        $this->ResiAct = new ResiActivityModel;
    }

    public function update_resi()
    {
        // $key  = "68726f2c48ed01b798228a748c9a77684f7959220263e8d08af9b7807b4e610f";

        $Resi = $this->Resi->findAll();
        $rows = [];
        foreach($Resi as $keys => $values){
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.binderbyte.com/v1/track?api_key=68726f2c48ed01b798228a748c9a77684f7959220263e8d08af9b7807b4e610f&courier='. $values->ekspedisi .'&awb=' . $values->no_resi,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));

            $json = curl_exec($curl);
            curl_close($curl);
            $result = (object) json_decode($json);

            $rows[] = $result;

            // {"status":200,"message":"Successfully tracked package","data":{
            //         "summary":{
            //             "awb":"004176544749","courier":"SiCepat","service":"REG","status":"DELIVERED","date":"2023-01-29 01:19","desc":"","amount":"6200","weight":"1"},
            //             "detail":{"origin":"DKI Jakarta","destination":"Kuningan, Kab. Kuningan","shipper":"Toko Mitra Abadi Official","receiver":"Nela Nailus Syarifah"},
            //             "history":[
            //                 {"date":"2023-01-31 10:43:00","desc":"PAKET DITERIMA OLEH [NELA - (KEL) KELUARGA SERUMAH]","location":""},
            //                 {"date":"2023-01-31 09:12:00","desc":"PAKET DIBAWA [SIGESIT - MAMAN RUDIMAN]","location":""},
            //                 {"date":"2023-01-31 08:52:00","desc":"PAKET TELAH DI TERIMA DI KUNINGAN [KUNINGAN JALAKSANA]","location":""},
            //                 {"date":"2023-01-30 14:44:00","desc":"PAKET KELUAR DARI CIREBON [CIREBON SORTATION]","location":""},
            //                 {"date":"2023-01-30 13:34:00","desc":"PAKET TELAH DI TERIMA DI CIREBON [CIREBON SORTATION]","location":""},
            //                 {"date":"2023-01-30 06:42:00","desc":"PAKET KELUAR DARI JAKARTA UTARA [LINE HAUL DARAT JAKARTA 1]","location":""},
            //                 {"date":"2023-01-30 04:50:00","desc":"PAKET TELAH DI TERIMA DI JAKARTA UTARA [LINE HAUL DARAT JAKARTA 1]","location":""},
            //                 {"date":"2023-01-28 23:01:00","desc":"PAKET KELUAR DARI JAKARTA BARAT [JAKBAR MANGGA BESAR]","location":""},
            //                 {"date":"2023-01-28 18:19:00","desc":"PAKET TELAH DI INPUT (MANIFESTED) DI JAKARTA BARAT [SICEPAT EKSPRES PINANGSIA]","location":""},
            //                 {"date":"2023-01-28 10:45:00","desc":"TERIMA PERMINTAAN PICK UP DARI [SHOPEE]","location":""}
            //             ]
            //         }
            //     };

            if($result->status == 200){
                foreach($result->data->history as $key => $val){
                    $res_act = $this->ResiAct->where('resi_id', $values->resi_id)->where('date', $val->date)->where('description', $val->desc)->findAll();
                    if(count($res_act) == 0 ){
                        $data = [
                            'resi_id' => $values->resi_id,
                            'date' => $val->date,
                            'description' => $val->desc,
                            'location' => $val->location,
                        ];
        
                        $this->ResiAct->save($data);
                        $message = "Halo kak, berikut informasi dari resi kaka :\r\n\r\nTanggal : ". $val->date . "\r\nKeterangan : " . $val->desc;
                        sendWa($values->no_telp, $message);
                    }
                }
            } else if($result->status == 400){
                $message = "Halo kak, berikut informasi dari resi kaka :\r\nKeterangan : " . $result->message;
                sendWa($values->no_telp, $message);
            }
        }

        print_r($rows);
    }
}
