<?php

namespace App\Controllers;

class Home extends BaseController
{
    private $payload;
    private $baseApi;
    private $requestAPI;
    
    
    public function __construct () {
        $this->baseApi = 'https://veiculos.fipe.org.br/api/veiculos/';
        // $this->payload = $this->request->getJSON();;
        // $this->requestAPI = "";
    }

    public function index()
    {
        
        
        $acts = [
            "ConsultarTabelaDeReferencia",
            // "ConsultarValorComTodosParametros" => [
            //     'codigoTabelaReferencia' => 263,
            //     'codigoTipoVeiculo' => 1,
            //     'anoModelo' => 2011,
            //     'modeloCodigoExterno' => '004357-5',
            //     'tipoConsulta' => 'codigo',
            // ],
            "ConsultarMarcas" => [
                "codigoTabelaReferencia" => 231,
                "codigoTipoVeiculo" => 1
            ],
            // "ConsultarModelos" => [
            //     'codigoTabelaReferencia' => 231,
            //     'codigoTipoVeiculo' => 1,
            //     'codigoMarca' => 26
            // ],
            // "ConsultarAnoModelo" => [
            //     'codigoTabelaReferencia' => 231,
            //     'codigoTipoVeiculo' => 1,
            //     'codigoMarca' => 26,
            //     'codigoModelo' => 4403
            // ],
            // "ConsultarModelosAtravesDoAno" => [
            //     'codigoTabelaReferencia' => 231,
            //     'codigoTipoVeiculo' => 1,
            //     'codigoMarca' => 26,
            //     'ano' => '2011-1',
            //     'codigoTipoCombustivel' => 1,
            //     'anoModelo' => 2011
            // ],
            // "ConsultarValorComTodosParametros" => [
            //     'codigoTabelaReferencia' => 231,
            //     'codigoTipoVeiculo' => 1,
            //     'codigoMarca' => 26,
            //     'ano' => '2011-1',
            //     'codigoTipoCombustivel' => 1,
            //     'anoModelo' => 2011,
            //     'codigoModelo' => 4403,
            //     'tipoConsulta' => 'tradicional'
            // ]
        ];
        // var_dump($acts);exit.
        $datas = [];
        foreach($acts as $act => $payload) {
            if(gettype($payload) === 'array') {
                $doit = $act;
                $pld = $payload;
            } else {
                $doit = $payload;
                $pld = [];
            }
            $this->requestAPI = $this->baseApi . $doit;
            // echo $doit ." -> ".$this->requestAPI . " with ";
            // echo "<pre>";
            // echo json_encode($pld);
            // echo "</pre>";
            // echo json_encode($pld);
            // echo  "\n<br> Response => \n";
            $r = $this->doRequest($this->requestAPI, json_encode($pld));
            // $this->requestAPI = $this->baseApi . $act;
            // echo  "\nResponse => \n";
            // echo "<pre>";
            // echo $r;
            // echo "</pre>";
            $datas[$doit] = json_decode($r);
            // $r = $this->doRequest($this->requestAPI, $rdata->payload);
            // $count = count(json_decode($r, true));
            // echo $r;
            // echo $count . " => " .$r;
            // echo "<hr>";
        }
        
        return view('welcome_message', compact('datas'));
    }

    public function doRequest($url, $data) {
        // print_r(json_encode($data));exit;
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => [
                "Host: veiculos.fipe.org.br",
                "Referer: https://veiculos.fipe.org.br",
                "Content-Type: application/json"
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    public function api() {
        $rdata = $this->request->getPost();
        $this->requestAPI = $this->baseApi . $rdata['act'];
        $pl = $rdata['payload'];
        $r = $this->doRequest($this->requestAPI, json_encode($pl));
            
        echo $r;
    }
}
