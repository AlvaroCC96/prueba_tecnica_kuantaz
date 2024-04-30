<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Services\BeneficiosService;
use Illuminate\Support\Facades\Log;
use Exception;

class ApiController extends Controller
{
    protected $service;

    public function __construct(BeneficiosService $service)
    {
        $this->service = $service;
    }

    public function processRequest(Request $request)
    {   
        try{
            $response = $this->service->process();
            return response()->json($response);
        } catch (Exception $e) {
            Log::error('Ha ocurrido un error al obtener los datos: '.$e->getMessage());
            $resultado = [
                'code' => 500,
                'success' => false,
                'message' => 'Ha ocurrido un error al obtener los datos: ',
            ];
    
            return response()->json($resultado, $resultado['code']);
        }
        
    }
}
