@include('componentes.applayout2')
@include('componentes.header')
<body>

  <div class="container busqueda-container mt-5 pt-5">
    <h1>Resultados de búsqueda: {{ $query }}</h1>
    <h6>{{ $totalResultados }} coincidencias</h6>

    @if($totalResultados > 0)
        @if(!$resultados['actividades']->isEmpty())
            <h2>Actividades</h2>
            @foreach($resultados['actividades'] as $actividad)
                <div class="row">
                    <div class="col-sm-12">
                        <a href="{{ route('visualizar_actividades', ['id' => $actividad->id]) }}">
                            <h6>{{ $actividad->titulo }}</h6>
                        </a>
                        <p>{!! $actividad->cuerpo_truncado !!}</p>
                    </div>
                </div>
            @endforeach
        @endif

        @if(!$resultados['martianas']->isEmpty())
            <h2>Martianas</h2>
            @foreach($resultados['martianas'] as $martina)
                <div class="row">
                    <div class="col-sm-12">
                        <a href="{{ route('visualizar_martianas', ['id' => $martina->id]) }}">
                            <h6>{{ $martina->titulo }}</h6>
                        </a>
                        <p>{!! $martina->cuerpo_truncado !!}</p>
                    </div>
                </div>
            @endforeach
        @endif

        @if(!$resultados['convocatorias']->isEmpty())
            <h2>Convocatorias</h2>
            @foreach($resultados['convocatorias'] as $convocatoria)
                <div class="row">
                    <div class="col-sm-12">
                        <a href="{{ route('visualizar_convocatorias', ['id' => $convocatoria->id]) }}">
                            <h6>{{ $convocatoria->titulo }}</h6>
                        </a>
                        <p>{!! $convocatoria->cuerpo_truncado !!}</p>
                    </div>
                </div>
            @endforeach
        @endif
    @else
        <p>No se encontraron resultados.</p>
    @endif
</div>

</body>
@include('componentes.footer')
<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
</html>