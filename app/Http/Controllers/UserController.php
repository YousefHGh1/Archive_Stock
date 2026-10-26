<?php
namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\SubSection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// use Illuminate\Validation\Rules;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:users-read', ['only' => ['index', 'show']]);
        $this->middleware('permission:users-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:users-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:users-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $user = auth()->user();

        // Filter users based on section access
        if ($user->can('view-all-sections') || $user->hasRole(['admin', 'Super-User'])) {
            $users      = User::with('section', 'subSection')->orderBy('id', 'desc')->paginate(10);
            $section    = Section::all();
            $subSection = SubSection::all();
            $sectionCount = Section::count();
            $subSectionCount = SubSection::count();
        } elseif ($user->can('view-own-section') && $user->section_id) {
            $users = User::with('section', 'subSection')
                ->where('section_id', $user->section_id)
                ->orderBy('id', 'desc')
                ->paginate(10);
            $section    = Section::where('id', $user->section_id)->get();
            $subSection = SubSection::where('section_id', $user->section_id)->get();
            $sectionCount = Section::where('id', $user->section_id)->count();
            $subSectionCount = SubSection::where('section_id', $user->section_id)->count();
        } else {
            $users      = User::where('id', $user->id)->orderBy('id', 'desc')->paginate(10);
            $section    = Section::where('id', $user->section_id)->get();
            $subSection = SubSection::where('id', $user->sub_section_id)->get();
            $sectionCount = Section::where('id', $user->section_id)->count();
            $subSectionCount = SubSection::where('id', $user->sub_section_id)->count();
        }

        return view('user.index', compact('users', 'section', 'subSection', 'sectionCount', 'subSectionCount'));
    }

    public function create()
    {
        $user = auth()->user();

        if ($user->can('view-all-sections') || $user->hasRole(['admin', 'Super-User'])) {
            $section    = Section::all();
            $subSection = SubSection::all();
        } elseif ($user->can('view-own-section') && $user->section_id) {
            $section    = Section::where('id', $user->section_id)->get();
            $subSection = SubSection::where('section_id', $user->section_id)->get();
        } else {
            $section    = Section::where('id', $user->section_id)->get();
            $subSection = SubSection::where('id', $user->sub_section_id)->get();
        }

        return view('user.create', compact('section', 'subSection'));
    }

    public function store(Request $request)
    {
        //
        $request->validate([
            'name'           => 'required',
            'email'          => 'required',
            'password'       => 'required',
            'section_id'     => 'required',
            'sub_section_id' => 'required',
            'employee_name'  => 'required',
        ]);

        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'section_id'     => $request->section_id,
            'sub_section_id' => $request->sub_section_id,
            'employee_name'  => $request->employee_name,

        ]);

        if ($user->save()) {
            return redirect('user/create')->with('success', 'تمت الاضافة بنجاح!');
        } else {
            return redirect('user/create')->with('warning', '  تحقق من صحة البيانات!');
        }
    }

    public function show(User $user)
    {
        //
    }

    public function edit($id)
    {
        $currentUser = auth()->user();
        $user        = User::find($id);

        // Check if user can edit this user based on section access
        if (! $currentUser->canManageSection($user->section_id)) {
            abort(403, 'ليس لديك صلاحية لتعديل هذا المستخدم.');
        }

        if ($currentUser->can('view-all-sections') || $currentUser->hasRole(['admin', 'Super-User'])) {
            $section    = Section::all();
            $subSection = SubSection::all();
        } elseif ($currentUser->can('view-own-section') && $currentUser->section_id) {
            $section    = Section::where('id', $currentUser->section_id)->get();
            $subSection = SubSection::where('section_id', $currentUser->section_id)->get();
        } else {
            $section    = Section::where('id', $currentUser->section_id)->get();
            $subSection = SubSection::where('id', $currentUser->sub_section_id)->get();
        }

        return view('user.edit', compact('user', 'subSection', 'section'));
    }

    public function update(Request $request, $id)
    {
        $currentUser = auth()->user();
        $user        = User::find($id);

        // Check if user can update this user based on section access
        if (! $currentUser->canManageSection($user->section_id)) {
            abort(403, 'ليس لديك صلاحية لتعديل هذا المستخدم.');
        }

        $user->name          = $request->get('name');
        $user->employee_name = $request->get('employee_name');

        // Only allow section changes if user has manage-all-sections permission
        if ($currentUser->can('manage-all-sections') || $currentUser->hasRole(['admin', 'Super-User'])) {
            $user->section_id     = $request->get('section_id');
            $user->sub_section_id = $request->get('sub_section_id');
        }

        if ($user->save()) {
            return redirect('user')->with('info', 'تمت التعديل بنجاح!');
        } else {
            return redirect('user')->with('warning', '  تحقق من صحة البيانات!');
        }
    }

    public function destroy(User $user)
    {
        //
    }

    public function getsub_section($id)
    {
        $subSection = DB::table("sub_sections")->where("section_id", $id)->pluck("name", "id");
        return json_encode($subSection);
    }
}
