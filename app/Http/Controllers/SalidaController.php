<?php

namespace App\Http\Controllers;


use App\Enums\FormaPago;
use App\Models\Ubicacion;
use App\Models\Variedad;
use App\Services\SalidaService;
use Illuminate\Http\Request;
use App\Models\Salida;

use App\Http\Requests\StoreSalidaRequest;


class SalidaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $salidas = Salida::query()
        ->with(['origen', 'destino'])
        ->when($request->filled('folio'), fn ($q) =>
            $q->where('folio', 'like', '%' . mb_strtoupper(trim($request->folio)) . '%'))
        ->when($request->filled('estatus'), fn ($q) =>
            $q->where('estatus', $request->estatus))
        ->when($request->filled('tipo'), fn ($q) =>
            $q->where('tipo', $request->tipo))
        ->when($request->filled('bodega'), fn ($q) =>
            $q->where('ubicacion_origen_id', $request->bodega))
        ->when($request->filled('desde'), fn ($q) =>
            $q->whereDate('fecha', '>=', $request->desde))
        ->when($request->filled('hasta'), fn ($q) =>
            $q->whereDate('fecha', '<=', $request->hasta))
        ->latest('id')
        ->paginate(15)
        ->withQueryString();

    $ubicaciones = Ubicacion::orderBy('nombre')->get();

    return view('salidas.index', compact('salidas', 'ubicaciones'));
}


    public function __construct(private SalidaService $salidaService)
    {
    }

    public function create()
    {
      $ubicaciones = Ubicacion::where('activo', true)->orderBy('nombre')->get();
        $variedades  = Variedad::where('activo', true)->orderBy('nombre')->get();
        $formasPago  = FormaPago::cases();

        return view('salidas.create', compact('ubicaciones', 'variedades', 'formasPago'));
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(StoreSalidaRequest $request)
{
    try {
        $salida = $this->salidaService->crear(
            $request->safe()->except(['detalles']),
            $request->validated('detalles'),
            $request->user(),
        );
    } catch (\Throwable $e) {
        return back()
            ->withInput()
            ->with('error', 'No se pudo registrar la salida: ' . $e->getMessage());
    }

   return redirect()
    ->route('salidas.show', $salida)
    ->with('success', "Salida {$salida->folio} registrada correctamente.");
}

    /**
     * Display the specified resource.
     */
public function show(Salida $salida)
{
    $salida->load(['detalles.variedad', 'origen', 'destino', 'usuario', 'historial.usuario']);


    return view('salidas.show', compact('salida'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function generarPDF(Salida $id) {
$logoPath = public_path('img/logo.png'); // Ruta a tu archivo

    $datos=Salida::find($id);
    $logoData = base64_encode(file_get_contents($logoPath));
    $logoBase64 = 'data:image/png;base64,' . $logoData;

   
    $pdf = app('dompdf.wrapper');
    $pdf->loadView('salidas.documento',compact('datos','logoBase64')); // Carga la vista que creaste
    return $pdf->stream('documento.pdf'); // Genera y descarga el PDF
}

}




