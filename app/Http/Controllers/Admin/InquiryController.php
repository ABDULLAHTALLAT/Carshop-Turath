<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;

class InquiryController extends Controller
{
    public function index()
    {
        $inquiries = Inquiry::with('car')->latest()->paginate(15);

        return view('admin.inquiries.index', compact('inquiries'));
    }

    public function show(Inquiry $inquiry)
    {
        $inquiry->load('car');

        if (is_null($inquiry->read_at)) {
            $inquiry->forceFill(['read_at' => now()])->save();
        }

        return view('admin.inquiries.show', compact('inquiry'));
    }

    public function destroy(Inquiry $inquiry)
    {
        $inquiry->delete();

        return redirect()->route('admin.inquiries.index')->with('status', 'Message deleted.');
    }
}
