<link rel="icon" type="image/x-icon" href="{{ asset('img/assets/icono.jpg') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<style>
  .nav-link {
    color: white !important; /* Forzar color blanco */
    text-decoration: none;
    padding: 0.5rem 0.8rem; /* Espaciado */
    border-radius: 5px;
    transition: color 0.3s ease, box-shadow 0.3s ease; /* Animación suave */
    text-align: center;
    position: relative; /* Necesario para el box-shadow */
  }

  /* Línea amarilla en hover */
  .nav-link:hover {
    color: #4DA1A9 !important; /* Color en hover */
    box-shadow: inset 0 -2px 0 0 #4DA1A9; /* Línea inferior */
  }

  /* Enlace activo */
  .nav-link.active {
    color: #4DA1A9 !important; /* Color activo */
    box-shadow: inset 0 -2px 0 0 #4DA1A9;
  }

  /* Fondo del navbar */
  .navbar {
    background-color: #2E5077;
  }

  /* Color del texto del botón del formulario */
  .btn-outline-light {
    color: white;
    border-color: white;
  }

  .btn-outline-light:hover {
    color: #4DA1A9;
    border-color: #4DA1A9;
    background-color: transparent;
  }

   /* Contenedor para la imagen del encabezado */
   .header-container {
    width: 100%; /* Ocupa todo el ancho */
    height: auto; /* Se adapta dinámicamente */
    position: relative;
    overflow: hidden; /* Oculta desbordes si es necesario */
    margin-top: 70px;
  }

  @media (max-width: 768px) {
  /* Ajuste del margen para pantallas más pequeñas */
  .header-container {
    margin-top: 90px; /* Ajustar según el tamaño del navbar en móviles */
  }
}

  /* Imagen responsiva */
  .header-container img {
    width: 100%; /* Ocupa todo el ancho */
    height: auto; /* Mantiene proporciones */
    display: block; /* Evita espacios extra debajo de la imagen */
  }

  /* Para pantallas pequeñas, puedes ajustar la altura */
  @media (max-width: 768px) {
    .header-container {
      height: auto; /* Permite que el contenedor crezca si es necesario */
    }
  }

  /* Ajuste adicional para evitar superposición con el navbar */
  body {
    margin: 0;
    padding: 0;
  }

  .redfooter{
    background-color: #2E5077;
  }

  .nav-item {
    margin-right: 15px; /* Ajusta el espacio horizontal entre los enlaces */
  }
</style>

<nav class="navbar navbar-expand-lg navbar-light fixed-top">
  <!-- Logo -->
  <a class="navbar-brand" href="#">
    <img src="{{ asset('img/assets/logo-bgNa.png') }}" width="70" height="70" class="d-inline-block align-top" alt="">
  </a>

  <!-- Botón colapsable para móviles -->
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Contenedor colapsable -->
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <li class="nav-item">
        <a href="{{ url('/') }}" class="nav-link {{ Request::is('/') ? '' : '' }}">Inicio</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/#historia') }}" class="nav-link {{ Request::is('/#historia') ? '' : '' }}">Historia</a>
      </li>
      <li class="nav-item">
        <a href="{{ route('actividades') }}" class="nav-link {{ Request::routeIs('actividades') ? 'active' : '' }}">Actividades de la Red</a>
      </li>
      <li class="nav-item">
        <a href="{{ route('martianas') }}" class="nav-link {{ Request::routeIs('martianas') ? 'active' : '' }}">Actividades martianas</a>
      </li>
      <li class="nav-item">
        <a href="{{ route('convocatorias') }}" class="nav-link {{ Request::routeIs('convocatorias') ? 'active' : '' }}">Convocatorias</a>
      </li>
      <li class="nav-item">
        <a href="{{ route('galeria') }}" class="nav-link {{ Request::routeIs('galeria') ? 'active' : '' }}">Galería</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/#directorio') }}" class="nav-link {{ Request::is('directorio') ? '' : '' }}">Directorio</a>
      </li>
    </ul>

    <!-- Formulario de búsqueda -->
    <form class="d-flex" method="POST" action="{{ route('buscar') }}" role="search">
      @csrf
      <input name="keyword" type="search" autocomplete="off" class="form-control me-2 rounded-pill" placeholder="Buscar..." aria-label="Buscar">
      <button class="btn btn-outline-light rounded-pill" type="submit">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 24px; height: 24px;">
          <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
      </button>
    </form>
  </div>
</nav>

<div class="header-container">
  <img src="{{ asset('img/assets/headercm_0.jpg') }}" alt="Header Image">
</div>