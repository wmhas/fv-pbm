<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Hospital;

class HospitalController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:maintenance-hospital', ['only' => ['index','store','update','destroy','search']]);
    }

    public function index(Request $request)
    {
        $name = $request->keyword;
        $keyword = preg_replace("/[^a-zA-Z0-9 ]/", "", $name);

        $keyword ? $hospitals = Hospital::where('name', 'like', '%' . $keyword . '%')
            ->orderBy('name', 'desc')
            ->paginate(15)
            :  $hospitals = Hospital::orderBy('id', 'desc')->paginate(15);
        $roles= DB::table('model_has_roles')->join('users','model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('hospitals.index', ['hospitals' => $hospitals, 'roles' => $roles]);
    }

    public function store(Request $request)
    {
        Hospital::create(['name' => $request->name]);
        return redirect()->action([HospitalController::class, 'index'])->with(['status' => true, 'message' => 'New Hospital Added']);
    }


    public function update(Request $request, Hospital $hospital)
    {
        Hospital::where('id', $hospital->id)
            ->update(['name' => $request->name]);

        return redirect()->action([HospitalController::class, 'index'])->with(['status' => true, 'message' => 'Hospital Updated']);
    }

    public function destroy(Hospital $hospital)
    {
        Hospital::destroy($hospital->id);
        return redirect()->action([HospitalController::class, 'index'])->with(['status' => true, 'message' => 'Hospital Deleted']);
    }

    public function search(Request $request)
    {
        $name = $request->keyword;
        $keyword = preg_replace("/[^a-zA-Z0-9 ]/", "", $name);

        $keyword ? $hospitals = Hospital::where('name', 'like', '%' . $keyword . '%')
            ->orderBy('name', 'desc')
            ->paginate(15)
            :  $hospitals = Hospital::orderBy('id', 'desc')->paginate(15);
        $roles= DB::table('model_has_roles')->join('users','model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('hospitals.index', ['hospitals' => $hospitals, 'roles'=> $roles]);
    }
}
