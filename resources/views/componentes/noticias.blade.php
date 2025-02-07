<div class="position-sticky" style="top: 2rem;">
        
    <div>
      <h4 class="fst-italic">Noticias</h4>
      <ul class="list-unstyled">
        @forelse($noticias as $noticia)
          <li>
            <a class="d-flex flex-column flex-lg-row gap-3 align-items-start align-items-lg-center py-3 link-body-emphasis text-decoration-none border-top" href="{{ route('visualizar_actividades', ['id' => $noticia->id]) }}">
              @php
                $imagen = $noticia->documentacionAs->isNotEmpty() 
                          ? 'documentacion_actividades/' .$noticia->documentacionAs->first()->archivo 
                          : 'img/assets/icono.jpg'; // Imagen por defecto si no hay imágenes
                $nombreArchivo = pathinfo($imagen, PATHINFO_FILENAME);
                $extension = pathinfo($imagen, PATHINFO_EXTENSION);

                $imagen = ($extension == 'pdf') 
                          ? 'img/assets/icono.jpg' 
                          : $imagen;
              @endphp
              <img src="{{ asset($imagen) }}" class="bd-placeholder-img" width="100" height="96" aria-hidden="true" preserveAspectRatio="xMidYMid slice">
              <div class="col-lg-8">
                <h6 class="mb-0">{{ $noticia->titulo }}</h6>
                <small class="text-body-secondary">{{ $noticia->fecha }}</small>
              </div>
            </a>
          </li>
        @empty
          <li>No hay noticias disponibles.</li>
        @endforelse
      </ul>
    </div>

    <div class="p-4">
      <h4 class="fst-italic">Red Universitaria</h4>
      <ol class="list-unstyled mb-0">
        <li class="views-row views-row-1 views-row-odd views-row-first p-2">  
            <div class="views-field views-field-field-banner">
                <div class="field-content">
                    <a href="http://catedramarti.srp.ucr.ac.cr/marti2.html">
                        <img typeof="foaf:Image" src="{{ asset('img/assets/red_universitaria1.jpg') }}" width="280" height="97" alt="">
                    </a>
                </div>  
            </div>
        </li>
        <li class="views-row views-row-2 views-row-even p-2">  
            <div class="views-field views-field-field-banner">
                <div class="field-content">
                    <a href="http://www.martiano.cu/">
                        <img typeof="foaf:Image" src="{{ asset('img/assets/red_universitaria2.jpg') }}" width="280" height="97" alt="">
                    </a>
                </div>
            </div>
        </li>
        <li class="views-row views-row-3 views-row-odd p-2">  
            <div class="views-field views-field-field-banner">
                <div class="field-content">
                    <a href="http://www.josemarti.cu/">
                        <img typeof="foaf:Image" src="{{ asset('img/assets/red_universitaria3.jpg') }}" width="280" height="97" alt="">
                    </a>
                </div>
            </div>
        </li>
        <li class="views-row views-row-4 views-row-even views-row-last p-2">  
            <div class="views-field views-field-field-banner">
                <div class="field-content">
                    <a href="http://www.cmarti.cucsh.udg.mx/">
                        <img typeof="foaf:Image" src="{{ asset('img/assets/red_universitaria4.jpg') }}" width="280" height="97" alt="">
                    </a>
                </div>
            </div>
        </li>
      </ol>
    </div>

  </div>