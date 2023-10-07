<?php

namespace App\Http\Controllers;

use App\Models\ImagesMultiple;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImagesMultipleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        foreach ($request->file('images') as $imagefile) {
            $imagefile->storeAs('public/multiple_images', $imagefile->hashName());
            $imageMultiple = new ImagesMultiple;
            $imageMultiple->images_multiple = $imagefile->hashName();
            $imageMultiple->product_id = $request->product_id;
            $imageMultiple->save();
        }

        return response()->json([
            'message' => 'Berhasil'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(ImagesMultiple $imagesMultiple)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ImagesMultiple $imagesMultiple)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ImagesMultiple $imagesMultiple)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $iamgemultiple = ImagesMultiple::find($id);
        Storage::delete('public/multiple_images/' . basename($iamgemultiple->images_multiple));
        $iamgemultiple->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil di hapus',
        ], 200);
    }
}
