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
        $historiques = Historique::where('created_at','>=',date('Y-m-d H:i:s', time() - (60 * 60 * 24 * 30)))->orderBy('id','DESC')->paginate(50);
        return view('admin.dashboard.index')->with('historiques',$historiques);
    }

}