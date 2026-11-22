<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;

/** Messages about the owner's own cars (read-only; the broker handles the buyer) */
class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $ids = $request->user()->cars()->pluck('id');
        $inquiries = Inquiry::with('car')->whereIn('car_id', $ids)->latest()->paginate(15);

        return view('owner.inquiries.index', compact('inquiries'));
    }
}
