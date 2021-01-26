<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Historique;

class DashboardController extends Controller {


    /**
     * Dashboard index
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $historiques = Historique::paginate(20);
        return view('admin.dashboard.index')->with('historiques',$historiques);
    }

}