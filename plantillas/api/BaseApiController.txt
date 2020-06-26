<?php

namespace App\Http\Controllers\API;

class BaseApiController {

    public function sendResponse($datos, $mensaje) {

        $respuesta = [
            'exito' => true,
            'mensaje' => $mensaje,
            'datos' => $datos,
        ];

        return response()->json($respuesta, 200);
    }

    public function sendError($datosError, $mensaje = [], $codigo = 404) {

        $respuesta = [
            'exito' => false,
            'mensaje' => $mensaje,
            'datos' => $datosError,
        ];

        return response()->json($respuesta, $codigo);
    }

}
