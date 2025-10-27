<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskAdminController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.tasks.index', [
            'title' => 'Tasks',
        ]);
    }
}