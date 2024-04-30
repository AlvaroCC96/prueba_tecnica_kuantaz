<?php

namespace App\Services;

class BeneficiosService
{   

    /**
     * Metodo general para hacer peticiones desde el service a una url 
     */
    public function getDataFromUrl($url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        if ($response) {
            return json_decode($response, true);
        } else {
            throw new \Exception('Error al obtener los datos de la URL: ' . $url);
        }
    }

    /**
     * Metodo que filtra los beneficios en base a los montos maximos y minimos 
     */
    private function filterData($beneficios, $filtros) {
        
        return $beneficios->filter(function ($item) use ($filtros) {
            $filtro = $filtros->firstWhere('id_programa', $item['id_programa']);
            if ($filtro) {
                return $item['monto'] >= $filtro['min'] && $item['monto'] <= $filtro['max'];
            }
            return false;
        });
    }

    /**
     * Metodo quue agrupa los beneficios por año y agrega detalles de la ficha
     */
    private function groupData($beneficios){
        
        return $beneficios->groupBy(function ($item) {
            return date('Y', strtotime($item['fecha']));
        })->sortByDesc(function ($beneficios, $year) {
            return $year;
        });
    }

    /**
     * Metodo principal del servicio, el que procesa los datos una vez cargados y filtrados
     */
    public function process()
    {

        $beneficios = $this->getDataFromUrl("https://run.mocky.io/v3/399b4ce1-5f6e-4983-a9e8-e3fa39e1ea71");
        $filtros = $this->getDataFromUrl("https://run.mocky.io/v3/06b8dd68-7d6d-4857-85ff-b58e204acbf4");
        $fichas = $this->getDataFromUrl("https://run.mocky.io/v3/c7a4777f-e383-4122-8a89-70f29a6830c0");

        // Convertir los arrays de datos en colecciones
        $beneficios = collect($beneficios['data']);
        $filtros = collect($filtros['data']);
        $fichas = collect($fichas['data']);

        $beneficios = $this->filterData($beneficios, $filtros);
        $beneficiosPorAnio = $this->groupData($beneficios);

        // Se formatea el resultado a entregar despues de filtrar y agrupar la data
        $resultado = [
            'code' => 200,
            'success' => true,
            'data' => $beneficiosPorAnio->map(function ($beneficios, $year) use ($fichas) {
                return [
                    'year' => $year,
                    'num' => $beneficios->count(),
                    'total' => $beneficios->sum('monto'),
                    'beneficios' => $beneficios->map(function ($beneficio) use ($fichas, $year) {
                        $ficha = $fichas->firstWhere('id_programa', $beneficio['id_programa']);
                        return [
                            'id_programa' => $beneficio['id_programa'],
                            'fecha_recepcion' => $beneficio['fecha_recepcion'],
                            'ano' => strval($year),
                            'monto' => $beneficio['monto'],
                            'fecha' => $beneficio['fecha'],
                            'ficha' => $ficha,
                            'view' => true //Se asume, ya que no viene especificado en el enunciado
                        ];
                    }),
                ];
            })->values()->all(),
        ];
        return $resultado;
    }

}