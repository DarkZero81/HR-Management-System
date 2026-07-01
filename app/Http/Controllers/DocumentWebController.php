<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\View\View;

class DocumentWebController extends Controller
{
    public function index(): View
    {
        $documents = Document::with('employee')->orderBy('expiry_date')->paginate(15);
        return view('documents.index', compact('documents'));
    }
}
