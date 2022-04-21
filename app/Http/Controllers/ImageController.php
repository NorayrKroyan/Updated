<?php

namespace App\Http\Controllers;

use App\Models\Images;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function deleteImage($id) {
        $image = Images::find($id);

        $path = public_path().'/storage/img/'.  $image['name'];
        unlink($path);

        $image->delete();

        return response()->json(['status' => 'deleted']);           
    }
}
