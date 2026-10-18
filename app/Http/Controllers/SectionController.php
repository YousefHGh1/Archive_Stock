<?php
namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:sections-read', ['only' => ['index', 'show']]);
        $this->middleware('permission:sections-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:sections-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:sections-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        //

        $section = Section::all();
        return view('section.index', compact('section'));
    }

    public function create()
    {
        //
        $lastSection = Section::latest()->value('num_section') ?? 0;
        $newSection  = $lastSection + 1;

        return view('section.create',compact('newSection'));
    }

    public function store(Request $request)
    {
        //
        $request->validate([
            // 'num_section' => 'required',
            'name_section' => 'required',
        ]);

        // الحصول على آخر رقم فاتورة وزيادته
        $lastSection = Section::max('num_section');
        $newSection  = $lastSection ? $lastSection + 1 : 1; // يبدأ من 100000

        $section               = new Section();
        $section->num_section  = $newSection;
        $section->name_section = $request->name_section;

        if ($section->save()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'id' => $section->id,
                    'name_section' => $section->name_section,
                    'num_section' => $section->num_section,
                ], 201);
            }

            return redirect()->back()->with('success', 'تمت الاضافة بنجاح!');
        }
    }

    public function show($id)
    {
        //
        $section = Section::find($id);
        return view('section.show')->with('section', $section);
    }

    public function edit($id)
    {
        //
        $section = Section::find($id);
        return view('section.edit', compact('section'))->with('section', $section);
    }

    public function update(Request $request, $id)
    {
        //
        $section               = Section::find($id);
        $section->num_section  = $request->num_section;
        $section->name_section = $request->name_section;

        $section->save();
        return redirect('section')->with('info', 'تمت التعديل بنجاح!');
    }

    public function destroy($id)
    {
        //
        Section::destroy($id);
        return redirect('section')->with('danger', 'تمت الحذف بنجاح!');
    }
}
