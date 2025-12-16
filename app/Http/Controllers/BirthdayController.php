<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Birthday;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class BirthdayController extends Controller
{
    public function store(Request $request)
    {
        if (!$request->hasFile('image')) {
            return response()->json([
                'error' => 'No image received'
            ], 400);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $path = $request->file('image')->store('birthdays', 'public');

        $birthday = Birthday::create([
            'name' => $request->name,
            'image' => $path, // birthdays/xxx.png
        ]);

        return response()->json([
            'link' => $birthday->public_token
        ]);
    }

    public function image($path)
{
    $fullPath = storage_path('app/public/' . $path);

    if (!File::exists($fullPath)) {
        abort(404);
    }

    $mime = File::mimeType($fullPath);

    return response()->file($fullPath, [
        'Content-Type' => $mime
    ]);
}



    public function showByToken($token)
    {
        $birthday = Birthday::where('public_token', $token)->firstOrFail();

        return response()->json([
            "message" => "success",
            "data" => [
                "id" => $birthday->id,
                "name" => $birthday->name,
                "image" => url("/image/" . $birthday->image), 
                "public_token" => $birthday->public_token,
                "created_at" => $birthday->created_at,
            ]
        ]);
    }
}
