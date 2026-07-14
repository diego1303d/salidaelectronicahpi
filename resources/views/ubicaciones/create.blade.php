<x-layouts.app>

    <style>
        /* ══════════════ Escenario con gradiente (fondo del mockup) ══════════════ */
        .ubi-scene {
            min-height: 80vh;
            padding: 2.5rem 2rem;
            border-radius: 1.5rem;
            background:
                radial-gradient(circle at 80% 15%, rgba(209, 250, 229, 0.90) 0%, transparent 50%),
                radial-gradient(circle at 92% 85%, rgba(253, 230, 138, 0.55) 0%, transparent 45%),
                radial-gradient(circle at 12% 80%, rgba(236, 253, 245, 0.90) 0%, transparent 50%),
                radial-gradient(circle at 8% 15%, rgba(254, 243, 199, 0.50) 0%, transparent 40%),
                #f8fafc;
        }

        /* ══════════════ Tarjeta de vidrio esmerilado ══════════════ */
        .ubi-card {
            position: relative;
            width: 100%;
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.55);
            border-radius: 2rem;
            padding: 2.5rem;
            box-shadow: 0 25px 60px -15px rgba(20, 52, 36, 0.18);
            overflow: hidden;
        }
        /* Franja decorativa verde → dorado en la parte superior */
        .ubi-card::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 6px;
            background: linear-gradient(90deg, #059669 0%, #34d399 45%, #facc15 100%);
        }

        /* ══════════════ Encabezado con insignia ══════════════ */
        .ubi-header  { display: flex; align-items: center; gap: 1rem; margin-bottom: 2.25rem; }
        .ubi-badge   {
            width: 3.25rem; height: 3.25rem; flex-shrink: 0;
            border-radius: 1rem;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #059669, #10b981);
            color: #fff;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.35);
        }
        .ubi-badge svg { width: 1.6rem; height: 1.6rem; }
        .ubi-title    { font-size: 2.15rem; font-weight: 700; color: #143424; line-height: 1.1; }
        .ubi-subtitle { color: #64748b; font-size: 0.875rem; margin-top: 0.3rem; }

        /* ══════════════ Rejilla: Clave 2 / Nombre 4 / Dirección 6 ══════════════ */
        .ubi-grid { display: grid; grid-template-columns: 1fr; gap: 1.25rem; }
        @media (min-width: 1024px) {
            .ubi-grid  { grid-template-columns: repeat(12, minmax(0, 1fr)); align-items: start; }
            .ubi-col-2 { grid-column: span 2; }
            .ubi-col-4 { grid-column: span 4; }
            .ubi-col-6 { grid-column: span 6; }
        }

        /* ══════════════ Campos ══════════════ */
        .ubi-form > * + * { margin-top: 2rem; }

        .ubi-label { display: block; font-size: 0.875rem; font-weight: 600; color: #1f2937; margin-bottom: 0.4rem; }
        .ubi-req   { color: #ef4444; }
        .ubi-opt   { font-size: 0.75rem; font-weight: 400; color: #94a3b8; margin-left: 0.25rem; }

        .ubi-field          { position: relative; }
        .ubi-field .ubi-icon {
            position: absolute; left: 0.8rem; top: 0.78rem;
            width: 1.1rem; height: 1.1rem;
            color: #94a3b8; pointer-events: none;
        }

        .ubi-input {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 0.65rem;
            background: #fff;
            padding: 0.68rem 0.8rem 0.68rem 2.5rem;
            font-size: 0.875rem;
            font-family: inherit;
            color: #111827;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .ubi-input::placeholder { color: #9ca3af; }
        .ubi-input:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.18);
        }
        .ubi-input.has-error { border-color: #f87171; }
        textarea.ubi-input   { resize: none; }

        .ubi-clave { text-transform: uppercase; font-weight: 600; letter-spacing: 0.05em; }
        .ubi-hint  { margin-top: 0.3rem; font-size: 0.75rem; color: #94a3b8; }
        .ubi-error { margin-top: 0.3rem; font-size: 0.75rem; font-weight: 500; color: #dc2626; }

        /* ══════════════ Panel de estatus (cambia con el toggle) ══════════════ */
        .ubi-status {
            display: flex; align-items: center; justify-content: space-between; gap: 1rem;
            border: 1px solid transparent;
            border-radius: 0.9rem;
            padding: 0.9rem 1rem;
            cursor: pointer;
            transition: background 0.2s ease, border-color 0.2s ease;
        }
        .ubi-status.panel-on  { border-color: #a7f3d0; background: rgba(209, 250, 229, 0.55); }
        .ubi-status.panel-off { border-color: #e5e7eb; background: #f8fafc; }

        .ubi-status-icon {
            width: 2.25rem; height: 2.25rem; flex-shrink: 0;
            border-radius: 0.6rem;
            display: flex; align-items: center; justify-content: center;
            transition: background 0.2s ease, color 0.2s ease;
        }
        .ubi-status-icon svg  { width: 1.25rem; height: 1.25rem; }
        .panel-on  .ubi-status-icon { background: #d1fae5; color: #059669; }
        .panel-off .ubi-status-icon { background: #e5e7eb; color: #9ca3af; }

        .ubi-status-title { font-weight: 600; color: #143424; }
        .ubi-status-desc  { font-size: 0.75rem; color: #64748b; }

        /* ══════════════ Interruptor (switch) ══════════════ */
        .ubi-switch { position: relative; display: inline-flex; flex-shrink: 0; }
        .ubi-switch input[type="checkbox"] {
            position: absolute; inset: 0;
            width: 100%; height: 100%;
            margin: 0; opacity: 0; cursor: pointer;
        }
        .ubi-switch .track {
            display: block; position: relative;
            width: 2.75rem; height: 1.5rem;
            border-radius: 9999px;
            background: #cbd5e1;
            transition: background 0.2s ease;
        }
        .ubi-switch .track::after {
            content: "";
            position: absolute; top: 2px; left: 2px;
            width: 1.25rem; height: 1.25rem;
            border-radius: 9999px;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25);
            transition: transform 0.2s ease;
        }
        .ubi-switch input[type="checkbox"]:checked + .track        { background: #059669; }
        .ubi-switch input[type="checkbox"]:checked + .track::after { transform: translateX(1.25rem); }
        .ubi-switch input[type="checkbox"]:focus-visible + .track  { box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.35); }

        /* ══════════════ Botones ══════════════ */
        .ubi-actions { display: flex; justify-content: flex-end; gap: 1rem; padding-top: 0.5rem; }

        .btn-cancel {
            display: inline-flex; align-items: center;
            padding: 0.8rem 2rem;
            border-radius: 0.9rem;
            font-weight: 600; font-size: 0.95rem;
            color: #fff; text-decoration: none;
            background: #94a3b8;
            box-shadow: 0 4px 12px rgba(100, 116, 139, 0.30);
            transition: background 0.2s ease;
        }
        .btn-cancel:hover { background: #64748b; }

        .btn-gold {
            padding: 0.8rem 2rem;
            border: none; border-radius: 0.9rem;
            font-weight: 700; font-size: 0.95rem; font-family: inherit;
            color: #143424; cursor: pointer;
            background: linear-gradient(180deg, #fbdf67 0%, #facc15 100%);
            box-shadow: 0 6px 18px rgba(250, 204, 21, 0.45);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .btn-gold:hover  { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(250, 204, 21, 0.55); }
        .btn-gold:active { transform: translateY(0); }
    </style>

    <div class="ubi-scene"
         x-data="{
            clave: {{ Js::from(old('clave', '')) }},
            nombre: {{ Js::from(old('nombre', '')) }},
            direccion: {{ Js::from(old('direccion', '')) }},
            activo: {{ old('activo', 1) ? 'true' : 'false' }}
         }">

        <div class="ubi-card" data-purpose="form-card">

            {{-- ══════════════ Encabezado ══════════════ --}}
            <div class="ubi-header">
                <span class="ubi-badge">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                    </svg>
                </span>
                <div>
                    <h1 class="ubi-title">Nueva ubicación</h1>
                    <p class="ubi-subtitle">Registra una bodega para capturar entradas y salidas.</p>
                </div>
            </div>

            {{-- ══════════════ Formulario ══════════════ --}}
            <form method="POST" action="{{ route('ubicaciones.store') }}" class="ubi-form">
                @csrf

                {{-- Clave + Nombre + Dirección en una sola fila (se apilan en pantallas chicas) --}}
                <div class="ubi-grid">

                    {{-- Clave --}}
                    <div class="ubi-col-2">
                        <label for="clave" class="ubi-label">
                            Clave <span class="ubi-req">*</span>
                        </label>
                        <div class="ubi-field">
                            <svg class="ubi-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/>
                            </svg>
                            <input type="text"
                                   id="clave"
                                   name="clave"
                                   x-model="clave"
                                   x-on:input="clave = clave.toUpperCase()"
                                   required
                                   maxlength="10"
                                   autofocus
                                   placeholder="BOD"
                                   class="ubi-input ubi-clave {{ $errors->has('clave') ? 'has-error' : '' }}">
                        </div>
                        <p class="ubi-hint"><span x-text="clave.length"></span>/10 caracteres</p>
                        @error('clave')
                            <p class="ubi-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nombre --}}
                    <div class="ubi-col-4">
                        <label for="nombre" class="ubi-label">
                            Nombre de la ubicación <span class="ubi-req">*</span>
                        </label>
                        <div class="ubi-field">
                            <svg class="ubi-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                            </svg>
                            <input type="text"
                                   id="nombre"
                                   name="nombre"
                                   x-model="nombre"
                                   required
                                   maxlength="100"
                                   placeholder="Ej. Bodega Villagrán"
                                   class="ubi-input {{ $errors->has('nombre') ? 'has-error' : '' }}">
                        </div>
                        @error('nombre')
                            <p class="ubi-error">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Dirección --}}
                    <div class="ubi-col-6">
                        <label for="direccion" class="ubi-label">
                            Dirección
                            <span class="ubi-opt">(opcional)</span>
                        </label>
                        <div class="ubi-field">
                            <svg class="ubi-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                            </svg>
                            <textarea id="direccion"
                                      name="direccion"
                                      x-model="direccion"
                                      rows="2"
                                      maxlength="255"
                                      placeholder="Calle, número, colonia, municipio…"
                                      class="ubi-input {{ $errors->has('direccion') ? 'has-error' : '' }}"></textarea>
                        </div>
                        @error('direccion')
                            <p class="ubi-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Estatus (activo = 1, inactivo = 0) — el panel cambia de color con el toggle --}}
                <label class="ubi-status"
                       :class="activo ? 'panel-on' : 'panel-off'">
                    <div style="display:flex; align-items:center; gap:0.75rem;">
                        <span class="ubi-status-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                        </span>
                        <div>
                            <p class="ubi-status-title"
                               x-text="activo ? 'Ubicación activa' : 'Ubicación inactiva'"></p>
                            <p class="ubi-status-desc">
                                Solo las activas aparecen al capturar entradas y salidas.
                            </p>
                        </div>
                    </div>

                    {{-- El hidden manda 0 si el checkbox va desmarcado; marcado manda 1 --}}
                    <span class="ubi-switch">
                        <input type="hidden" name="activo" value="0">
                        <input type="checkbox"
                               name="activo"
                               value="1"
                               x-model="activo">
                        <span class="track"></span>
                    </span>
                </label>

                {{-- ══════════════ Acciones ══════════════ --}}
                <div class="ubi-actions">
                    <a href="{{ route('ubicaciones.index') }}" class="btn-cancel">
                        Cancelar
                    </a>
                    <button type="submit" class="btn-gold">
                        Guardar ubicación
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>