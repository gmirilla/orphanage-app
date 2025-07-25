<?php

namespace App\Http\Controllers;

use App\Models\accomodationrecord;
use App\Models\backgroundrecord;
use App\Models\Child;
use App\Models\country;
use App\Models\Development;
use App\Models\Education;
use App\Models\Medicalrecord;
use Illuminate\Http\Request;

class ChildController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $children=Child::all();

        return view('children.listchildren', compact('children'));
    }

        /**
     * User clicked the Register new Child button. Begin process of registering new Child
     */
    public function registernew()
    {

        $countries=country::all();

        return view('children.addchild', compact('countries'));
    }

        /**
     *  Register/Modify  Child Basic Info.
     */
    public function addbasicinfo(Request $request)
    {

        $child= Child::create($request->all());


    //profilepicture do not override if empty
       if (!empty($request->profilepicture)) {
        # code...
        $request->validate(['profilepicture' => 'image|max:4096',]);
        $fpath=$request->file('profilepicture')->store('profilepictures/children','public');
        $child->profilephoto=$fpath;
       }

       $child->save();


        return view('children.addchild_education', compact('child'));
    }

    /**
     * Add the Education/development info to a child being registered.
     */
    public function addeducationinfo(Request $request)
    {
        //
        try {
            //code...
            $childeducation=Education::create($request->all());
            $childdevelopment=Development::create($request->all());
            $childeducation->save();
            $childdevelopment->save();
            $child=Child::where('id',$request->child_id)->first();
        } catch (\Throwable $th) {
            //throw $th;
        }

        if($request->has('addeducationrecord')){

            return view('children.viewchild',compact('child'));
        }

        return view('children.addchild_health', compact('child'));
    }
    
    /**
     * Add the Medical info to a child being registered.
     */
    public function addmedicalinfo(Request $request)
    {
        //
        try {
            //code...
            $childmedical=Medicalrecord::create($request->all());
            $childmedical->save();
            $child=Child::where('id',$request->child_id)->first();
        } catch (\Throwable $th) {
            //throw $th;
        }

        if($request->has('addmedicalrecord')){

            return view('children.viewchild',compact('child'));
        }

        return redirect()->route('list_children');
    }


        /**
     * Add the Accomodation  info to a child being registered.
     */
    public function addlivinginfo(Request $request)
    {
        //
        try {
            //code...
            $childaccomodation=accomodationrecord::create($request->all());
            $childaccomodation->save();
            
        } catch (\Throwable $th) {
            //throw $th;
        }
        
        $child=Child::where('id',$request->child_id)->first();
        if($request->has('addaccomodationrecord')){

            return view('children.viewchild',compact('child'));
        }      

        return redirect()->route('list_children');

    }

    
        /**
     * Add the Background  info to a child being registered.
     */
    public function addbkgrdinfo(Request $request)
    {
        //
        try {
            //code...
            $childbackground=backgroundrecord::create($request->all());
            $childbackground->save();
            
        } catch (\Throwable $th) {
            //throw $th;
        }
        $child=Child::where('id',$request->child_id)->first();
        if($request->has('addbackgroundinforecord')){

            return view('children.viewchild',compact('child'));
        }      

        return redirect()->route('list_children');

    }

        /**
     * Display the specified resource.
     */
    public function viewchild(Request $request)
    {
        //
        $child=Child::where('id', $request->child_id)->first();

        return view('children.viewchild', compact('child'));
        
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Child $child)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Child $child)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Child $child)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Child $child)
    {
        //
    }
}
