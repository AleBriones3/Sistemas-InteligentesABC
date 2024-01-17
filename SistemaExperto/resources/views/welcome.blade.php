<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>
    <script
			src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
			integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
			crossorigin="anonymous"
			referrerpolicy="no-referrer"
		></script>
    @vite(['resources/js/app.js'])
    <title>TRANSACCIÓN</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-TxCA8XGf7By2DmJT77P5LF5buvyhwE5xFm5oWB7z9Bc5JlLT5giw2x4Iwhd+ZVevi7+wJzDjDZ9D6UHnFIQtaQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.20.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
</head>
<body class="bg-info text-white">
    @include('partials.alerta')
    
    <h1 class="display-1 text-center mt-4">SistemaExperto <i class="bi bi-alphabet-uppercase"></i></h1>

    <div class="card text-bg-light mb-3 mx-auto mt-5" style="max-width:50rem;">
        <div class="card-header">
            <h2 class="text-center">Información de Tarjeta</h2>
        </div>
        <div class="card-body">
            <form action="/pagar" method="post">
                @csrf
                <label for="titular_tarjeta">Titular de la Tarjeta</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="titular_tarjeta" placeholder="Titular de la Tarjeta" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <span class="input-group-text" id="basic-addon2">
                        <i class="fa-solid fa-credit-card"></i>
                    </span>
                </div>

                <label for="numero_tarjeta">Número de Tarjeta</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="tarjeta" placeholder="Número de Tarjeta" aria-label="Número de Tarjeta" aria-describedby="card-type-addon" required value="{{ old('tarjeta') }}">
                    <span class="input-group-text" id="card-type-addon">
                        <i class="fab fa-cc-visa"></i>
                        <i class="fab fa-cc-mastercard"></i>
                    </span>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="fecha_expiracion">Fecha Expiración</label>
                        <div class="input-group mb-3">
                            <input type="month" class="form-control" name="fecha_validacion" placeholder="Fecha Expiración" aria-label="Fecha Expiración" aria-describedby="basic-addon2" required value="{{ old('fecha_validacion') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="cvv">CVV</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="cvv" placeholder="CVV" aria-label="CVV" aria-describedby="basic-addon2" required value="{{ old('cvv') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="objeto">Objeto</label>
                        <div class="input-group mb-3">
                            <select class="form-control" name="objeto" id="objeto" required>
                                <option value="" selected disabled>Selecciona un Producto</option>
                                @foreach($objetos as $objeto)
                                <option value="{{ $objeto->id }}" {{ old('objeto') == $objeto->id ? 'selected' : '' }}>{{ $objeto->producto }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <hr>

                <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-cart-shopping"></i> Realizar Pago</button>
            </form>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/d152bd8a2a.js" crossorigin="anonymous"></script>
</body>
</html>