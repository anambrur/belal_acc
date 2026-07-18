<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TransliterationService;

class TransliterationController extends Controller
{
    protected $transliterationService;

    public function __construct(TransliterationService $transliterationService)
    {
        $this->transliterationService = $transliterationService;
    }

    public function transliterate(Request $request)
    {
       
        $englishText = $request->input('text');
        $banglaText = $this->transliterationService->transliterate($englishText);
       
        return response()->json(['banglaText' => $banglaText]);
        // return response()->json(['success' => true]);
    }


    public function showForm()
    {
        return view('transliterate');
    }
}
