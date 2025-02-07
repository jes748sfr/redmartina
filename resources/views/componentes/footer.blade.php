<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#2E5077" fill-opacity="1" d="M0,288L60,288C120,288,240,288,360,272C480,256,600,224,720,224C840,224,960,256,1080,272C1200,288,1320,288,1380,288L1440,288L1440,320L1380,320C1320,320,1200,320,1080,320C960,320,840,320,720,320C600,320,480,320,360,320C240,320,120,320,60,320L0,320Z"></path></svg>
<div class="redfooter">
      <footer class="d-flex flex-wrap text-center text-lg-start text-white">
        <div class="container p-4 pb-0">
            <section class="">
                <div class="row">
                    <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                        <img src="{{ asset('img/assets/escudo_footer.png') }}">
                        <p>CENTRO UNIVERSITARIO DE CIENCIAS SOCIALES Y HUMANIDADES <br>
                        Red Internacional de Cátedras Martianas</p>
                    </div>
                    <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                    </div>
                    <hr class="w-100 clearfix d-md-none" />
                    <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
                        <h6 class="text-uppercase mb-4 font-weight-bold text-center">Contenido</h6>
                        <p><a class="nav-link" href="{{ url('/') }}" class="text-white">Inicio</a></p>
                        <p><a class="nav-link" href="{{ url('/#historia') }}" class="text-white">Historia</a></p>
                        <p><a class="nav-link" href="{{ route('actividades') }}" class="text-white">Actividades de la red</a></p>
                        <p><a class="nav-link" href="{{ route('martianas') }}" class="text-white">Actividades martianas</a></p>
                    </div>
                    <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                        <p><a class="nav-link" href="{{ route('convocatorias') }}" class="text-white">Convocatorias</a></p>
                        <p><a class="nav-link" href="{{ route('galeria') }}" class="text-white">Galería</a></p>
                        <p><a class="nav-link" href="{{ route('directorio') }}" class="text-white">Directorio</a></p>
                    </div>
                </div>
            </section>
            <hr class="my-3">
            <section class="p-3 pt-0">
                <div class="row d-flex align-items-center">
                    <div class="col-md-7 col-lg-8 text-center text-md-start">
                        <div class="p-3">Universidad de Guadalajara © Derechos reservados ©1997 - 2013.</div>
                    </div>
                    <div class="col-md-5 col-lg-4 ml-lg-0 text-center text-md-end">
                        Sitio desarrollado en la
                        <a href="http://www.cucsh.udg.mx/" class="text-white">CTA</a><br>
                        <a href="{{ route('creditos') }}" class="text-white">Créditos de este sitio</a>
                        <a href="{{ route('politicas') }}" class="text-white">Políticas de uso y privacidad</a>
                      </div>
                </div>
            </section>
        </div>
    </footer>
    {{-- <footer class="d-flex flex-wrap justify-content-between align-items-center">
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
                        <li class="nav-item mb-2"><a href="{{ url('/') }}" class="nav-link p-0 text-body-secondary">Inicio</a></li>
                        <li class="nav-item mb-2"><a href="{{ url('/#historia') }}" class="nav-link p-0 text-body-secondary">Historia</a></li>
                        <li class="nav-item mb-2"><a href="{{ route('galeria') }}" class="nav-link p-0 text-body-secondary">Galería</a></li>
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
      </footer>  --}}
</div>
