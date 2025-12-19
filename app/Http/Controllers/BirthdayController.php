<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Birthday;
use Illuminate\Support\Facades\Storage;

class BirthdayController extends Controller
{
    public function store(Request $request)
    {
        //  Validate first (Laravel will auto-return errors)
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        //  Store image in storage/app/public/birthdays
        $path = $request->file('image')->store('birthdays', 'public');

        // Get full URL of the image
        $imageUrl = Storage::url($path); 

        //  Save to database
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
