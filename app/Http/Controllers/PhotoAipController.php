<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;

class PhotoAipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $photo = Photo::latest('id')->paginate(10);
        return response()->json($photo);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "product_id" => "required|exists:products,id",
            "photos" => "required",
            "photos.*" => "file|mimes:jpeg,png|max:512",
        ]);
        foreach ($request->file('photos') as $photo) {
            $newName = $photo->store('public/');
            Photo::create([
                "product_id" => $request->product_id,
                "name" => $newName
            ]);
        }
        return response()->json([
            "message" => "Upload Photo Success"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $photo = Photo::find($id);
        if (is_null($photo)) {
            return response()->json(["message" => "photo not found."], 404);
        }
        return response()->json($photo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $photo = Photo::find($id);
        if (is_null($photo)) {
            return response()->json(["message" => "product not found."], 404);
        }
        $photo->delete();
        return response()->json(["message" => "product is deleted."]);
    }
}
