<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\State;
use App\Models\Card;
use App\Models\PatientAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use PDF;

class PatientController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:patient-list', ['only' => ['index', 'patient_detail', 'patient_view', 'print_jhev', 'download_jhev','downloadICAttachment','downloadSLAttachment']]);
        $this->middleware('permission:patient-create', ['only' => ['create', 'store', 'create_address', 'store_address', 'create_card', 'store_card']]);
        $this->middleware('permission:patient-edit', ['only' => ['update_patient', 'update','update_ic_attach']]);
        $this->middleware('permission:patient-delete', ['only' => ['deleteAttachment']]);
    }

    public function index(Request $request)
    {
        $method = $request->get('method');
        $keyword = $request->get('keyword');
        // $keyword = preg_replace("/[^a-zA-Z0-9 ]/", "", $keyword);
        $cards = null;
        switch ($method) {
            case ('identification'):
                $patients = Patient::with('card')->where('identification', 'like', '%' . strtoupper($keyword) . '%')
                    ->orderBy('identification', 'asc')
                    ->paginate(10);
                break;

            case ('army_pension'):
                $cards = Card::where('army_pension', 'like', '%' . strtoupper($keyword) . '%')
                    ->orderBy('army_pension', 'asc')->pluck('id');

                $patients = Patient::with('card')->whereIn('card_id', $cards)
                    ->orderBy('id', 'asc')->limit(500)
                    ->paginate(10);

                break;

            default:
                $patients = Patient::with('card')->orderBy('id', 'desc')->paginate(10);
        }

        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('patients.index', [
            'patients' => $patients, 'cards' => $cards, 'roles' => $roles, 'method' => $method, 'keyword' => $keyword
        ]);
    }

    public function create($id = null)
    {
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        if (!empty($id)) {
            $patient = Patient::find($id);
            return view('patients.registrations.create', compact('patient', 'roles'));
        }
        return view('patients.registrations.create', compact('roles'));
    }

    public function store(Request $request)
    {
        if (!($request->update)) {
            $patient = Patient::create([
                'email' => $request->email,
                'full_name' => $request->full_name,
                'identification' => $request->identification,
                'gender' => $request->gender,
                'date_of_birth' => $request->dob,
                'phone' => $request->phone,
                'salutation' => $request->salutation,
                'tariff_id' => $request->tariff,
            ]);
        } else {
            $patient = Patient::find($request->id);
            $patient->email = $request->email;
            $patient->full_name = $request->full_name;
            $patient->identification = $request->identification;
            $patient->gender = $request->gender;
            $patient->date_of_birth = $request->dob;
            $patient->phone = $request->phone;
            $patient->salutation = $request->salutation;
            $patient->tariff_id =  $request->tariff;
            $patient->save();
        }
        return redirect()->action('PatientController@create_address', [
            'id' => $patient->id
        ]);
    }

    public function create_address($id)
    {
        $states = State::all();
        $patient = Patient::find($id);
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        // dd($patient);
        return view('patients.registrations.create_1', ['states' => $states, 'patient' => $patient, 'roles' => $roles]);
    }

    public function store_address(Request $request, $id)
    {
        $patient = Patient::find($id);
        $patient->address_1 = $request->address_1;
        $patient->address_2 = $request->address_2;
        $patient->address_3 = $request->address_3;
        $patient->postcode = $request->postcode;
        $patient->city = $request->city;
        $patient->state_id = $request->state;
        $patient->save();

        return redirect()->action('PatientController@create_card', [
            'id' => $patient->id
        ]);
    }

    public function create_card($id)
    {
        $patient = Patient::find($id);
        $cardchecking = Card::where('ic_no', $patient->identification)->first();
        // dd($cardchecking);
        $card = Card::all();
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('patients.registrations.create_2', ['patient' => $patient, 'cards' => $card, 'cardchecking' => $cardchecking, 'roles' => $roles]);
    }

    public function store_card(Request $request, $id)
    {
        // dd($request->all());
        $patient = Patient::find($id);
        if ($request->hasFile('ic_attach')) {
            //Get filename with extension
            $fileNameWithExt = $request->file('ic_attach')->getClientOriginalName();
            //Get just filename
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            //Get just extensions
            $extension = $request->file('ic_attach')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $fileName . '.' . $extension;
            //Upload file
            $path = $request->file('ic_attach')->storeAs('public/ic-attachment/' . $patient->identification . '/', $fileNameToStore);
            $document_path = 'public/ic-attachment/' . $patient->identification . '/' . $fileNameToStore;

            // store IC in database
            $patient->ic_original_filename = $fileNameToStore;
            $patient->ic_document_path = $document_path;
        }

        //SLAttachment
        if ($request->hasFile('sl_attach')) {
            foreach ($request->file('sl_attach') as $file) {
                $patient_attachment = new PatientAttachment();
                $patient_attachment->patient_id = $id;
                //Get filename with extension
                $fileNameWithExt = $file->getClientOriginalName();
                //Get just filename
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                //Get just extensions
                $extension = $file->getClientOriginalExtension();
                // Filename to store
                $fileNameToStore = $fileName . '.' . $extension;
                //Upload file
                $path = $file->storeAs('public/support-letter/' . $patient->identification . '/', $fileNameToStore);
                $document_path = 'public/support-letter/' . $patient->identification . '/' . $fileNameToStore;

                // store sl in database
                $patient_attachment->sl_original_filename = $fileNameToStore;
                $patient_attachment->sl_document_path = $document_path;
                $patient_attachment->save();
            }
        }

        if ($request->relation == 'CardOwner') {
            $cardchecking = Card::where('ic_no', $patient->identification)->first();
            if (!empty($cardchecking)) {
                $patient->card_id = $cardchecking->id;
                $patient->confirmation = 1;
                $patient->relation = $request->relation;
                $patient->save();

                $cardchecking->patient_id = $patient->id;
                $cardchecking->save();
            } else {
                $card = new Card();
                $card->patient_id =  $patient->id;
                $card->army_pension = $request->army_pension;
                $card->ic_no = $patient->identification;
                $card->name = $patient->full_name;
                $card->type = $request->card_type;
                $card->army_type = $request->army_type;
                $card->salutation = $request->salutation;
                $card->remark = $request->remark;
                $card->save();

                $latest_card = Card::where('patient_id', $patient->id)->first();
                $patient->card_id = $latest_card->id;
                $patient->confirmation = 1;
                $patient->relation = $request->relation;
                $patient->save();
            }
        } else {
            $card = new Card();
            $card->army_pension = $request->army_pension;
            $card->salutation = $request->salutation;
            $card->name = $request->card_name;
            $card->ic_no = $request->identification;
            $card->type = $request->card_type;
            $card->army_type = $request->army_type;
            $card->remark = $request->remark;
            $card->save();

            $latest_card = Card::latest()->first();
            $patient->card_id = $latest_card->id;
            $patient->confirmation = 1;
            $patient->relation = $request->relation;
            $patient->save();
        }

        return redirect()->action('PatientController@index')->with(['status' => true, 'message' => 'Register Sucessfully !']);
    }

    public function patient_detail(Patient $patient)
    {
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        if (empty($patient->state) && empty($patient->card)) {
            return redirect()->action('PatientController@create_address', [
                'id' => $patient->id
            ]);
        } else if (empty($patient->card)) {
            return redirect()->action('PatientController@create_card', [
                'id' => $patient->id
            ]);
        } else {
            $states = State::all();
            $patient_attachments = PatientAttachment::where('patient_id', $patient->id)->get();
            if ($patient_attachments->isEmpty()) {
                return view('patients.registrations.update', ['patient' => $patient, 'states' => $states, 'patient_attachments' => $patient_attachments, 'roles' => $roles, 'attachment' => null]);
            } else {
                return view('patients.registrations.update', ['patient' => $patient, 'states' => $states, 'patient_attachments' => $patient_attachments, 'roles' => $roles]);
            }
        }
    }

    public function patient_view(Patient $patient)
    {

        $states = State::all();
        $patient_attachments = PatientAttachment::where('patient_id', $patient->id)->get();
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('patients.registrations.view_detail', ['patient' => $patient, 'states' => $states, 'patient_attachments' => $patient_attachments, 'roles' => $roles]);
    }


    public function update_patient(Request $request, Patient $patient)
    {
        $patient->salutation = $request->salutation;
        $patient->full_name = $request->full_name;
        $patient->identification = $request->identification;
        $patient->email = $request->email;
        $patient->date_of_birth = $request->date_of_birth;
        $patient->phone = $request->phone;
        $patient->gender = $request->gender;
        $patient->address_1 = $request->address_1;
        $patient->address_2 = $request->address_2;
        $patient->postcode = $request->postcode;
        $patient->city = $request->city;
        $patient->state_id = $request->state;
        $patient->relation = $request->relation;
        $patient->tariff_id = $request->agency;
        $patient->save();

        if($patient->relation == "CardOwner"){
            $card=Card::where('patient_id',$patient->id)->first();
            $card->salutation = $request->salutation;
            $card->name = $request->full_name;
            $card->ic_no = $request->identification;
            $card->save();
        }

        if (!empty($patient->ic_original_filename)) {
            if ($request->hasFile('ic_attach')) {
                //Get filename with extension
                $fileNameWithExt = $request->file('ic_attach')->getClientOriginalName();
                //Get just filename
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                //Get just extensions
                $extension = $request->file('ic_attach')->getClientOriginalExtension();
                // Filename to store
                $fileNameToStore = $fileName . '.' . $extension;
                //Upload file
                $path = $request->file('ic_attach')->storeAs('public/ic-attachment/' . $patient->identification . '/', $fileNameToStore);
                $document_path = 'public/ic-attachment/' . $patient->identification . '/' . $fileNameToStore;

                // store IC in database
                $patient->id = $patient->id;
                $patient->ic_original_filename = $fileNameToStore;
                $patient->ic_document_path = $document_path;
                $patient->save();
            }
        } else {
            if ($request->hasFile('ic_attach')) {
                //Get filename with extension
                $fileNameWithExt = $request->file('ic_attach')->getClientOriginalName();
                //Get just filename
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                //Get just extensions
                $extension = $request->file('ic_attach')->getClientOriginalExtension();
                // Filename to store
                $fileNameToStore = $fileName . '.' . $extension;
                //Upload file
                $path = $request->file('ic_attach')->storeAs('public/ic-attachment/' . $patient->identification . '/', $fileNameToStore);
                $document_path = 'public/ic-attachment/' . $patient->identification . '/' . $fileNameToStore;

                // store IC in database
                $patient->ic_original_filename = $fileNameToStore;
                $patient->ic_document_path = $document_path;
                $patient->save();
            }
        }

        return redirect()->action('PatientController@patient_detail', ['patient' => $patient->id])->with(['status' => true, 'message' => 'Update Sucessfully !']);
    }

    public function downloadICAttachment($id)
    {
        $patient_ic_attach = Patient::findorfail($id);
        if (!empty($patient_ic_attach)) {
            if (!empty($patient_ic_attach->ic_document_path)) {
                $contents = Storage::get($patient_ic_attach->ic_document_path);
                $ext = pathinfo($patient_ic_attach->ic_document_path, PATHINFO_EXTENSION);
                $resp = response($contents)->header('Content-Type', $this->getMimeType($patient_ic_attach->ic_document_path));
                $resp->header('Content-Disposition', 'inline; filename="' . $patient_ic_attach->ic_original_filename . '.' . $ext .   '"');
                return $resp;
            }
        }
        return null;
    }

    public function downloadSLAttachment($id)
    {
        $patient_attachment = PatientAttachment::findorfail($id);
        if (!empty($patient_attachment)) {
            if (!empty($patient_attachment->sl_document_path)) {
                $contents = Storage::get($patient_attachment->sl_document_path);
                $ext = pathinfo($patient_attachment->sl_document_path, PATHINFO_EXTENSION);
                $resp = response($contents)->header('Content-Type', $this->getMimeType($patient_attachment->sl_document_path));
                $resp->header('Content-Disposition', 'inline; filename="' . $patient_attachment->sl_original_filename . '.' . $ext .   '"');
                return $resp;
            }
        }
        return null;
    }

    public function update_ic_attach(Request $request, Patient $patient)
    {
        if ($request->get('type') == 'ic') {
            if ($request->hasFile('ic_attach')) {
                //Get filename with extension
                $fileNameWithExt = $request->file('ic_attach')->getClientOriginalName();
                //Get just filename
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                //Get just extensions
                $extension = $request->file('ic_attach')->getClientOriginalExtension();
                // Filename to store
                $fileNameToStore = $fileName . '.' . $extension;
                // File to delete
                unlink(storage_path('app/public/ic-attachment/' . $patient->identification . '/' . $patient->ic_original_filename));
                // Storage::delete($attachment->ic_original_filename);
                //Upload file
                $path = $request->file('ic_attach')->storeAs('public/ic-attachment/' . $patient->identification . '/', $fileNameToStore);
                $document_path = 'public/ic-attachment/' . $patient->identification . '/' . $fileNameToStore;
                // store IC in database
                $patient->ic_original_filename = $fileNameToStore;
                $patient->ic_document_path = $document_path;
                $patient->save();

                return redirect()->action('PatientController@patient_detail', ['patient' => $patient->id])->with(['status' => true, 'message' => 'Attachment Update!']);
            }
        } elseif ($request->get('type') == 'icnew') {
            if ($request->hasFile('ic_new')) {
                //Get filename with extension
                $fileNameWithExt = $request->file('ic_new')->getClientOriginalName();
                //Get just filename
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                //Get just extensions
                $extension = $request->file('ic_new')->getClientOriginalExtension();
                // Filename to store
                $fileNameToStore = $fileName . '.' . $extension;
                //Upload file
                $path = $request->file('ic_new')->storeAs('public/ic-attachment/' . $patient->identification . '/', $fileNameToStore);
                $document_path = 'public/ic-attachment/' . $patient->identification . '/' . $fileNameToStore;
                // store IC in database
                $patient->ic_original_filename = $fileNameToStore;
                $patient->ic_document_path = $document_path;
                $patient->save();

                return redirect()->action('PatientController@patient_detail', ['patient' => $patient->id])->with(['status' => true, 'message' => 'Attachment Update!']);
            }
        }

        $patientAttachment = new PatientAttachment();

        if ($request->get('type') == 'sl') {
            if ($request->hasFile('sl_attach')) {
                //Get filename with extension
                $fileNameWithExt = $request->file('sl_attach')->getClientOriginalName();
                //Get just filename
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                //Get just extensions
                $extension = $request->file('sl_attach')->getClientOriginalExtension();
                // Filename to store
                $fileNameToStore = $fileName . '.' . $extension;
                //Upload file
                $path = $request->file('sl_attach')->storeAs('public/support-letter/' . $patient->identification . '/', $fileNameToStore);
                $document_path = 'public/support-letter/' . $patient->identification . '/' . $fileNameToStore;
                // store IC in database
                $patientAttachment->patient_id = $patient->id;
                $patientAttachment->sl_original_filename = $fileNameToStore;
                $patientAttachment->sl_document_path = $document_path;
                $patientAttachment->save();

                return redirect()->action('PatientController@patient_detail', ['patient' => $patient->id])->with(['status' => true, 'message' => 'Attachment Update!']);
            }
        }
    }

    public function deleteAttachment(PatientAttachment $attachment)
    {
        // dd($attachment->patient_id);
        $patient = Patient::where('id', $attachment->patient_id)->first();
        unlink(storage_path('app/public/support-letter/' . $patient->identification . '/' . $attachment->sl_original_filename));
        $attachment->delete();
        return redirect()->action('PatientController@patient_detail', ['patient' => $patient->id])->with(['status' => true, 'message' => 'Attachment Delete!']);
    }

    public function print_jhev()
    {
        return view('print.print1');
    }

    public function download_jhev($id)
    {

        $patient = Patient::find($id);

        $pdf = PDF::loadView('print.print1', compact('patient'));
        return $pdf->stream('patient.pdf');
        // return view('print.print1', compact('patient'));
    }

    public function show()
    {
        return view('patients.patients_view');
    }

    public function update_card_owner(Patient $patient)
    {
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        return view('patients.registrations.update_card', [
            'patient'  => $patient,
            'roles' => $roles,
            'relations' => $relation = Patient::where('card_id', $patient->card_id)->get()
        ]);
    }

    public function store_card_owner(Request $request, Patient $patient)
    {
        $card = Card::where('id', $patient->card_id)->first();
        // dd($request->all());
        $card->salutation = $request->salutation;
        $card->name = $request->full_name;
        $card->ic_no  = $request->identification;
        $card->type = $request->card_type;
        $card->army_type = $request->army_type;
        $card->army_pension = $request->army_pension;
        $card->remark = $request->remark;
        $card->save();

        $patient=Patient::where('id', $patient->id)->first();
        $patient->salutation= $request->salutation;
        $patient->full_name = $request->full_name;
        $patient->identification  = $request->identification;
        $patient->save();


        return redirect()->action('PatientController@update_card_owner', [
            'patient'  => $patient,
            'relations' => $relation = Patient::where('card_id', $patient->card_id)->get(),
        ])->with(['status' => true, 'message' => 'Card Owner  Updated !']);
    }

    public function getPatients($id)
    {
        $empData['data'] = Patient::where('id', $id)->get();
        return response()->json($empData);
    }


    public function register_relation(Patient $patient)
    {
        $roles = DB::table('model_has_roles')->join('users', 'model_has_roles.model_id', '=', 'users.id')->where("users.id", auth()->id())->first();
        $states = State::all();
        return view('patients.registrations.relation', [
            'patient' => $patient,
            'states' => $states,
            'roles' => $roles
        ]);
    }

    public function store_relation(Request $request, Patient $patient)
    {
        // dd($request->relation);
        $new =  Patient::create([
            'full_name' => $request->full_name,
            'salutation' => $request->salutation,
            'identification' =>  $request->identification,
            'email' => $request->email,
            'date_of_birth' => $request->dob,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'address_1' => $request->address_1,
            'address_2' => $request->address_2,
            'address_3' => $request->address_3,
            'postcode' => $request->postcode,
            'city' => $request->city,
            'state_id' => $request->state,
            'confirmation' => 1,
            'relation' => $request->relation,
            'tariff_id' => $request->tariff,
            'card_id' => $patient->card_id,
        ]);

        if ($request->hasFile('ic_attach')) {
            //Get filename with extension
            $fileNameWithExt = $request->file('ic_attach')->getClientOriginalName();
            //Get just filename
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            //Get just extensions
            $extension = $request->file('ic_attach')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $fileName . '.' . $extension;
            //Upload file
            $path = $request->file('ic_attach')->storeAs('public/ic-attachment/' . $new->identification . '/', $fileNameToStore);
            $document_path = 'public/ic-attachment/' . $new->identification . '/' . $fileNameToStore;

            // store IC in database
            $new->ic_original_filename = $fileNameToStore;
            $new->ic_document_path = $document_path;
            $new->save();
        }

        //SLAttachment
        if ($request->hasFile('sl_attach')) {
            foreach ($request->file('sl_attach') as $file) {
                $patient_attachment = new PatientAttachment();
                $patient_attachment->patient_id = $new->id;
                //Get filename with extension
                $fileNameWithExt = $file->getClientOriginalName();
                //Get just filename
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                //Get just extensions
                $extension = $file->getClientOriginalExtension();
                // Filename to store
                $fileNameToStore = $fileName . '.' . $extension;
                //Upload file
                $path = $file->storeAs('public/support-letter/' . $new->identification . '/', $fileNameToStore);
                $document_path = 'public/support-letter/' . $new->identification . '/' . $fileNameToStore;

                // store sl in database
                $patient_attachment->sl_original_filename = $fileNameToStore;
                $patient_attachment->sl_document_path = $document_path;
                $patient_attachment->save();
            }
        }
        return redirect()->action('PatientController@index')->with(['status' => true, 'message' => 'Register Sucessfully !']);
    }
}
