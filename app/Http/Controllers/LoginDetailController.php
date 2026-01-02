<?php

namespace App\Http\Controllers;

use App\Models\LoginDetail;
use App\Models\User;
use Illuminate\Http\Request;

class LoginDetailController extends Controller
{
    public function index(Request $request)
    {
        $logindetails = LoginDetail::where('created_by', '=', \Auth::user()->creatorId());
        $usersList = User::where('created_by', '=', \Auth::user()->creatorId())->whereNotIn('type', ['super admin', 'owner'])->get()->pluck('name', 'id');
        $usersList->prepend('All', '');
        if (isset($request->month) && !empty($request->month))
        {
            $time=strtotime($request->month);
            $month=date("m",$time);

           $logindetails = $logindetails->whereMonth('date', $month);
        }
        if(isset($request->user) && !empty($request->user))
        {
           $logindetails = $logindetails->where('user_id', $request->user);
        }

        $logindetails = $logindetails->get();
        return view('users.login_details', compact('logindetails','usersList'));

    }

    public function show($id)
    {
        $details = LoginDetail::find($id);

        return view('users.show_login_detail', compact('details'));

    }

    public function destroy($id)
    {
        LoginDetail::where('id', $id)->delete();

        return redirect()->route('logindetails.index')->with(
            'success', 'logindetails successfully deleted.'
        );

    }
}
