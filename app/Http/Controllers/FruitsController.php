<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFruitsRequest;
use App\Http\Requests\UpdateFruitsRequest;
use App\Models\Fruits;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class FruitsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFruitsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFruitsRequest $request)
    {

        $validation= Validator::make($request->all(),[
           "name" => "required|min:3",
           "price" =>"required|integer|min:100",
            "photo" => "required|file|mimes:jpeg,png"
        ]);

        $photo=$request->file('photo');
        $newName = uniqid()."_photo.".$photo->extension();
        $photo->storeAs('public/photo',$newName);

        $img=Image::make($photo);
        $img->fit(300,300)->save('storage/thumbnail/'.$newName);



        if ($validation->fails()){
            return response()->json([
                'status' => "fall",
                "errors" => $validation->errors()
            ]);
        }
        $fruit=new Fruits();
        $fruit->name =$request->name;
        $fruit->price=$request->price;
        $fruit->photo=$newName;
        $fruit->save();

        $fruit->original_photo = asset('storage/photo/'.$fruit->photo);
        $fruit->thumbnail = asset('storage/thumbnail/'.$fruit->photo);
        $fruit->time =$fruit->created_at->diffForHumans();



        return response()->json([
            'status' => "success",
            "info" => $fruit,
        ]);


        return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fruits  $fruits
     * @return \Illuminate\Http\Response
     */
    public function show(Fruits $fruit)
    {
        $fruit->original_photo = asset('storage/photo/'.$fruit->photo);
        $fruit->thumbnail = asset('storage/thumbnail/'.$fruit->photo);
        return $fruit;

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fruits  $fruits
     * @return \Illuminate\Http\Response
     */
    public function edit(Fruits $fruits)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFruitsRequest  $request
     * @param  \App\Models\Fruits  $fruits
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFruitsRequest $request, Fruits $fruit)
    {


        $validation= Validator::make($request->all(),[
            "name" => "required|min:3",
            "price" =>"required|integer|min:100",
            "photo" => "nullable|file|mimes:jpeg,png"
        ]);

        if ($validation->fails()){
            return response()->json([
                'status' => "fall",
                "errors" => $validation->errors()
            ]);
        }


        if ($request->hasFile("photo")){
            $photo=$request->file('photo');
            $newName = uniqid()."_photo.".$photo->extension();
            $photo->storeAs('public/photo',$newName);

            $img=Image::make($photo);
            $img->fit(300,300)->save('storage/thumbnail/'.$newName);
        }



        $fruit->name =$request->name;
        $fruit->price=$request->price;

        if ($request->hasFile("photo")){
            $fruit->photo=$newName;
        }


        $fruit->save();

        $fruit->original_photo = asset('storage/photo/'.$fruit->photo);
        $fruit->thumbnail = asset('storage/thumbnail/'.$fruit->photo);
        $fruit->time =$fruit->created_at->diffForHumans();



        return response()->json([
            'status' => "success",
            "info" => $fruit,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fruits  $fruits
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fruits $fruit)
    {
        $fruit->delete();
        return response()->json([
           "status" => "success",
            "info" => "Delete Successful"
        ]);

    }
}
