@include('componentes.applayout2')
@include('componentes.header')
<body>
    <div class="container mb-2">
        <div class="row g-3 mt-2">
            <div class="col-md-8">
                <h2 class="text-center">Directorio</h2>
    
                <!-- Sección de usuarios destacados -->
                <div class="d-flex flex-column align-items-center mb-3">
                    @foreach ($usuariosDestacados as $usuario)
                        <div class="card m-2 p-3 border-dot text-center" style="width: 20rem; cursor: pointer;"
                        data-nombre="{{ $usuario->nombre }}"
                        data-imagen="{{ $usuario->imagen ? asset('storage/directorio/' . $usuario->imagen) : asset('img/assets/headercm_2.jpg') }}"
                        data-area="{{ $usuario->area }}"
                        data-correo="{{ $usuario->correo }}"
                        data-descripcion="{{ $usuario->descripcion }}"
                        onclick="mostrarModal(this)">
                            @if ($usuario->imagen)
                                <div class="content-center">
                                    <img src="{{ asset('storage/directorio/' . $usuario->imagen) }}" class="img-corner border-dot" alt="Imagen de {{ $usuario->nombre }}">
                                </div>
                            @endif
                            <h5 class="text-primary">{{ $usuario->nombre }}</h5>
                            <p class="mb-0 text-muted">{{ $usuario->area }}</p>
                            {{-- <p class="text-muted">descripcion: {{ $usuario->descripcion }}</p> --}}
                            @if ($usuario->correo)
                                <p><a href="mailto:{{ $usuario->correo }}">{{ $usuario->correo }}</a></p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="d-flex flex-wrap justify-content-center">
                    <h2 class="text-center">Cátedras de la red</h2>
                </div>
    
                <div class="d-flex flex-wrap justify-content-center">
                    @foreach ($directorios as $pais => $personas)
                        <div class="w-100">
                            <button class="btn btn-primary w-100 text-center mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ Str::slug($pais) }}">
                                {{ $pais }}
                            </button>
                            <div class="collapse" id="collapse-{{ Str::slug($pais) }}">
                                <div class="d-flex justify-content-center"> <!-- Centra el card -->
                                    <div class="card m-2 p-2 border-dot" style="width: 30rem;">
                                        <ul class="list-group list-group-flush">
                                            @foreach ($personas as $persona)
                                                <li class="list-group-item d-flex align-items-center"
                                                    style="cursor: pointer;"
                                                    data-nombre="{{ $persona->nombre }}"
                                                    data-imagen="{{ $usuario->imagen ? asset('storage/directorio/' . $usuario->imagen) : asset('img/assets/headercm_2.jpg') }}"
                                                    data-area="{{ $persona->area }}"
                                                    data-correo="{{ $persona->correo }}"
                                                    data-descripcion="{{ $persona->descripcion }}"
                                                    onclick="mostrarModal2(this)">
                                                        <img src="{{ asset('img/assets/headercm_2.jpg') }}" class="img-corner border-dot" alt="Imagen de {{ $persona->nombre }}">
                                                    <div class="ml-3">
                                                        <strong>{{ $persona->nombre }}</strong>
                                                        <p class="mb-0 text-muted">{{ $persona->area }}</p>
                                                        @if ($persona->correo)
                                                            <p class="mb-0"><a href="mailto:{{ $persona->correo }}">{{ Str::limit($persona->correo, 50) }}</a></p>
                                                        @endif
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
            </div>
    
            <div class="col-md-4">
                @include('componentes.noticias', ['noticias' => $noticias])
            </div>
        </div>
    </div>
    
 <!-- Modal de Bootstrap -->
<div class="modal fade" id="usuarioModal" tabindex="-1" aria-labelledby="usuarioModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="usuarioModalLabel"></h5>
{{--           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button> --}}
        </div>
        <div class="modal-body text-center">
            <img id="usuarioImagen" src="" class="img-fluid object-fit-cover mb-2 rounded-circle border-dot" style="width: 100px; height: 100px;" alt="Imagen del usuario">
            <p><strong>Área:</strong> <span id="usuarioArea"></span></p>
            <div id="correoContainer">
                <p><strong>Correo:</strong> <a href="" id="usuarioCorreo"></a></p>
            </div>
            <div id="descripcionContainer">
                <p><strong>Descripción:</strong> <span id="usuarioDescripcion"></span></p>
            </div>
        </div>
      </div>
    </div>
  </div>
  
  

</body>
@include('componentes.footer')
<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function mostrarModal(element) {
        let nombre = element.getAttribute("data-nombre");
    let imagen = element.getAttribute("data-imagen") || "{{ asset('img/default-user.png') }}";
    let area = element.getAttribute("data-area");
    let correo = element.getAttribute("data-correo");
    let descripcion = element.getAttribute("data-descripcion");

    $("#usuarioModalLabel").text(nombre);
    $("#usuarioImagen").attr("src", imagen);

    $("#usuarioArea").text(area);

    if (correo) {
        $("#usuarioCorreo").text(correo).attr("href", "mailto:" + correo).parent().show();
    } else {
        $("#usuarioCorreo").parent().hide();
    }

    if (descripcion) {
        $("#usuarioDescripcion").text(descripcion).parent().show();
    } else {
        $("#usuarioDescripcion").parent().hide();
    }

    $("#usuarioModal").modal("show");
    }
</script>
<script>
    function mostrarModal2(element) {
        let nombre = element.getAttribute("data-nombre");
    let imagen = element.getAttribute("data-imagen") || "{{ asset('img/default-user.png') }}";
    let area = element.getAttribute("data-area");
    let correo = element.getAttribute("data-correo");
    let descripcion = element.getAttribute("data-descripcion");

    $("#usuarioModalLabel").text(nombre);
    $("#usuarioImagen").attr("src", imagen);

    $("#usuarioArea").text(area);

    if (correo) {
        $("#usuarioCorreo").text(correo).attr("href", "mailto:" + correo).parent().show();
    } else {
        $("#usuarioCorreo").parent().hide();
    }

    if (descripcion) {
        $("#usuarioDescripcion").text(descripcion).parent().show();
    } else {
        $("#usuarioDescripcion").parent().hide();
    }

    $("#usuarioModal").modal("show");
    }
</script>

</html>