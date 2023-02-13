<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\ResiModel;
use App\Models\ProdukModel;

class Ongkir extends BaseController
{
    protected $raja_key = "c77bd09e66015ea8b62e826ab0c71cca";
    
    function __construct(){
        helper(['my_helper']);
        $this->Resi = new ResiModel;
        $this->Produk = new ProdukModel;
    }

    public function index(){
        $data = [
            'Produk' => $this->Produk->findAll(),
            'Provinsi' => $this->Province(),
            'title' => 'Cek Ongkir',
            'sub_title' => 'Cek Ongkir'
        ];
        return view('cek-ongkir/index', $data);
    }
    
    public function Province(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://pro.rajaongkir.com/api/province",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "key: " . $this->raja_key
        ),
        ));

        $json = curl_exec($curl);
        $err = curl_error($curl);
        return (object) json_decode($json);
    }

    public function city($provinceId = null){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://pro.rajaongkir.com/api/city?province=" . $provinceId,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "key: " . $this->raja_key
        ),
        ));

        $json = curl_exec($curl);
        $err = curl_error($curl);
        $response = (object) json_decode($json);
        return json_encode($response->rajaongkir->results);

    }

    public function subdis($city = null){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://pro.rajaongkir.com/api/subdistrict?city=" . $city,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "key: " . $this->raja_key
        ),
        ));

        $json = curl_exec($curl);
        $err = curl_error($curl);
        $response = (object) json_decode($json);
        return json_encode($response->rajaongkir->results);
    }

    public function cek($origin= null, $destination = null, $weight = null, $courier = null, $qty = null, $kode_barang = null){
        $produk = $this->Produk->where('kode_barang', $kode_barang)->first();

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://pro.rajaongkir.com/api/cost",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "origin=". $origin ."&originType=subdistrict&destination=" . $destination . "&destinationType=subdistrict&weight=" . $weight . "&courier=" . $courier,
        CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded",
            "key: " . $this->raja_key
        ),
        ));

        $json = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        $response = (object) json_decode($json);
        $html     = "";
        $copy_text   = "";
        $copy_alamat = "";

        if(count($response->rajaongkir->results[0]->costs) > 0){
            $no = 1;
            foreach ($response->rajaongkir->results[0]->costs as $key => $value) {
                
                $html .= "<tr>
                        <td>". $response->rajaongkir->results[0]->name . " - ". $value-> service ."</td>        
                        <td>". $value->cost[0]->etd ." Hari</td>        
                        <td>Rp. ". number_format($value->cost[0]->value, 2, ',','.') ."</td>        
                        <td>". $value->description ."</td>        
                </tr>";

                $cod = (2 * $produk->harga) / 100;
                $total_harga =  ($qty * $produk->harga) + $value->cost[0]->value;

                $copy_text .= "<div class='mb-3'>";
                $copy_text .= "<textarea rows='8' id='copy_text".$no."' name='copy_text".$no."' class='form-control' readonly>";
                $copy_text .= "Nama Produk  : " . $produk->nama_barang; 
                $copy_text .= "\r\nHarga Produk  : " . $qty . " x Rp. " . number_format($produk->harga, 2, ',','.') . " = Rp. " . number_format($qty * $produk->harga, 2, ',','.'); 
                $copy_text .= "\r\nEkspedisi  : " . $response->rajaongkir->results[0]->name . " - ". $value-> service;
                $copy_text .= "\r\nOngkir  : " . $qty . " x " . $weight . "(g) = Rp. " . number_format($value->cost[0]->value, 2, ',','.');
                $copy_text .= "\r\nCOD  : Rp. " . number_format($cod, 2, ',','.');
                $copy_text .= "\r\nTotal Harga : Rp. " . number_format($total_harga+$cod, 2, ',','.') ; 
                $copy_text .= "</textarea>";
                $copy_text .= "<button type='button' onclick=\"copyToClipboard('#copy_text".$no."')\" class='btn btn-success btn_copy mt-2'>Copy Text</button>";
                $copy_text .= "</div>";

                $result_text[] = $copy_text;

                $copy_text     = "";
                $no++;
            }
        }

        $data = [
            "result" => $html,
            "copy_alamat" => $copy_alamat,
            "copy_text" => $result_text,
        ];
        
        return json_encode($data);
    }
}
