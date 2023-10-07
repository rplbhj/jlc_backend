<?php

namespace App\Http\Controllers;

use App\Models\ImagesMultiple;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    public function getProductuser()
    {
        $product = Product::orderBy('id_product', 'DESC');
        return response()->json([
            'product' => $product->get()
        ]);
    }
    public function index(Request $request)
    {
        $query = $request->q;
        $results = Product::orderBy('id_product', 'DESC')->where('name_product', 'like', '%' . $query . '%')->orWhere('status', 'like', '%' . $query . '%')->orWhere('type_car', 'like', '%' . $query . '%')->paginate(12);

        return response()->json([
            'product' => $results
        ]);
    }

    public function store(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'name_product' => 'required|min:5|max:255',
            'description_product' =>  'required|min:5',
            'harga_product' =>  'required|numeric',
            'id_kategori' =>  'required',
            'cover_image_product' =>  'required|image',
            'type_car' => 'required|max:255',
            'nik_car' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validation->errors(),
            ], 400);
        }

        $image = $request->file('cover_image_product');
        $path = $image->storeAs('public/cover_image_product', $image->hashName());
        $img = Image::make($image);
        $img->save(storage_path('app/' . $path), 80);

        $pro = new Product;
        $pro->name_product = $request->name_product;
        $pro->description_product =  $request->description_product;
        $pro->harga_product =  $request->harga_product;
        $pro->id_kategori =  $request->id_kategori;
        $pro->cover_image_product =  $image->hashName();
        $pro->type_car = $request->type_car;
        $pro->nik_car = $request->nik_car;
        $pro->status = 'ready';
        $pro->save();

        foreach ($request->file('images') as $imagefile) {
            $path = $imagefile->storeAs('public/multiple_images', $imagefile->hashName());
            $img = Image::make($imagefile);
            $img->save(storage_path('app/' . $path), 80);

            $imageMultiple = new ImagesMultiple;
            $imageMultiple->images_multiple = $imagefile->hashName();
            $imageMultiple->product_id = $pro->id;
            $imageMultiple->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Data di tambahkan',
        ], 200);
    }

    public function show($id)
    {
        $product = Product::where('id_product', $id)->first();

        $ori = DB::table('products')->join('kategoris', 'products.id_kategori', '=', 'kategoris.id_kategori')->select('products.*', 'kategoris.*')->where('id_product', $id)->first();
        $image = ImagesMultiple::where('product_id', $id)->get();

        if ($product) {
            return response()->json([
                'status' => true,
                'message' => 'Data di temukan',
                'product' => $ori,
                'image' => $image
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
            'name_product' => 'required|min:5|max:255',
            'description_product' =>  'required|min:5',
            'harga_product' =>  'required|max:255',
            'id_kategori' =>  'required',
            'type_car' => 'required',
            'nik_car' => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find post by ID
        $product = Product::where('id_product', $id)->first();

        //check if image is not empty
        if ($request->hasFile('cover_image_product')) {

            //upload image
            $image = $request->file('cover_image_product');
            $image->storeAs('public/cover_image_product', $image->hashName());

            //delete old image
            Storage::delete('public/cover_image_product/' . basename($product->cover_image_product));

            //update post with new image
            Product::where('id_product', $id)->update([
                'name_product' => $request->name_product,
                'description_product' =>  $request->description_product,
                'harga_product' =>  $request->harga_product,
                'id_kategori' =>  $request->id_kategori,
                'cover_image_product'     => $image->hashName(),
                'type_car' => $request->type_car,
                'nik_car' => $request->nik_car,
                'status' => $request->status
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil di update',
            ], 200);
        } else {

            //update post without image
            Product::where('id_product', $id)->update([
                'name_product' => $request->name_product,
                'description_product' =>  $request->description_product,
                'harga_product' =>  $request->harga_product,
                'id_kategori' =>  $request->id_kategori,
                'type_car' => $request->type_car,
                'status' => $request->status,
                'nik_car' => $request->nik_car,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil di update',
        ], 200);
    }

    public function destroy($id)
    {
        $product = Product::where('id_product', $id)->first();
        $iamgemultiple = ImagesMultiple::where('product_id', $id)->get();

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak di temukan',
            ], 400);
        }

        Storage::delete('public/cover_image_product/' . basename($product->cover_image_product));

        foreach ($iamgemultiple as $images) {
            Storage::delete('public/multiple_images/' . basename($images->images_multiple));
        }

        Product::where('id_product', $id)->delete();
        ImagesMultiple::where('product_id', $id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil di hapus',
        ], 200);
    }

    public function post(Request $request)
    {
        // $image = $request->file('image');
        // $path = $image->storeAs('public/test_image', $image->hashName());
        // $img = Image::make($image);
        // $img->save(storage_path('app/' . $path), 80);

        // return response()->json([
        //     'status' => true
        // ]);


        // Loop through each uploaded image and compress it
        foreach ($request->file('images') as $image) {
            // Define the path to save the compressed image
            $path = $image->storeAs('public/test_image', $image->hashName());

            // Create an Intervention Image instance
            $img = Image::make($image);

            // Compress the image (adjust the quality as needed)
            $img->save(storage_path('app/' . $path), 80); // 80 is the image quality (adjust as needed)

            // You can save the path or any other information in your database here
            // For example, you might want to save $path in your database.
        }

        // Move the JSON response outside the loop to send it after all images are processed
        return response()->json([
            'status' => true
        ]);
    }
}
