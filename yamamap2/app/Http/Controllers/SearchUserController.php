<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SearchUserController extends Controller
{
  public function __invoke(Request $request)
  {
    $result = null;

    if ($query = $request->get('query')) {
      $result = User::search($query)->get();
    }
    return view('branch.index', compact('result'));
  }
}
