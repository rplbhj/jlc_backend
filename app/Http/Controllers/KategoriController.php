<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function getuser()
    {
        $kategori = Kategori::all();

        if ($kategori) {
            return response()->json([
                'status' => true,
                'message' => 'Data di temukan',
                'kategori' => $kategori
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak di temukan',
            ], 400);
        }
    }

    public function index()
    {
        $kategori = Kategori::all();

        if ($kategori) {
            return response()->json([
                'status' => true,
                'message' => 'Data di temukan',
                'kategori' => $kategori
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak di temukan',
            ], 400);
        }
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name_kategori' => 'required|min:3|max:255',
            'description_kategori' =>  'required|min:5|max:255',
            'cover_image_kategori' =>  'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validation->errors(),
            ], 400);
        }

        $image = $request->file('cover_image_kategori');
        $image->storeAs('public/cover_image_kategori', $image->hashName());

        Kategori::create([
            'name_kategori' => $request->name_kategori,
            'description_kategori' =>  $request->description_kategori,
            'cover_image_kategori' =>  $image->hashName(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data di tambahkan',
        ], 200);
    }


    public function show($id)
    {
        $kategori = Kategori::where('id_kategori', $id)->first();

        if ($kategori) {
            return response()->json([
                'status' => true,
                'message' => 'Data di temukan',
                'kategori' => $kategori
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak di temukan',
            ], 400);
        }
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name_kategori' => 'required|max:255',
            'description_kategori' =>  'required|min:5|max:255',
            'cover_image_kategori' =>  '',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find post by ID
        $kategori = Kategori::where('id_kategori', $id)->first();

        //check if image is not empty
        if ($request->hasFile('cover_image_kategori')) {

            //upload image
            $image = $request->file('cover_image_kategori');
            $image->storeAs('public/cover_image_kategori', $image->hashName());

            //delete old image
            Storage::delete('public/cover_image_kategori/' . basename($kategori->cover_image_kategori));

            //update post with new image
            Kategori::where('id_kategori', $id)->update([
                'name_kategori' => $request->name_kategori,
                'description_kategori' =>  $request->description_kategori,
                'cover_image_kategori' => $image->hashName(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil di update',
            ], 200);
        } else {

            //update post without image
            Kategori::where('id_kategori', $id)->update([
                'name_kategori' => $request->name_kategori,
                'description_kategori' =>  $request->description_kategori,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil di update',
            ], 200);
        }
    }


    public function destroy($id)
    {
        $kategori = Kategori::where('id_kategori', $id)->first();

        if (!$kategori) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak di temukan',
            ], 401);
        }

        Storage::delete('public/cover_image_kategori/' . basename($kategori->cover_image_kategori));

        Kategori::where('id_kategori', $id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil di hapus',
        ], 200);
    }
}
