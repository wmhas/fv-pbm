<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class BorangJevController extends Controller
{
    public function index ()
    {
        return view('borang.index', [
            'roles' => DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first()
        ]);
    }

    //public function print (Request $request)
    public function print ($doNumber)
    {
    
        //$doNumber = $request->get('do_number');
     // dd($doNumber);

        if (!$doNumber) {
            return redirect(route('borang.index'));
        }

        $order = Order::where('do_number', $doNumber)
            ->with(['patient.card', 'patient.state'])
            ->first();
        if (!$order) {
            return redirect(route('borang.index'));
        }

        $view = view('borang.print_pdf', [
            'order' => $order
        ]);
        $pdf = PDF::loadHTML($view);
        return $pdf->download('Borang_JHEV_DO_' . $doNumber . '.pdf');
    }
}
