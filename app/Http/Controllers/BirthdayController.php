<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Birthday;
use Illuminate\Support\Facades\Storage;

class BirthdayController extends Controller
{
    public function store(Request $request)
    {
        // Validate
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        //  Store image (uses FILESYSTEM_DISK=s3)
        $path = $request->file('image')->store('birthdays');

        //  Generate public URL (S3 / R2)
        $imageUrl = Storage::url($path);

        // Save to DB
        $birthday = Birthday::create([
            'name'  => $validated['name'],
            'image' => $imageUrl,
        ]);

        return response()->json([
            'message' => 'success',
            'link'    => $birthday->public_token,
        ], 201);
    }

    public function showByToken($token)
    {
        $birthday = Birthday::where('public_token', $token)->firstOrFail();

        return response()->json([
            'message' => 'success',
            'data'    => $birthday,
        ]);
    }
}
