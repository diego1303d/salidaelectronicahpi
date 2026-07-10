<?php

namespace App\Http\Controllers;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;


class ProductoController extends Controller
{

public function index (Request $request)
{
$productos = Producto::all();


return view ('Productos_vista.index',compact('productos'));
}
}
