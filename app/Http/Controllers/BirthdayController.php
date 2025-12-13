<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Birthday;
use Illuminate\Support\Facades\Storage;

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
        'link' => url("/b/{$birthday->public_token}")
    ]);
}

    public function showByToken($token){
    $birthday = Birthday::where('public_token', $token)->first();
    return response()->json([
        "message" => "success",
        "data" => $birthday
    ]);
}
}
