<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Webhook;
use Illuminate\Support\Facades\Auth;


class WebhookController extends Controller
{
    public function create()
    {
        {
            $module = [
                'New Project' => 'New Project ',
                'New Task' => 'New Task',
                'New Invoice' => 'New Invoice',
                'Task Stage Updated' => 'Task Stage Updated',
                'New Milestone' => 'New Milestone',
                'Milestone Status Updated' => 'Milestone Status Updated',
                'Invoice Status Updated' => 'Invoice Status Updated',
            ];

            $method = ['GET' => 'GET', 'POST' => 'POST'];

            return view('webhook.create',compact('module','method'));
        }
    }

    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                               'module' => 'required',
                               'url' => 'required',
                               'method' => 'required',

                           ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->route('settings')->with('error', $messages->first());
        }
        $webhook               = new Webhook();
        $webhook->module       = $request->module;
        $webhook->url          = $request->url;
        $webhook->method       = $request->method;
        $webhook->created_by   = Auth::user()->id;
        $webhook->save();

        return redirect()->route('settings')->with('success', __('webhook successfully created!'));
    }

    public function edit($id)
    {
        $webhook = Webhook::find($id);
        $module = [
            'New Project' => 'New Project ',
            'New Task' => 'New Task',
            'New Invoice' => 'New Invoice',
            'Task Stage Updated' => 'Task Stage Updated',
            'New Milestone' => 'New Milestone',
            'Milestone Status Updated' => 'Milestone Status Updated',
            'Invoice Status Updated' => 'Invoice Status Updated',
        ];

        $method = ['Get' => 'Get', 'Post' => 'Post'];

        return view('webhook.edit', compact('webhook', 'module', 'method'));
    }

    public function update(Request $request, $id)
    {

        $webhook = Webhook::find($id);
        $webhook->module = $request->module;
        $webhook->url = $request->url;
        $webhook->method = $request->method;
        $webhook->created_by = \Auth::user()->id;
        $webhook->save();

        return redirect()->back()->with('success', __('Webhook successfully updated.'));
    }

    public function destroy(Webhook $webhook)
    {
        if(Auth::user()->id == $webhook->created_by)
        {
            $webhook->delete();

            return redirect()->route('settings')->with('success', __('Webhook successfully deleted.'));
        }
        else
        {
            return redirect()->route('settings')->with('error', __('Permission Denied.'));
        }
    }

}
