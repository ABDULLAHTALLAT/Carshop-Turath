<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function create(Request $request)
    {
        $car = null;

        if ($slug = $request->query('car')) {
            $car = Car::visible()->where('slug', (string) $slug)->first();
        }

        return view('pages.contact', compact('car'));
    }

    public function store(Request $request)
    {
        // Honeypot: real visitors never fill this hidden field
        if ($request->filled('website')) {
            return redirect()->route('contact')->with('status', 'Thank you! Your message has been sent.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:80'],
            'email' => ['required', 'email:rfc', 'max:120'],
            'phone' => ['nullable', 'string', 'max:25', 'regex:/^[0-9+\-\s()]+$/'],
            'subject' => ['required', 'string', 'max:120'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
            'car' => ['nullable', 'string', 'max:255'],
        ]);

        $car = isset($data['car']) ? Car::where('slug', $data['car'])->first() : null;

        Inquiry::create([
            'car_id' => $car?->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'subject' => $data['subject'],
            'message' => $data['message'],
        ]);

        return redirect()->route('contact')->with('status', 'Thank you! Your message has been sent. We will get back to you soon.');
    }
}
