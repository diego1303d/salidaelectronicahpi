# Guía de Desarrollo — Sistema de Salidas de Trigo

Plan de trabajo ordenado para construir el sistema completo en Laravel. Sigue las fases en orden: cada una depende de la anterior. Ve palomeando los checkboxes conforme avances.

\---

## Fase 0 — Preparación del proyecto

* \[x ] Crear el proyecto Laravel (o usar uno existente) con PHP 8.3+ y MySQL 8.0.16+
* \[ x] Configurar `.env` (conexión a BD, `APP\_TIMEZONE=America/Mexico\_City`)
* \[x ] Copiar las carpetas `database/` y `app/` del zip dentro del proyecto
* \[x ] Verificar que la tabla `users` esté migrada (la trae Laravel por default)
* \[x ] Correr `php artisan migrate`
* \[x ] Correr `php artisan db:seed --class=UbicacionSeeder`
* \[x ] Editar en la BD (o en el seeder) las claves y nombres reales de las 4 bodegas
* \[x ] Instalar Spatie Laravel Permission si vas a manejar roles (ya lo conoces de Indicadores):
`composer require spatie/laravel-permission`
* \[x ] Instalar el generador de códigos de barras:
`composer require picqer/php-barcode-generator`
* \[ x] Instalar dompdf para imprimir los documentos:
`composer require barryvdh/laravel-dompdf`

**Meta de la fase:** el proyecto corre, las 11 tablas existen y las bodegas están dadas de alta.

\---

## Fase 1 — Catálogos (Ubicaciones y Variedades)

Empieza por lo más simple para calentar motores. Son CRUDs normales como los que ya has hecho.

* \[x ] CRUD de **Variedades**: listado, crear, editar, activar/desactivar

  * \[x ] Campo `peso\_bulto\_kg` en el formulario (ej. 50.00) — es clave para validar después
  * \[x ] Validación: `nombre` requerido y único, `peso\_bulto\_kg` numérico mayor a 0
* \[x ] CRUD de **Ubicaciones**: solo editar nombre/dirección y activar/desactivar

  * \[x ] No permitir borrar ubicaciones (tienen inventario e historial amarrado), solo desactivar
* \[x ] Menú lateral (AdminLTE) con las secciones: Catálogos, Entradas, Salidas, Inventario, Reportes

**Meta de la fase:** ya puedes dar de alta variedades de trigo y ver tus 4 bodegas.

\---

## Fase 2 — Entradas de inventario

Sin inventario no hay nada que vender ni traspasar, así que esto va antes que las salidas.

* \[ x] Formulario de nueva entrada: bodega, fecha, origen (proveedor/campo), observaciones
* \[x ] Tabla dinámica de partidas: variedad + toneladas + bultos (agregar/quitar renglones con JS)
* \[x ] FormRequest de validación: bodega existente y activa, al menos una partida, toneladas > 0, bultos > 0
* \[x ] Controlador que llame a `EntradaService::crear()` dentro de try/catch
* \[x ] Listado de entradas con filtros por bodega y rango de fechas
* \[ x] Vista de detalle de una entrada (folio, partidas, quién la capturó)
* \[ x] **Pantalla de Inventario actual**: tabla bodega × variedad con toneladas y bultos
(consulta directa a la tabla `inventarios` con sus relaciones)

**Meta de la fase:** puedes meter trigo a cualquier bodega y ver el inventario actualizado en pantalla.

\---

## Fase 3 — Crear Salidas (venta y traspaso)

El corazón del sistema. Aquí se captura el documento y se descuenta el inventario.

* \[ x] Formulario de nueva salida con campos **dinámicos según el tipo**:

  * \[ x] Tipo: venta o traspaso (radio o select)
  * \[ x] Si es **venta**: mostrar cliente (nombre), teléfono y forma de pago (contado / crédito / 50-50)
  * \[ x] Si es **traspaso**: mostrar bodega destino (ocultar cliente y forma de pago)
  * \[x ] Bodega origen, fecha, observaciones (siempre visibles)
* \[x ] Tabla de partidas: variedad, toneladas, bultos, precio por tonelada (precio oculto en traspasos)
* \[ x] Mostrar en vivo los **totales** (toneladas, bultos, importe) con JS conforme capturan
* \[ x] Validación con FormRequest:

  * \[ x] Si tipo = venta → `cliente\_nombre` y `forma\_pago` requeridos
  * \[ x] Si tipo = traspaso → `ubicacion\_destino\_id` requerida y diferente a la origen
  * \[x ] Al menos una partida; toneladas y bultos mayores a 0
  * \[ x] **Validar congruencia bultos vs toneladas** usando `Variedad::bultosEsperados()`
(ej. si el bulto pesa 50 kg, 10 toneladas ≈ 200 bultos; avisar si se desvía mucho)
* \[x ] Controlador que llame a `SalidaService::crear()` en try/catch
(si no hay existencia, el servicio lanza excepción con mensaje claro → mostrarlo al usuario)
* \[x ] Al guardar, redirigir a la vista de detalle mostrando el **folio generado**
* \[x ] Listado de salidas con filtros: estatus, tipo, bodega, fechas, folio
* \[ x] Vista de detalle: todos los datos + estatus con color (pendiente amarillo, entregada verde, cancelada rojo)

**Meta de la fase:** creas una venta o traspaso, se genera el folio y el inventario de la bodega origen baja al instante.

\---

## Fase 4 — Impresión del documento con código de barras

* \[x ] Vista PDF del documento (dompdf): encabezado, folio, datos del cliente o bodega destino, partidas, totales, firmas
* \[ ] Generar el código de barras **Code128 del folio** con picqer y meterlo al PDF:

```php
$generator = new \\Picqer\\Barcode\\BarcodeGeneratorPNG();
$barcode = base64\_encode(
    $generator->getBarcode($salida->codigo\_barras, $generator::TYPE\_CODE\_128)
);
// En el blade: <img src="data:image/png;base64,{{ $barcode }}">
```

* \[ ] Botón "Imprimir" en el detalle de la salida
* \[ ] Probar que el escáner (Zebra o el que usen) lea el código impreso **antes** de seguir a la Fase 5

**Meta de la fase:** el documento sale impreso con su código de barras y el escáner lo lee bien.

\---

## Fase 5 — Entrega por escaneo

* \[ ] Pantalla de "Entregar salida": un solo input de texto con **autofocus**
(el escáner escribe el código y manda Enter, como si fuera teclado)
* \[ ] Al recibir el código, llamar a `SalidaService::entregar()` en try/catch
* \[ ] Mostrar feedback grande y claro:

  * \[ ] ✅ Verde: "Salida S-2026-000123 entregada" + resumen (tipo, bodega, cliente, toneladas)
  * \[ ] ❌ Rojo: el mensaje de la excepción ("ya está entregada", "no existe ese código", etc.)
* \[ ] Regresar el foco al input después de cada escaneo (para escanear en cadena)
* \[ ] Permitir también teclear el folio a mano (por si el código está dañado)
* \[ ] Verificar que al entregar un **traspaso**, el inventario de la bodega destino suba

**Meta de la fase:** escaneas el papel, cambia a entregada con fecha/hora y usuario, y los traspasos llegan a la bodega destino.

\---

## Fase 6 — Cancelación

* \[ ] Botón "Cancelar" visible **solo** en salidas con estatus pendiente
* \[ ] Modal que pida el **motivo** (obligatorio) y confirme la acción
* \[ ] Llamar a `SalidaService::cancelar()` en try/catch
* \[ ] Verificar en pantalla de inventario que el trigo regresó a la bodega origen
* \[ ] (Opcional) Restringir la cancelación a un rol específico con Spatie

**Meta de la fase:** una salida equivocada se cancela con motivo y el inventario regresa solo.

\---

## Fase 7 — Reportes y consultas

* \[ ] **Kardex por bodega/variedad**: listado de `movimientos\_inventario` con filtros de fecha
(aquí se ve entrada por entrada y salida por salida, con signo y usuario)
* \[ ] Reporte de ventas por periodo: total de toneladas, bultos e importe, por forma de pago
* \[ ] Reporte de traspasos entre bodegas
* \[ ] Historial de estatus de cada salida (tabla `salida\_historial`) en la vista de detalle
* \[ ] Exportar a Excel con PhpSpreadsheet (ya dominas esto de Indicadores)

**Meta de la fase:** puedes responder "¿cuánto se vendió este mes?" y "¿por qué el inventario dice X?" sin abrir phpMyAdmin.

\---

## Fase 8 — Roles y permisos (Spatie)

* \[ ] Definir roles, por ejemplo:

  * **Capturista**: crea entradas y salidas
  * **Entregador**: solo pantalla de escaneo
  * **Supervisor**: todo + cancelar
  * **Admin**: todo + catálogos y usuarios
* \[ ] Proteger rutas con middleware `role:` / `permission:`
* \[ ] Ocultar botones del menú según el rol (como en Indicadores)

**Meta de la fase:** cada quien ve y hace solo lo que le toca.

\---

## Fase 9 — Pruebas y arranque

* \[ ] Probar el flujo completo feliz: entrada → venta → imprimir → escanear → entregada
* \[ ] Probar el flujo de traspaso: crear → verificar que baja origen → entregar → verificar que sube destino
* \[ ] Probar los casos de error:

  * \[ ] Vender más trigo del que hay (debe rechazar y no mover nada)
  * \[ ] Escanear un código ya entregado (debe avisar, no duplicar)
  * \[ ] Escanear un código cancelado o inexistente
  * \[ ] Cancelar una salida ya entregada (debe negarse)
  * \[ ] Dos usuarios guardando salidas al mismo tiempo (los folios no deben repetirse)
* \[ ] Cuadrar inventario: suma de `movimientos\_inventario` = saldo en `inventarios`
* \[ ] Configurar respaldo automático de la BD (mysqldump programado)
* \[ ] Capacitar a los usuarios (una pantalla por rol, 15 minutos como tus pláticas de ciberseguridad)

**Meta de la fase:** sistema en producción y tú durmiendo tranquilo.

\---

\---

# ¿Qué son los Servicios y cómo se usan?

## El problema que resuelven

Crear una salida no es solo un `INSERT`. Son como 6 operaciones que tienen que pasar **todas o ninguna**: generar folio, guardar el documento, guardar las partidas, descontar inventario, registrar el kardex y registrar el historial. Si eso lo pones en el controlador, te queda un controlador de 200 líneas imposible de mantener, y si mañana quieres crear salidas desde otro lado (una API, un comando artisan, un import de Excel), tienes que copiar y pegar todo.

## La solución

Un **servicio** es una clase normal de PHP que agrupa la lógica de negocio de un tema. Piensa en tu MVC de siempre pero con una capa más:

```
Ruta → Controlador → Servicio → Modelos → BD
         (tráfico)   (reglas)   (datos)
```

* El **controlador** solo recibe el request, valida y decide qué vista/respuesta regresar. Es el mesero.
* El **servicio** hace el trabajo pesado con todas las reglas de negocio. Es el cocinero.

En tu sistema hay dos: `SalidaService` (crear, entregar, cancelar) y `EntradaService` (ingresar inventario). Los dos usan `DB::transaction()`: si cualquier paso falla a la mitad —por ejemplo, no hay existencia suficiente— MySQL revierte **todo** y la base queda como si nada hubiera pasado. Nunca vas a tener una salida guardada sin su descuento de inventario, ni un descuento sin documento.

## Cómo se usan

En el controlador, lo pides con `app()` y lo llamas dentro de try/catch:

```php
use App\\Services\\SalidaService;

public function store(StoreSalidaRequest $request)
{
    try {
        $salida = app(SalidaService::class)->crear(
            datos: $request->only(\[
                'tipo', 'ubicacion\_origen\_id', 'ubicacion\_destino\_id',
                'cliente\_nombre', 'cliente\_telefono', 'fecha',
                'forma\_pago', 'observaciones',
            ]),
            detalles: $request->input('detalles'), // arreglo de partidas
            usuario: auth()->user(),
        );

        return redirect()
            ->route('salidas.show', $salida)
            ->with('success', "Salida {$salida->folio} creada correctamente.");

    } catch (\\RuntimeException $e) {
        return back()->withInput()->with('error', $e->getMessage());
    }
}
```

Los servicios lanzan `RuntimeException` con mensajes ya redactados para el usuario ("Existencia insuficiente en la bodega origen…", "La salida ya está entregada…"). Tú solo la atrapas y la muestras. No tienes que revisar existencias ni estatus en el controlador: el servicio ya lo hace, y lo hace **dentro** de la transacción, que es el único lugar seguro para hacerlo.

**Regla práctica:** si una operación toca más de una tabla o tiene reglas de negocio, va en el servicio. Si solo es leer y mostrar, el controlador puede consultar los modelos directo.

\---

# ¿Qué son los Enums y cómo se usan?

## El problema que resuelven

Sin enums, los estatus y tipos viven como **strings mágicos** regados por todo el código:

```php
// Así NO — el clásico que truena:
if ($salida->estatus == 'Pendiente') { ... }   // en la BD es 'pendiente', nunca entra
if ($salida->tipo == 'transpaso') { ... }      // typo, nunca entra, y PHP no te avisa
```

Te suena, ¿no? Es primo hermano del bug de `tipo\_calculo` en Indicadores: comparaciones contra valores "a mano" que fallan en silencio. El código compila, corre, y simplemente no hace lo que debe.

## La solución

Un **enum** (PHP 8.1+) es un tipo que define la lista cerrada de valores válidos. Ya no escribes el string: usas la constante, y si te equivocas en el nombre, PHP truena de inmediato en lugar de fallar calladito.

Tu sistema trae cuatro, en `app/Enums/`:

|Enum|Valores|Se usa en|
|-|-|-|
|`SalidaTipo`|venta, traspaso|`salidas.tipo`|
|`SalidaEstatus`|pendiente, entregada, cancelada|`salidas.estatus`, historial|
|`FormaPago`|contado, credito, 50\_50|`salidas.forma\_pago`|
|`TipoMovimiento`|entrada, salida\_venta, salida\_traspaso, entrada\_traspaso, reverso\_cancelacion|kardex|

Los modelos ya los tienen conectados con `casts`, así que Eloquent convierte solo: al leer de la BD te da el enum, al guardar escribe el string correcto.

## Cómo se usan

**Comparar** (lo más común):

```php
use App\\Enums\\SalidaEstatus;
use App\\Enums\\SalidaTipo;

if ($salida->estatus === SalidaEstatus::Pendiente) { ... }
if ($salida->tipo === SalidaTipo::Traspaso) { ... }

// O con los helpers que ya trae el modelo:
if ($salida->estaPendiente()) { ... }
if ($salida->esTraspaso()) { ... }
```

**Obtener el valor** (el string que va en la BD) **o la etiqueta** (texto bonito para pantalla):

```php
$salida->estatus->value;       // "pendiente"
$salida->estatus->etiqueta();  // "Pendiente por entregar"
$salida->forma\_pago->etiqueta(); // "50 / 50"
```

**Llenar un select en el formulario** sin escribir las opciones a mano:

```php
// Controlador:
$formasPago = \\App\\Enums\\FormaPago::cases();

// Blade:
@foreach ($formasPago as $forma)
    <option value="{{ $forma->value }}">{{ $forma->etiqueta() }}</option>
@endforeach
```

**Asignar al crear o actualizar:**

```php
$salida->update(\['estatus' => SalidaEstatus::Entregada]);
```

**Convertir un string que llega del request a enum** (valida de paso):

```php
$tipo = SalidaTipo::from($request->input('tipo'));
// Si mandan "transpaso" (typo), lanza excepción en lugar de guardar basura.
// Versión suave: SalidaTipo::tryFrom($valor) regresa null si no existe.
```

**Regla práctica:** en todo el proyecto, jamás escribas `'pendiente'`, `'venta'` o `'contado'` entre comillas. Si estás tecleando uno de esos strings, detente y usa el enum. Así el typo es imposible y si algún día agregas un estatus nuevo, lo agregas en UN solo lugar.

\---

## Checklist final — que no se te pase nada

* \[ ] Zona horaria en `America/Mexico\_City` (las fechas de entrega salen del servidor)
* \[ ] El input de escaneo siempre con autofocus y que recupere el foco tras cada lectura
* \[ ] Validar bultos vs toneladas con `peso\_bulto\_kg` antes de guardar
* \[ ] Nunca borrar salidas, entradas, ni renglones de kardex — todo se cancela o se reversa
* \[ ] Los totales de la salida los calcula el servicio (y el importe la BD); no confiar en los del navegador
* \[ ] Probar el escáner con el PDF real impreso, no solo en pantalla
* \[ ] Respaldo diario de la BD desde el día uno
* \[ ] Cuadre periódico: `SUM(movimientos)` = saldo en `inventarios` por bodega/variedad


Traer lo ultimo que escribi 
git pull


Al terminar para guardar lo que hice en el trabajo 

git add .
git commit -m "Avances realizados en la oficina"
git push


composer no lo jala en GitHub 
por lo que se tiene que instalar 
composer install
