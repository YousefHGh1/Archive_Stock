<?php
namespace App\Http\Controllers;

use App\Models\Legal;
use App\Models\LegalNot;
use App\Models\User;
use App\Notifications\CreatelegalNot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class LegalNotController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:legal', ['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy']]);
    }

    public function index()
    {
        //
        $user     = User::all();
        $legal    = Legal::all();
        $legalNot = LegalNot::all();
        return view('sendNotification.legalindex', compact('user', 'legal', 'legalNot'));
    }

    public function create()
    {
        //
        $user     = User::all();
        $legal    = Legal::all();
        $legalNot = LegalNot::all();
        return view('sendNotification.legalNot', compact('user', 'legal', 'legalNot'));

    }

    public function store(Request $request)
    {
        //
        $request->validate([
            'sender'      => 'required',
            'reciver'     => 'required',
            'file'        => 'required',
            'description' => 'required',
        ]);

        $legalNot = new LegalNot();

        $legalNot->id     = $request->id;
        $legalNot->sender = Auth::user()->name;

        $recipients        = $request->input('reciver');
        $recipients        = is_array($recipients) ? $recipients : [$recipients];
        $recipientNames    = User::whereIn('name', $recipients)->pluck('employee_name')->implode(", ");
        $legalNot->reciver = $recipientNames;

        $file  = $request->file;
        $file1 = time() . '.' . $file->getClientoriginalExtension();
        $request->file->move('filelegalNot', $file1);
        $legalNot->file        = $file1;
        $legalNot->description = $request->description;
        // ************************{متغيرات الاشعارات}*********************************
        $users       = User::whereIn('name', $recipients)->get();
        $user_create = auth()->user()->name;
        Notification::send($users, new CreatelegalNot($legalNot->id, $legalNot->file, $legalNot->description, $user_create));
        // ****************************************************************************

        if ($legalNot->save()) {
            return redirect('sendNotification/legalNot')->with('success', 'تمت الارسال بنجاح!');
        } else {
            return redirect('legalNot.create')->with('warning', '  تحقق من صحة البيانات!');
        }

    }

    public function show($id)
    {
        //
        $legalNot = LegalNot::find($id);

        return view('sendNotification.legalshow', compact('legalNot'))->with('legalNot', $legalNot);
    }

    public function destroy($id)
    {
        //
        LegalNot::destroy($id);
        return redirect('sendNotification/legalNot')->with('error', 'تمت الحذف بنجاح!');
    }

}
