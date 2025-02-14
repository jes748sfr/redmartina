<!-- Ícono y estilos -->
<link rel="icon" type="image/x-icon" href="{{ asset('img/assets/icono.jpg') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="{{ asset('css/header.css') }}">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
  <div class="container-fluid">
    <!-- Logo -->
    <a class="navbar-brand" href="#">
      <img src="{{ asset('img/assets/logo-bgNa.png') }}" width="60" height="60" class="d-inline-block align-top" alt="Logo">
    </a>

    <!-- Botón de menú para móviles -->
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menú lateral en móviles -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="navbarNav" aria-labelledby="navbarNavLabel" style="background-color: #2E5077;">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title text-light" id="navbarNavLabel">Menú</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a href="{{ url('/') }}" class="nav-link">Inicio</a></li>
          <li class="nav-item"><a href="{{ url('/#historia') }}" class="nav-link">Historia</a></li>
          <li class="nav-item"><a href="{{ route('actividades') }}" class="nav-link">Actividades de la Red</a></li>
          <li class="nav-item"><a href="{{ route('martianas') }}" class="nav-link">Actividades Martianas</a></li>
          <li class="nav-item"><a href="{{ route('convocatorias') }}" class="nav-link">Convocatorias</a></li>
          <li class="nav-item"><a href="{{ route('galeria') }}" class="nav-link">Galería</a></li>
          <li class="nav-item"><a href="{{ route('directorio') }}" class="nav-link">Directorio</a></li>
        </ul>

        <!-- Formulario de búsqueda -->
        <form class="d-flex mt-3" method="POST" action="{{ route('buscar') }}" role="search">
          @csrf
          <div class="input-group">
            <input name="keyword" type="search" autocomplete="off" class="form-control rounded-pill" placeholder="Buscar..." aria-label="Buscar" minlength="3" required>
            <button class="btn btn-outline-light rounded-pill ms-2" type="submit">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 24px; height: 24px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
              </svg>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</nav>

<!-- Imagen de cabecera -->
<div class="header-container">
  <img src="{{ asset('img/assets/headercm_0.jpg') }}" alt="Header Image" class="img-fluid w-100">
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    let input = document.querySelector('input[name="keyword"]');
    let button = document.querySelector('button[type="submit"]');

    // Desactivar el botón al inicio
    button.disabled = true;

    input.addEventListener("input", function() {
        if (input.value.trim().length >= 3) {
            button.disabled = false; // Habilitar el botón
        } else {
            button.disabled = true; // Deshabilitar el botón
        }
    });
});
</script>
