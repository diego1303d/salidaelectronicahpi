<?php

namespace App\Http\Controllers;


use App\Http\Requests\StoreEntradaRequest;
use App\Models\Entrada;
use App\Models\Ubicacion;
use App\Models\Variedad;
use App\Services\EntradaService;
use Illuminate\Http\Request;

class EntradaController extends Controller
{
 public function __construct(private EntradaService $entradaService)
    {
    }


    public function index(Request $request){

$ubicaciones = Ubicacion::where('activo', true)->orderBy('nombre')->get();

    $entradas = Entrada::with(['ubicacion', 'detalles.variedad', 'usuario'])
        ->when($request->filled('ubicacion_id'),
            fn ($query) => $query->where('ubicacion_id', $request->ubicacion_id))
        ->when($request->filled('desde'),
            fn ($query) => $query->where('fecha', '>=', $request->desde))
        ->when($request->filled('hasta'),
            fn ($query) => $query->where('fecha', '<=', $request->hasta))
        ->latest('fecha')
        ->latest('id')
        ->paginate(15)
        ->withQueryString();

    return view('entradas.index', compact('entradas', 'ubicaciones'));
    }

   public function create()
    {
        $ubicaciones = Ubicacion::where('activo', true)->orderBy('nombre')->get();
        $variedades  = Variedad::where('activo', true)->orderBy('nombre')->get();

        return view('entradas.create', compact('ubicaciones', 'variedades'));
    }


    public function store(StoreEntradaRequest $request)
{
    try {
        $entrada = $this->entradaService->crear(
            $request->safe()->only(['ubicacion_id', 'fecha', 'origen', 'observaciones']),
            $request->detalles,
            $request->user(),
        );
    } catch (\Throwable $e) {
        return back()
            ->withInput()
            ->with('error', 'No se pudo registrar la entrada: ' . $e->getMessage());
    }

    return redirect()
        ->route('entradas.index')
        ->with('success', "Entrada {$entrada->folio} registrada correctamente.");
}



public function show(Entrada $entrada)
{
    $entrada->load(['ubicacion', 'detalles.variedad', 'usuario']);

    return view('entradas.show', compact('entrada'));
}

}
