<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class CameraController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        return view('presensi');
    }

   /**
     * Write code on Method
     *
     * @return response()
     */
    // public function store(Request $request)
    // {
    //     // Capture and upload image code
    //     $img = $request->image;
    //     $folderPath = "public/";
    //     $image_parts = explode(";base64,", $img);
    //     $image_type_aux = explode("image/", $image_parts[0]);
    //     $image_type = $image_type_aux[1];
    //     $image_base64 = base64_decode($image_parts[1]);
    //     $rand_code = Str::random(6);
    //     $fileName = time() . $rand_code . '.png';
    //     $file = $folderPath . $fileName;

    //    // Store the file in the public disk
    //     Storage::disk('public')->put($fileName, $image_base64);
    //     // Get the public URL of the uploaded file
    //     $publicUrl = Storage::url($fileName);
    //     $id = Photo::create([
    //         // 'name'  =>  $request->name,
    //         // 'email' =>  $request->email,
    //         // 'password' => Hash::make($request->password),
    //         'photo_name' => $fileName
    //     ])->id;
    //     return redirect('photo/'.$id);
    // }
    
public function store(Request $request)
{
    // Validate the request to ensure the image is provided
    $request->validate([
        'image' => 'required|string',
    ]);

    $img = $request->image;
    $folderPath = "public/";

    // Check if the input contains ";base64," to avoid undefined array index error
    if (!str_contains($img, ';base64,')) {
        return response()->json(['error' => 'Invalid image format'], 400);
    }

    // Split the base64 string into type and data
    $image_parts = explode(";base64,", $img);
    if (count($image_parts) < 2) {
        return response()->json(['error' => 'Invalid base64 format'], 400);
    }

    // Extract the image type (e.g., png, jpg)
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1] ?? 'png'; // Default to 'png' if type is not detected

    // Decode the base64 data
    $image_base64 = base64_decode($image_parts[1]);

    // Generate a unique file name
    $rand_code = Str::random(6);
    $fileName = time() . $rand_code . '.' . $image_type;

    // Save the file to the storage/public folder
    Storage::disk('public')->put($fileName, $image_base64);

    // Get the public URL of the uploaded file
    $publicUrl = Storage::url($fileName);

    // Save the file information to the database
    $photo = Photo::create([
        'photo_name' => $fileName,
    ]);

    // Redirect to the photo's detail page
    // return redirect('photo/' . $photo->id);
    return redirect('sandana/projects');
}

    public function photo($id)
    {
        $user = Photo::where('id',$id)->first();
        return view('presensi',compact('user'));
    }

}