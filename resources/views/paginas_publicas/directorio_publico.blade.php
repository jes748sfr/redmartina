<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Red Internacional de Cátedras Martianas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
    html {
        scroll-behavior: smooth;
    }
    .img-corner {
      position: absolute;
      top: 10px; /* Espacio desde la parte superior */
      right: 10px; /* Espacio desde la derecha */
      width: 50px; /* Tamaño de la imagen */
      height: 50px; /* Tamaño de la imagen */
      border-radius: 50%; /* Hace la imagen circular */
      object-fit: cover; /* Ajusta la imagen dentro del contenedor */
    }
    .border-dot{
      border-style: double;
      border-color: #4DA1A9;
      border-width: 4px;
    }
    </style>

</head>
@include('componentes.header')
<body>
    <div class="container mb-2">
        <div class="row g-3 mt-2">
            <div class="col-md-8">
                <h2 class="text-center">Organigrama</h2>
    
                <!-- Sección de usuarios destacados -->
                <div class="d-flex flex-column align-items-center mb-3">
                    @foreach ($usuariosDestacados as $usuario)
                        <div class="card m-2 p-3 border-dot text-center" style="width: 20rem;">
                            @if ($usuario->imagen)
                                <img src="{{ asset('storage/img/usuarios/' . $usuario->imagen) }}" class="img-corner" alt="Imagen de {{ $usuario->nombre }}">
                            @endif
                            <h5 class="text-primary">{{ $usuario->nombre }}</h5>
                            <p class="text-muted">{{ $usuario->area }}</p>
                            @if ($usuario->correo)
                                <p><a href="mailto:{{ $usuario->correo }}">{{ $usuario->correo }}</a></p>
                            @endif
                        </div>
                    @endforeach
                </div>
    
                <!-- Sección de directorios por país -->
                <div class="d-flex flex-wrap justify-content-center">
                    @foreach ($directorios as $pais => $personas)
                        <div class="card m-2 p-2 border-dot" style="width: 18rem;">
                            <h5 class="text-center text-primary">{{ $pais }}</h5>
                            <ul class="list-group list-group-flush">
                                @foreach ($personas as $persona)
                                    <li class="list-group-item d-flex align-items-center">
                                        @if ($persona->imagen)
                                            <img src="{{ asset('storage/img/usuarios/' . $persona->imagen) }}" class="img-corner" alt="Imagen de {{ $persona->nombre }}">
                                        @endif
                                        <div class="ml-3">
                                            <strong>{{ $persona->nombre }}</strong>
                                            <p class="mb-0 text-muted">{{ $persona->area }}</p>
                                            @if ($persona->correo)
                                                <p class="mb-0"><a href="mailto:{{ $persona->correo }}">{{ $persona->correo }}</a></p>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
    
            <div class="col-md-4">
                @include('componentes.noticias', ['noticias' => $noticias])
            </div>
        </div>
    </div>   

</body>
@include('componentes.footer')
<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
</html>