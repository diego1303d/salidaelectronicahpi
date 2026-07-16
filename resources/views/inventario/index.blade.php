<x-layouts.app title="Inventario">

    <style>
        /* ══════════════ Tarjetas KPI ══════════════ */
        .inv-kpis {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 1rem;
            margin-bottom: 1.25rem;
        }
        .inv-kpi {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.9rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            padding: 1rem 1.1rem;
        }
        .inv-kpi-icon {
            width: 2.75rem; height: 2.75rem; flex-shrink: 0;
            border-radius: 0.75rem;
            display: flex; align-items: center; justify-content: center;
        }
        .inv-kpi-icon svg    { width: 1.35rem; height: 1.35rem; }
        .inv-kpi-icon.verde  { background: #d1fae5; color: #047857; }
        .inv-kpi-icon.oro    { background: #fef3c7; color: #b45309; }
        .inv-kpi-label { font-size: 0.72rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; color: #94a3b8; }
        .inv-kpi-value { font-size: 1.15rem; font-weight: 700; color: #143424; line-height: 1.25; }
        .inv-kpi-sub   { font-size: 0.75rem; color: #64748b; }

        /* ══════════════ Tarjeta contenedora ══════════════ */
        .inv-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.9rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        /* ══════════════ Barra de filtros ══════════════ */
        .inv-toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .inv-select {
            appearance: none;
            border: 1px solid #d1d5db;
            border-radius: 0.6rem;
            background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='m19.5 8.25-7.5 7.5-7.5-7.5'/%3E%3C/svg%3E") no-repeat right 0.7rem center / 0.85rem;
            padding: 0.55rem 2.2rem 0.55rem 0.9rem;
            font-size: 0.85rem;
            font-weight: 600;
            font-family: inherit;
            color: #1f2937;
            cursor: pointer;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .inv-select:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.18);
        }

        .inv-search {
            position: relative;
            margin-left: auto;
            min-width: 240px;
        }
        .inv-search svg {
            position: absolute; right: 0.8rem; top: 50%;
            transform: translateY(-50%);
            width: 1rem; height: 1rem;
            color: #9ca3af; pointer-events: none;
        }
        .inv-search input {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 0.6rem;
            padding: 0.55rem 2.4rem 0.55rem 0.9rem;
            font-size: 0.85rem;
            font-family: inherit;
            color: #111827;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .inv-search input::placeholder { color: #9ca3af; }
        .inv-search input:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.18);
        }

        /* ══════════════ Tabla ══════════════ */
        .inv-scroll { overflow-x: auto; }
        .inv-table  { width: 100%; border-collapse: collapse; font-size: 0.875rem; }

        .inv-table thead th {
            background: #143424;
            color: #fff;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0.85rem 1rem;
            text-align: right;
            white-space: nowrap;
        }
        .inv-table thead th.inv-left { text-align: left; }

        .inv-table tbody td {
            padding: 0.9rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            text-align: right;
            vertical-align: middle;
        }
        .inv-table tbody td.inv-left { text-align: left; }

        .inv-var { font-weight: 600; color: #143424; }
        .inv-tag {
            margin-left: 0.4rem;
            border-radius: 0.35rem;
            background: #e5e7eb;
            color: #6b7280;
            font-size: 0.7rem;
            padding: 0.1rem 0.4rem;
        }

        .inv-num  { display: block; font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-weight: 700; color: #111827; }
        .inv-sub  { display: block; font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size: 0.72rem; color: #64748b; }
        .inv-dash { color: #d1d5db; }

        /* ══════════════ Pie de tabla (totales) ══════════════ */
        .inv-table tfoot td {
            padding: 0.9rem 1rem;
            background: #f8fafc;
            border-top: 2px solid #e5e7eb;
            font-weight: 600;
            text-align: right;
        }
        .inv-table tfoot td.inv-gran { background: #fef9c3; }

        /* ══════════════ Pie con contador y leyenda ══════════════ */
        .inv-footer {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.85rem 1.25rem;
            border-top: 1px solid #e5e7eb;
            font-size: 0.8rem;
            color: #64748b;
        }
        .inv-legend { display: inline-flex; align-items: center; gap: 0.5rem; }
        .inv-legend-bar {
            width: 110px; height: 8px;
            border-radius: 9999px;
            background: linear-gradient(90deg, rgba(16, 185, 129, 0.07), rgba(16, 185, 129, 0.42));
        }

        /* ══════════════ Estado vacío ══════════════ */
        .inv-empty {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.9rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            padding: 2.5rem;
            text-align: center;
            color: #6b7280;
        }
        .inv-empty a { font-weight: 700; color: #047857; text-decoration: none; }
        .inv-empty a:hover { text-decoration: underline; }
    </style>

    <x-page-header
        title="Inventario actual"
        subtitle="Existencias por bodega y variedad" />

    @if ($variedades->isEmpty())
        <div class="inv-empty">
            Aún no hay inventario.
            <a href="{{ route('entradas.create') }}">Registra la primera entrada</a>
        </div>
    @else
        @php
            // ── Totales calculados una sola vez ──
            $granTotalT = $inventarios->sum('toneladas');
            $granTotalB = $inventarios->sum('bultos');

            $totPorVariedad = [];
            foreach ($variedades as $v) {
                $totPorVariedad[$v->id] = [
                    'KG' => $inventarios->where('variedad_id', $v->id)->sum('toneladas'),
                    'b' => $inventarios->where('variedad_id', $v->id)->sum('bultos'),
                ];
            }

            $totPorUbicacion = [];
            foreach ($ubicaciones as $u) {
                $totPorUbicacion[$u->id] = [
                    'KG' => $inventarios->where('ubicacion_id', $u->id)->sum('toneladas'),
                    'b' => $inventarios->where('ubicacion_id', $u->id)->sum('bultos'),
                ];
            }

            // ── Datos para las tarjetas KPI ──
            $variedadesConStock = collect($totPorVariedad)->filter(fn ($x) => $x['KG'] > 0)->count();
            $bodegaTop   = $ubicaciones->sortByDesc(fn ($u) => $totPorUbicacion[$u->id]['KG'])->first();
            $variedadTop = $variedades->sortByDesc(fn ($v) => $totPorVariedad[$v->id]['KG'])->first();

            // ── Celda máxima para escalar el mapa de calor ──
            $maxCelda = 0;
            foreach ($matriz as $c) {
                $maxCelda = max($maxCelda, (float) $c->toneladas);
            }

            // Lista ligera para los filtros de Alpine
            $listaVariedades = $variedades->map(fn ($v) => [
                'id'     => $v->id,
                'nombre' => $v->nombre,
            ])->values();
        @endphp

        {{-- ══════════════ Tarjetas de resumen ══════════════ --}}
        <div class="inv-kpis">
            <div class="inv-kpi">
                <span class="inv-kpi-icon verde">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 0 1-2.031.352 5.988 5.988 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971Zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 0 1-2.031.352 5.989 5.989 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971Z"/>
                    </svg>
                </span>
                <div>
                    <p class="inv-kpi-label">Inventario total</p>
                    <p class="inv-kpi-value">{{ number_format($granTotalT, 3) }} KG</p>
                    <p class="inv-kpi-sub">{{ number_format($granTotalB) }} bultos</p>
                </div>
            </div>

            <div class="inv-kpi">
                <span class="inv-kpi-icon oro">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/>
                    </svg>
                </span>
                <div>
                    <p class="inv-kpi-label">Variedades con existencia</p>
                    <p class="inv-kpi-value">{{ $variedadesConStock }} de {{ $variedades->count() }}</p>
                    <p class="inv-kpi-sub">registradas en catálogo</p>
                </div>
            </div>

            <div class="inv-kpi">
                <span class="inv-kpi-icon verde">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                    </svg>
                </span>
                <div>
                    <p class="inv-kpi-label">Bodega más cargada</p>
                    <p class="inv-kpi-value">{{ $bodegaTop?->nombre ?? '—' }}</p>
                    <p class="inv-kpi-sub">
                        {{ $bodegaTop ? number_format($totPorUbicacion[$bodegaTop->id]['KG'], 3) . ' KG' : '' }}
                    </p>
                </div>
            </div>

            <div class="inv-kpi">
                <span class="inv-kpi-icon oro">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941"/>
                    </svg>
                </span>
                <div>
                    <p class="inv-kpi-label">Variedad principal</p>
                    <p class="inv-kpi-value">{{ $variedadTop?->nombre ?? '—' }}</p>
                    <p class="inv-kpi-sub">
                        {{ $variedadTop ? number_format($totPorVariedad[$variedadTop->id]['KG'], 3) . ' KG' : '' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="inv-card"
             x-data="{
                buscar: '',
                variedadFiltro: '',
                bodegaFiltro: '',
                variedades: {{ Js::from($listaVariedades) }},
                pasaFiltros(v) {
                    const q = this.buscar.trim().toLowerCase();
                    return (q === '' || v.nombre.toLowerCase().includes(q))
                        && (this.variedadFiltro === '' || String(v.id) === this.variedadFiltro);
                },
                get visibles() {
                    return this.variedades.filter(v => this.pasaFiltros(v));
                }
             }">

            {{-- ══════════════ Filtros ══════════════ --}}
            <div class="inv-toolbar">
                <select class="inv-select" x-model="variedadFiltro">
                    <option value="">Variedad</option>
                    @foreach ($variedades as $v)
                        <option value="{{ $v->id }}">{{ $v->nombre }}</option>
                    @endforeach
                </select>

                <select class="inv-select" x-model="bodegaFiltro">
                    <option value="">Bodega</option>
                    @foreach ($ubicaciones as $ubicacion)
                        <option value="{{ $ubicacion->id }}">{{ $ubicacion->nombre }}</option>
                    @endforeach
                </select>

                <div class="inv-search">
                    <input type="text" placeholder="Buscar variedad" x-model="buscar">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                </div>
            </div>

            {{-- ══════════════ Tabla con mapa de calor ══════════════ --}}
            <div class="inv-scroll">
                <table class="inv-table">
                    <thead>
                        <tr>
                            <th class="inv-left">Variedad</th>
                            @foreach ($ubicaciones as $ubicacion)
                                <th x-show="bodegaFiltro === '' || bodegaFiltro === '{{ $ubicacion->id }}'">
                                    {{ $ubicacion->nombre }}
                                </th>
                            @endforeach
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($variedades as $variedad)
                            <tr x-show="pasaFiltros({ id: {{ $variedad->id }}, nombre: {{ Js::from($variedad->nombre) }} })">

                                <td class="inv-left">
                                    <span class="inv-var">{{ $variedad->nombre }}</span>
                                    @unless ($variedad->activo)
                                        <span class="inv-tag">inactiva</span>
                                    @endunless
                                </td>

                                @foreach ($ubicaciones as $ubicacion)
                                    @php
                                        $celda = $matriz->get($ubicacion->id . '-' . $variedad->id);
                                        $hayStock = $celda && ($celda->toneladas > 0 || $celda->bultos > 0);
                                        // Intensidad del mapa de calor: 0.07 (poquito) → 0.40 (la celda más cargada)
                                        $alpha = ($hayStock && $maxCelda > 0)
                                                 ? round(0.07 + 0.33 * min(1, $celda->toneladas / $maxCelda), 3)
                                                 : 0;
                                    @endphp
                                    <td x-show="bodegaFiltro === '' || bodegaFiltro === '{{ $ubicacion->id }}'"
                                        @if ($alpha > 0) style="background: rgba(16, 185, 129, {{ $alpha }});" @endif>
                                        @if ($hayStock)
                                            <span class="inv-num">{{ number_format($celda->toneladas, 3) }} KG</span>
                                            <span class="inv-sub">({{ number_format($celda->bultos) }} bultos)</span>
                                        @else
                                            <span class="inv-dash">—</span>
                                        @endif
                                    </td>
                                @endforeach

                                {{-- Total por variedad --}}
                                <td>
                                    <span class="inv-num">{{ number_format($totPorVariedad[$variedad->id]['KG'], 3) }} KG</span>
                                    <span class="inv-sub">({{ number_format($totPorVariedad[$variedad->id]['b']) }} bultos)</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <td style="text-align: right;">Total por bodega:</td>
                            @foreach ($ubicaciones as $ubicacion)
                                <td x-show="bodegaFiltro === '' || bodegaFiltro === '{{ $ubicacion->id }}'">
                                    <span class="inv-num">{{ number_format($totPorUbicacion[$ubicacion->id]['KG'], 3) }} KG</span>
                                    <span class="inv-sub">({{ number_format($totPorUbicacion[$ubicacion->id]['b']) }} bultos)</span>
                                </td>
                            @endforeach
                            {{-- Gran total --}}
                            <td class="inv-gran">
                                <span class="inv-num">{{ number_format($granTotalT, 3) }} KG</span>
                                <span class="inv-sub">({{ number_format($granTotalB) }} bultos)</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- ══════════════ Pie: contador + leyenda del mapa de calor ══════════════ --}}
            <div class="inv-footer">
                <span>
                    Mostrando <strong x-text="visibles.length"></strong>
                    de {{ $variedades->count() }} variedades
                </span>
                <span class="inv-legend">
                    Menos existencias
                    <span class="inv-legend-bar"></span>
                    Más existencias
                </span>
            </div>
        </div>
    @endif

</x-layouts.app>
