<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Rule;

class RuleController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $rules = Rule::orderBy('order')->get();
        return view('user.rules.index', compact('user', 'rules'));
    }
}
