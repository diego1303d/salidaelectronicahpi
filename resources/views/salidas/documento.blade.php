<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Salida de Semilla</title>
  <style>
    @page {
      margin: 20px;
    }
    * {
      box-sizing: border-box;
    }
    body {
      font-family: Arial, Helvetica, sans-serif;
      margin: 0;
      padding: 0;
      color: #000;
      background: #fff;
      font-size: 11px;
    }

    .page {
      width: 100%;
    }

    /* ENCABEZADO */
    .header-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 5px;
    }

    .logo-placeholder {
      font-weight: bold;
      font-size: 22px;
      color: #176B53;
      text-align: left;
    }

    .company {
      text-align: center;
      line-height: 1.2;
    }

    .company h1 {
      font-size: 15px;
      margin: 0;
      font-weight: bold;
    }

    .company p {
      margin: 1px 0;
      font-size: 10px;
    }

    .folio-box {
      width: 85px;
      border: 2px solid #000;
      text-align: center;
      font-size: 12px;
      font-weight: bold;
      padding: 4px;
      float: right;
    }

    .folio-box .num {
      color: red;
      margin-top: 4px;
      font-size: 11px;
    }

    /* CAJA PRINCIPAL CON BORDES (Súper ampliada en alto) */
    .main-box {
      border: 2px solid #000;
      padding: 6px;
      /* Ajusta este min-height si quieres que la caja del cuerpo sea aún más grande o chica */
      min-height: 480px;
    }

    /* TABLA DE INFORMACIÓN SUPERIOR */
    .info-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 8px;
      font-size: 10px;
    }

    .info-table td {
      padding: 3px 4px;
      vertical-align: middle;
    }

    .box-val {
      border: 1px solid #000;
      padding: 3px 6px;
      display: inline-block;
      font-weight: bold;
    }

    .title-line {
      text-align: right;
      font-weight: bold;
      font-size: 13px;
      padding-right: 15px;
    }

    /* TABLA DE PRODUCTOS */
    table.data-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 11px;
      margin-top: 5px;
    }

    table.data-table th,
    table.data-table td {
      border: 1px solid #000;
      padding: 6px 4px;
    }

    table.data-table th {
      text-align: center;
      font-weight: bold;
    }

    /* Filas vacías para rellenar visualmente el cuerpo de la tabla */
    .empty-row td {
      height: 25px;
    }

    /* TABLA DE TOTALES */
    table.totals-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 11px;
      margin-top: 15px;
    }

    table.totals-table td {
      padding: 4px;
    }

    .right { text-align: right; }
    .center { text-align: center; }
    .bold { font-weight: bold; }

    /* SECCIÓN FIJA EN EL PIE DE PÁGINA (FIRMAS Y CÓDIGO DE BARRAS) */
    .footer-fixed {
      position: fixed;
      bottom: 0px;
      left: 0px;
      right: 0px;
      width: 100%;
    }

    .signatures-table {
      width: 100%;
      border-collapse: collapse;
      text-align: center;
      font-size: 10px;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .signatures-table td {
      width: 25%;
      padding: 0 15px;
    }

    .line {
      border-top: 1px solid #000;
      margin-bottom: 4px;
    }

    .footer-table {
      width: 100%;
      border-collapse: collapse;
    }

    .footer-folio {
      width: 110px;
      border: 2px solid #000;
      text-align: center;
      padding: 5px;
      font-size: 13px;
      font-weight: bold;
    }

    .footer-folio .num {
      color: red;
      margin-top: 45px;
    }

    .barcode-container {
      padding-left: 15px;
      vertical-align: bottom;
    }

    .barcode-placeholder {
      width: 100%;
      height: 65px;
    }
  </style>
</head>
<body>

  {{-- $salida es UN registro, no una colección: se accede directo, sin foreach --}}
  @php
    $esTraspaso    = $salida->tipo === \App\Enums\SalidaTipo::Traspaso;
    $nombreDestino = $esTraspaso ? optional($salida->destino)->nombre : $salida->cliente_nombre;
  @endphp

  <div class="page">

    <!-- ENCABEZADO -->
    <table class="header-table">
      <tr>
        <td style="width: 20%;">
          <div class="logo-placeholder">

 <img src="{{ $logoBase64 }}" style="max-width: 140px; height: auto;" alt="Logo">


          </div>
        </td>
        <td class="company" style="width: 60%;">
          <h1>HARINERA LOS PIRINEOS, S.A DE C.V</h1>
          <p>Carr. Panamericana Km.11 Tramo Salamanca-Celaya</p>
          <p>Col.Emiliano Zapata C.P. 36700 Salamanca, Gto</p>
          <p><strong>R.F.C</strong> HPI8806245W5</p>
          <p><strong>Tels.</strong> (464) 642-01-96, 800-147-44-44</p>
        </td>
        <td style="width: 20%; vertical-align: top;">
          <div class="folio-box">
            FOLIO
            <div class="num">{{ $salida->folio }}</div>
          </div>
        </td>
      </tr>
    </table>

    <!-- CAJA PRINCIPAL -->
    <div class="main-box">

      <!-- INFORMACIÓN ESTRUCTURADA -->
      <table class="info-table">
        <tr>
          <td style="width: 10%;">NOMBRE</td>
          <td style="width: 40%;">
            <span class="box-val" style="width: 180px; text-align: center;">{{ $nombreDestino }}</span>
          </td>
          <td colspan="3" class="title-line">SALIDA DE SEMILLA</td>
        </tr>
        <tr>
          <td colspan="2">
            {{-- AJUSTA: si no tienes cliente_telefono, quita este span o cámbialo por el campo real --}}
            <span class="box-val"><strong>TEL.</strong> {{ $salida->cliente_telefono ?? '—' }}</span>
            &nbsp;&nbsp;
            <span class="box-val"><strong>UBICACIÓN:</strong> {{ optional($salida->origen)->nombre }}</span>
          </td>
          <td style="text-align: right;"><strong>FECHA DE CREACIÓN:</strong> {{ $salida->fecha->format('d/m/Y') }}</td>
          <td style="text-align: center;"><strong>DESTINO:</strong> {{ $nombreDestino }}</td>
          {{-- Aquí puse el tipo (VENTA / TRASPASO). Si manejas forma de pago (CREDITO/CONTADO), cámbialo --}}
          <td style="text-align: right;"><strong>{{ strtoupper($salida->tipo->value) }}</strong></td>
        </tr>
      </table>

      {{--
        AJUSTA los nombres de campo de cada detalle según tu modelo SalidaDetalle:
          $detalle->toneladas        -> columna TON
          $detalle->bultos           -> columna SACOS (¿o es 'sacos'?)
          $detalle->variedad->nombre -> columna VARIEDAD
          $detalle->precio           -> columna PRECIO (asumí precio por tonelada)
        Si el detalle ya guarda un importe/total calculado, usa ese campo
        en lugar de multiplicar precio * toneladas.
      --}}

      <!-- TABLA DE DETALLES -->
      <table class="data-table">
        <thead>
          <tr>
            <th style="width: 8%;">PARTIDA</th>
            <th style="width: 8%;">TON</th>
            <th style="width: 8%;">SACOS</th>
            <th>VARIEDAD</th>
            <th style="width: 12%;">PRECIO</th>
            <th style="width: 12%;">TOTAL</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($salida->detalles as $detalle)
            <tr>
              <td class="center">{{ $loop->iteration }}</td>
              <td class="center">{{ $detalle->toneladas }}</td>
              <td class="center">{{ $detalle->bultos }}</td>
              <td class="bold">{{ optional($detalle->variedad)->nombre }}</td>
              <td class="right">{{ number_format($detalle->precio_tonelada ?? 0, 2) }}</td>
              <td class="right">{{ number_format(($detalle->precio_tonelada ?? 0) * ($detalle->toneladas ?? 0), 2) }}</td>
            </tr>


          @endforeach

          {{-- Filas vacías opcionales para que la tabla se vea "llena" en el papel. Bórralas si no las quieres --}}
          @for ($i = $salida->detalles->count(); $i < 6; $i++)
            <tr class="empty-row">
              <td></td><td></td><td></td><td></td><td></td><td></td>
            </tr>
          @endfor
        </tbody>
      </table>

      @php
        $subtotal = $salida->detalles->sum(fn($d) => ($d->precio_tonelada ?? 0) * ($d->toneladas ?? 0));
        $iva      = 0.16;                 // AJUSTA si manejas IVA (ej. $subtotal * 0.16)
        $total    = $subtotal + $iva;
      @endphp

      <!-- TABLA DE TOTALES Y RESUMEN -->
      <table class="totals-table">
        <tr>
          <td style="width: 16%; border: 1px solid #000;" class="center bold">TOTAL TON</td>
          <td style="width: 16%; border: 1px solid #000;" class="center bold">TOTAL SACOS</td>
          <td class="right bold" style="border: none;">Sub Total</td>
          <td class="right" style="width: 12%; border: 1px solid #000;">{{ number_format($subtotal, 2) }}</td>
        </tr>
        <tr>
          <td style="border: 1px solid #000;" class="center bold">{{ $salida->total_toneladas }}</td>
          <td style="border: 1px solid #000;" class="center bold">{{ $salida->total_bultos }}</td>
          <td class="right bold" style="border: none;">IVA :</td>
          <td class="right" style="border: 1px solid #000;">{{ number_format($iva, 2) }}</td>
        </tr>
        <tr>
          <td colspan="2" style="border: none;"></td>
          <td class="right bold" style="border: none;">Total:</td>
          <td class="right bold" style="border: 1px solid #000;">{{ number_format($total, 2) }}</td>
        </tr>
      </table>

    </div>

  </div>

  <!-- SECCIÓN PIE DE PÁGINA (FIRMAS Y CÓDIGO DE BARRAS SIEMPRE ABAJO) -->
  <div class="footer-fixed">
    <!-- FIRMAS -->
    <table class="signatures-table">
      <tr>
        <td>
          <div class="line"></div>
          ELABORO<br>
          <span style="font-weight: normal;">{{ optional($salida->usuario)->name }}</span>
        </td>
        <td>
          <div class="line"></div>
          AUTORIZO
        </td>
        <td>
          <div class="line"></div>
          ENTREGO
        </td>
        <td>
          <div class="line"></div>
          RECIBIO
        </td>
      </tr>
    </table>

    <!-- FOLIO Y CÓDIGO DE BARRAS -->
    <table class="footer-table">
      <tr>
        <td style="width: 110px;">
          <div class="footer-folio">
            FOLIO
            <div class="num">{{ $salida->folio }}</div>
          </div>



        </td>
        <td class="barcode-container">
          <div class="barcode-placeholder">




 <div class="barcode-placeholder">
  @if(!empty($barcodeBase64))
    <img src="{{ $barcodeBase64 }}"
         alt="Código de barras {{ $salida->folio }}"
         style="width: 220px; height: 35px; object-fit: fill;">
  @endif
</div>


          </div>
        </td>
      </tr>
    </table>
  </div>

</body>
</html>
