<div class="redfooter">
    <footer class="d-flex flex-wrap justify-content-between align-items-center">
        <div class="col-md-4 d-flex align-items-center">
          <a href="/" class="mb-3 me-2 mb-md-0 text-body-secondary text-decoration-none lh-1">
            <img src="{{ asset('img/assets/escudo_footer.png') }}">
          </a>
        </div>
    
        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-5">
                <div class="col mb-3">
                <p class="text-light">CENTRO UNIVERSITARIO DE CIENCIAS SOCIALES Y HUMANIDADES <br>
                Red Internacional de Cátedras Martianas</p>
                </div>
              
                <div class="col mb-3">
              
                </div>
              
                <div class="col mb-3">
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Inicio</a></li>
                        <li class="nav-item mb-2"><a href="{{ url('/#historia') }}" class="nav-link p-0 text-body-secondary">Historia</a></li>
                        <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Galería</a></li>
                        <li class="nav-item mb-2"><a href="{{ url('/#directorio') }}" class="nav-link p-0 text-body-secondary">Directorio</a></li>
                        <li class="nav-item mb-2"><a href="{{ route('convocatorias') }}" class="nav-link p-0 text-body-secondary">Convocatorias</a></li>
                    </ul>
                </div>
              
                <div class="col mb-3">
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a href="{{ route('actividades') }}" class="nav-link p-0 text-body-secondary">Actividades de la red</a></li>
                        <li class="nav-item mb-2"><a href="{{ route('martianas') }}" class="nav-link p-0 text-body-secondary">Actividades martianas</a></li>
                    </ul>
                </div>
            </div>
        </div>
      </footer> 
</div>
