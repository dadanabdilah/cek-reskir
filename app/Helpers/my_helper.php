<?php 
    function expedisi($ex = null){
        $Expedisi = [
            'jne' => 'JNE',
            'jnt' => 'JNT',
            'sap' => 'SAP Express',
            'sicepat' => 'SICEPAT',
            // 'pos' => 'POS',
            // 'ninja' => 'NINJA',
            // 'anteraja' => 'Anteraja',
            // 'spx' => 'Shopee Express',
            // 'lion' => 'LION',
            // 'tiki' => 'Tiki',
            // 'jet' => 'JET Express',
            // 'ide' => 'ID Express',
            // 'wahana' => 'Wahana',
            // 'rpx' => 'RPX',
            // 'rex' => 'REX Express',
            // 'first' => 'FIRST Logitics',
        ];

        // jne, pos, tiki, rpx, wahana, sicepat, jnt, sap, jet,
        // first, ninja, lion, rex, ide, anteraja,

        if($ex == null){
            return $Expedisi;
        } else {
            return $Expedisi[$ex];
        }

    }

    function cek_expedisi($ex = null){
        $Expedisi = [
            'jne' => 'JNE',
            'jnt' => 'JNT',
            'sap' => 'SAP Express',
            'sicepat' => 'SICEPAT',
            // 'pos' => 'POS',
            // 'ninja' => 'NINJA',
            // 'anteraja' => 'Anteraja',
            // 'lion' => 'LION',
            // 'tiki' => 'Tiki',
            // 'jet' => 'JET Express',
            // 'ide' => 'ID Express',
            // 'wahana' => 'Wahana',
            // 'rpx' => 'RPX',
            // 'rex' => 'REX Express',
            // 'first' => 'FIRST Logitics',
        ];

        // jne, pos, tiki, rpx, wahana, sicepat, jnt, sap, jet,
        // first, ninja, lion, rex, ide, anteraja,

        if($ex == null){
            return $Expedisi;
        } else {
            return $Expedisi[$ex];
        }

    }

    function sendWa($target = null, $message = null) 
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $target,
                // 'message' => "Halo kak, \r\n\r\nBerikut adalah rincian pesanan Anda:\r\n\r\n- Produk : " . $data_wa['product'] . " \r\n- No.Invoice : " . $data_wa['order_id'] . " \r\n- Total Tagihan : " . $data_wa['total_bayar'] . " \r\n- Metode Pembayaran : " . $data_wa['method'] ."\r\n\r\nCek pesanan anda di sini ". base(_url() . "/payment/" . $data_wa['order_id']  ."\r\n\r\nTerima kasih.",
                'message' => $message,
                'countryCode' => '62', //optional
            ),
            CURLOPT_HTTPHEADER => [
                'Authorization: JUkxqxhYf3U0Y@fNIhkj' //change TOKEN to your actual token
            ],
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

?>