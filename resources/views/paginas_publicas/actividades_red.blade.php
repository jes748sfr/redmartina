@include('componentes.applayout')
@include('componentes.header')
<body>
  @if(isset($actividad))
  <div class="container mt-4">
    <div class="card-header">
        {{ $actividad->header }}
    </div>
    <div class="card-body">
        <h3 class="card-title text-justify">{{ $actividad->titulo }}</h3>

        {{-- Mostrar imágenes arriba del cuerpo --}}
        @php
            $imagenes = $documentos_actividad->filter(function ($documento) {
                return in_array(pathinfo($documento->archivo, PATHINFO_EXTENSION), ['jpeg', 'jpg', 'png']);
            });
        @endphp

        @if ($imagenes->isNotEmpty())
            <div class="mb-3 d-flex flex-wrap justify-content-center gap-2">
                @foreach ($imagenes as $documento)
                    <div class="image-container">
                        <a class="optimized-img" href="{{ asset('documentacion_actividades/' . $documento->archivo) }}" data-lightbox="documento{{ $documento->id_galeria }}">
                            <img src="{{ asset('documentacion_actividades/' . $documento->archivo) }}" class="optimized-img" alt="Imagen">
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Cuerpo de la actividad --}}
        <p class="card-text text-justify">{!! $actividad->cuerpo !!}</p>

        {{-- Mostrar PDFs abajo del cuerpo --}}
        @php
            $pdfs = $documentos_actividad->filter(function ($documento) {
                return pathinfo($documento->archivo, PATHINFO_EXTENSION) === 'pdf';
            });
        @endphp

        @if ($pdfs->isNotEmpty())
            <div class="mt-4">
                <h5>Documentos adjuntos:</h5>
                @foreach ($pdfs as $documento)
                    <p>
                        <a href="{{ asset('documentacion_actividades/' . $documento->archivo) }}" target="_blank">
                            📄 Ver PDF ({{ $documento->archivo }})
                        </a>
                    </p>
                @endforeach
            </div>
        @endif
    </div>
</div>
  @else
    <div class="container mb-2">
        <div class="row g-5 mt-2">
          <div class="col-md-8">
            <div class="container">
                <div class="row">
                    @foreach($actividades as $index => $actividad)
                        <div id="cartas" class="col-12 col-md-4 mb-3">
                          <a class="link-offset-2 link-underline link-underline-opacity-0" href="{{ route('visualizar_actividades', $actividad->id) }}">
                            <div class="card mb-3 h-100 d-flex flex-column" style="max-width: 18rem;">
                                <!-- Asignar un degradado dinámico según el índice -->
                                <div class="card-header @if($index % 3 == 0) degradado-1
                                                       @elseif($index % 3 == 1) degradado-2
                                                       @else degradado-3 @endif">
                                    {{ $actividad->header }}
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $actividad->titulo }}</h5>
                                    <p class="card-text text-justify">
                                      {!! $actividad->cuerpo_truncado !!}
                                    </p>
                                </div>
                            </div>
                          </a>  
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="mt-6">
                {{ $actividades->links('pagination::bootstrap-5') }}
            </div>            
        </div>
        
            <div class="col-md-4">
              @include('componentes.noticias', ['noticias' => $noticias])
            </div>
          </div>
    </div>    
  @endif
</body>
@include('componentes.footer')
<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('vendor/lightbox2-2.11.5/dist/js/lightbox-plus-jquery.js') }}"></script>
<script>
  tinymce.init({
      selector: '#cuerpo', // Selector del textarea
      plugins: 'link image media table codesample fullscreen',
      toolbar: 'undo redo | styleselect | bold italic | link image media | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table codesample fullscreen',
      height: 300,
      menubar: false,
      branding: false,
      automatic_uploads: true,
      /* images_upload_url: '/subir-imagen',
      images_upload_handler: function (blobInfo, success, failure) {
          let formData = new FormData();
          formData.append('file', blobInfo.blob(), blobInfo.filename());
          fetch('/subir-imagen', {
              method: 'POST',
              headers: {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              body: formData,
          })
          .then(response => response.json())
          .then(data => success(data.location))
          .catch(error => failure('Error al subir la imagen: ' + error.message));
      } */
  });
</script>
</html>