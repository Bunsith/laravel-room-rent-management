<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        Gate::authorize('reports.view');

        return view('reports.index');
    }
}
