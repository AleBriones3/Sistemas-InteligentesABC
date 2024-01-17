<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormularioController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function procesarPago(Request $request)
    {
        $tarjeta = $request->input('tarjeta');
        $fechaValidacion = $request->input('fecha_validacion');
        $cvv = $request->input('cvv');
        $intentos = $request->input('intentos');
        $fondos = $request->input('fondos');

    }

    private function ValidacionesPago($tarjeta, $fechaValidacion, $cvv, $intentos, $fondos)
    {
            if (
            $tarjeta == 'verificada' &&
            $fechaValidacion == 'no_expirada' &&
            $cvv == 'correcto' &&
            $intentos == 'no_excedidos' &&
            $fondos == 'suficiente'
        ) {
            $pagoResultado = 'autorizado';
        } elseif ($tarjeta == 'no_verificada') {
            $pagoResultado = 'no_autorizado';
        } elseif ($fechaValidacion == 'expirada' || $cvv == 'incorrecto' || $intentos == 'excedidos' || $fondos == 'insuficiente') {
            $pagoResultado = 'no_autorizado';
        } else {
            $pagoResultado = 'no_autorizado';
        }
    }
}


   
