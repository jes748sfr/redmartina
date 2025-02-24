@include('componentes.applayout2')
@include('componentes.header')
<body>
    <div class="container mb-2">
        <div class="row g-3 mt-2">
            <div class="col-md-8">
              @foreach($galerias as $galeria)
              <div class="card">
                <div class="card-header">
                  {{ $galeria->titulo }}
                </div>
                <div class="card-body">
                  <div id="carouselGaleria{{ $galeria->id }}" class="carousel slide pb-2" data-bs-ride="carousel">
                    <div class="carousel-inner">
                      @foreach($galeria->fotos as $index => $foto)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                          <a href="{{ asset('img/galeria/'.$foto->imagen) }}" data-lightbox="documento{{ $foto->id_galeria }}">
                            <img src="{{ asset('img/galeria/'.$foto->imagen) }}" class="d-block w-100" alt="Imagen {{ $foto->id }}" style="height: 300px; width: 100%; object-fit: contain;">
                          </a>
                        </div>
                      @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselGaleria{{ $galeria->id }}" data-bs-slide="prev" style="background-color: black">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselGaleria{{ $galeria->id }}" data-bs-slide="next" style="background-color: black">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">Next</span>
                    </button>
                  </div>
                  {{-- <a href="#" class="btn btn-primary">Visualizar</a> --}}
                </div>
              </div>
              @endforeach
              <div class="mt-6">
                {{ $galerias->links('pagination::bootstrap-5') }}
            </div>  
            </div>
        
            <div class="col-md-4">
              @include('componentes.noticias', ['noticias' => $noticias])
            </div>
          </div>
    </div>    

</body>
<script src="{{ asset('vendor/lightbox2-2.11.5/dist/js/lightbox-plus-jquery.js') }}"></script>
@include('componentes.footer')
<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
</html>