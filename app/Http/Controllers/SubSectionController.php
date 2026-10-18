<?php
namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\SubSection;
use Illuminate\Http\Request;

class SubSectionController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:sections', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']]);
    }

    public function index()
    {
        //
        $subSection = SubSection::all();
        return view('subsection.index', compact('subSection'));
    }

    public function create()
    {
        //
        $section    = Section::all();
        $subSection = SubSection::all();
        return view('subsection.create', compact('subSection', 'section'));
    }

    public function store(Request $request)
    {
        //
        $request->validate([
            'name'       => 'required',
            'section_id' => 'required',
        ]);
        $subSection             = new SubSection();
        $subSection->name       = $request->name;
        $subSection->section_id = $request->section_id;

        if ($subSection->save()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'id' => $subSection->id,
                    'section_id' => $subSection->section_id,
                    'name' => $subSection->name,
                ], 201);
            }

            return redirect()->back()->with('success', 'تمت الاضافة بنجاح!');
        }
    }

    public function show($id)
    {
        //
        $subSection = SubSection::find($id);
        return view('subsection.show')->with('subSection', $subSection);
    }

    public function edit($id)
    {
        //
        $subSection = SubSection::find($id);
        $section    = Section::all();
        return view('subsection.edit', compact('subSection', 'section'))->with('subSection', $subSection);
    }

    public function update(Request $request, $id)
    {
        //
        $subSection             = SubSection::find($id);
        $subSection->name       = $request->name;
        $subSection->section_id = $request->section_id;

        $subSection->save();
        return redirect('subSection')->with('info', 'تمت التعديل بنجاح!');
    }

    public function destroy($id)
    {
        //
        SubSection::destroy($id);
        return redirect('subSection')->with('danger', 'تمت الحذف بنجاح!');
    }
}
