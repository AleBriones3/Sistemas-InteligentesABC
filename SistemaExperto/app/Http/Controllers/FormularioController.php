<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DB;
use Carbon\Carbon;

class FormularioController extends Controller{
    public function index(){
        $objetos = DB::table('objetos')->get();
        return view('welcome', compact('objetos'));
    }

    public function procesarPago(Request $request){
        $tarjeta = $request->input('tarjeta');
        $fechaValidacion = $request->input('fecha_validacion');
        $cvv = $request->input('cvv');
        $id = $request->input('objeto');
        $objeto=DB::table('objetos')->where('id',$id)->first();
        $precio = $objeto->costo;

        $datos_tarjeta = DB::table('tarjetas')->where('tarjeta', $tarjeta)->first();

        if (!$datos_tarjeta) {
            return redirect()->back()->with('Error', 'La tarjeta no está registrada.');
        }
        else{
            $expDate = explode('-', $fechaValidacion);
            $expMonth = $expDate[1];
            $expYear = $expDate[0];
            
            $currentMonth = Carbon::now()->format('m');
            $currentYear = Carbon::now()->format('Y');
            
            $dbExpDateArray = explode('-', $datos_tarjeta->fecha);
            $dbExpMonth = $dbExpDateArray[1];
            $dbExpYear = $dbExpDateArray[0];

            if (!Session::has('tarjeta')) {
                Session::put('tarjeta', $tarjeta);
                $accesoId = DB::table('accesos')->insertGetId([
                    'id_tarjeta' => $datos_tarjeta->id,
                    'num_intento' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                Session::put('accesoId', $accesoId);
            }
            $accesoId = Session::get('accesoId');
            $intentos = DB::table('accesos')
            ->where('id', $accesoId)
            ->value('num_intento');
            $fondos = $datos_tarjeta->fondos;
            if ($intentos > 2) {

                DB::table('pagos')->insert([
                    'id_objeto' => $id, 
                    'id_tarjeta' => $datos_tarjeta->id,
                    'intentos' => $intentos,
                    'balance' => $fondos,
                    'estatus' => 'No autorizado',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                Session::forget('tarjeta');
                Session::forget('accesoId');
                return redirect()->back()->withErrors('Error', '')->with('Error', 'Número de intentos superado. Pago no autorizado.');
            }

            if ($expMonth != $dbExpMonth || $expYear != $dbExpYear || $expYear < $currentYear || ($expYear == $currentYear && $expMonth < $currentMonth)) {
                DB::table('accesos')
                ->where('id', $accesoId)
                ->increment('num_intento');
                if ($intentos >2) {
                    DB::table('pagos')->insert([
                        'id_objeto' => $id,
                        'id_tarjeta' => $datos_tarjeta->id,
                        'intentos' => $intentos,
                        'balance' => $fondos,
                        'estatus' => 'No autorizado',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                    Session::forget('tarjeta');
                    Session::forget('accesoId');
                    return redirect()->back()->withErrors('Error', '')->with('Error', 'Número de intentos agotado. La fecha de vencimiento no coincide con la registrada o ha caducado. Pago no autorizado.');
                }
                return redirect()->back()->with('Error', 'La fecha de vencimiento no coincide con la registrada o ha caducado.');
            }
            if ($cvv != $datos_tarjeta->CVV) {
                DB::table('accesos')
                ->where('id', $accesoId)
                ->increment('num_intento');
                if ($intentos > 2) {
                    DB::table('pagos')->insert([
                        'id_objeto' => $id,
                        'id_tarjeta' => $datos_tarjeta->id,
                        'intentos' => $intentos,
                        'balance' => $fondos,
                        'estatus' => 'No autorizado',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                    Session::forget('tarjeta');
                    Session::forget('accesoId');
                    return redirect()->back()->withErrors('Error', '')->with('Error', 'Número de intentos agotado. El CVV no coincide con el registrado. Pago no autorizado.');
                }
                return redirect()->back()->with('Error', 'El CVV no coincide con el registrado.');
            }
            if ($datos_tarjeta->fondos < $precio) {
                DB::table('pagos')->insert([
                    'id_objeto' => $id, 
                    'id_tarjeta' => $datos_tarjeta->id,
                    'intentos' => $intentos,
                    'balance' => 'Fondos Insuficientes',
                    'estatus' => 'No autorizado',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                Session::forget('tarjeta');
                Session::forget('accesoId');
                return redirect()->back()->withErrors('Error', '')->with('Error', 'Fondos insuficientes para realizar el pago.');
            }
            $nuevoBalance = $fondos - $precio;
            DB::table('pagos')->insert([
                'id_objeto' => $id,
                'id_tarjeta' => $datos_tarjeta->id,
                'intentos' => $intentos,
                'balance' => $nuevoBalance,
                'estatus' => 'Autorizado',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('tarjetas')
            ->where('id', $datos_tarjeta->id)
            ->update(['fondos' => $nuevoBalance]);
            Session::forget('tarjeta');
            Session::forget('accesoId');
            return redirect()->back()->with('Confirmacion', 'Pago realizado correctamente');

        }
        
    }
}
