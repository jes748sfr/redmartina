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

                <article class="blog-post">
                    <div class="pb-5">
                        <div class="d-flex justify-content-center">
                            <h2 class="display-5 link-body-emphasis mb-1">Créditos</h2>
                        </div>
                        <div class="d-flex justify-content-center">
                            <h5>Rector</h5>
                        </div>
                        <div class="d-flex justify-content-center">
                            <p>Dr. Héctor Raúl Solis Gadea</p>
                        </div>
                        <div class="d-flex justify-content-center">
                            <h5>Secretaria Académica</h5>
                        </div>
                        <div class="d-flex justify-content-center">
                            <p>Dra. María Guadalupe Moreno González</p>
                        </div>
                        <div class="d-flex justify-content-center">
                            <h5>Coordinador de Tecnologías para el Aprendizaje</h5>
                        </div>
                        <div class="d-flex justify-content-center">
                            <p>Ing. Héctor Aceves Shimizu y López</p>
                        </div>
                        <div class="d-flex justify-content-center">
                            <h5>Jefe de Unidad de Multimedia Instruccional</h5>
                        </div>
                        <div class="d-flex justify-content-center">
                            <p>Ing. Omar Alberto Andrade Muñoz</p>
                        </div>
                        <div class="d-flex justify-content-center">
                            <h5>Diseño, desarrollo y programación</h5>
                        </div>
                        <div class="d-flex justify-content-center">
                            <p>Lic. Beatriz Idania Gómez Cosio</p>
                        </div>
                        <div class="d-flex justify-content-center">
                            <p>Ing. Eduardo García Salazar</p>
                        </div>
                        <div class="d-flex justify-content-center">
                            <h5>Infraestructura Tecnológica</h5>
                        </div>
                        <div class="d-flex justify-content-center">
                            <p>Lic. Jesús Enrique Vega Coronel</p>
                        </div>
                        <div class="d-flex justify-content-center">
                            <p>Lic. Octavio Cortázar Rodriguez</p>
                        </div>
                        <div class="d-flex justify-content-center">
                            <p>Lic. Eduardo Solano Guzmán</p>
                        </div>
                    </div>
            </div>
        
            <div class="col-md-4">
                <div class="position-sticky" style="top: 5rem;">
              
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
              </div>
          </div>
    </div>    

</body>
@include('componentes.footer')
<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
</html>