<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;

class SearchController extends Controller
{


  public function __invoke(Request $request)
  {
    $result = null;

    if ($query = $request->get('query')) {
      $result = Item::search($query)->get();
    }
    return view('item_card.index', compact('result'));
  }
}
