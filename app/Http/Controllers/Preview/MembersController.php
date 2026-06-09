<?php

namespace App\Http\Controllers\Preview;

use App\Http\Controllers\MembersController as PublicMembersController;
use Illuminate\Http\Request;

class MembersController extends PublicMembersController
{
    public function index(Request $request)
    {
        $response = parent::index($request);

        if ($response instanceof \Illuminate\View\View) {
            return view('preview::members.index', $response->getData());
        }

        return $response;
    }
}
