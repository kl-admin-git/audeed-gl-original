@extends('template.baseVertical')

@section('css')
<link rel="stylesheet" href="{{ assets_version('/vertical/assets/css/listachequeo/informe_planilla_titulacion/main.css') }}">
@endsection

@section('breadcrumb')
    <h3 class="page-title">Planilla titulación</h1>
@endsection
@section('section')

<div class="row">
    <div class="col-12">
        <div class="card m-b-20">
            <div class="card-body">
                <div class="col-lg-12 m-b-30 contenedorTablaPerfiles">
                    <div class="col-lg-12">
                        <div class="row m-b-10">
                            <div class="col-lg-12">
                                <div class="d-flex justify-content-end align-items-center contenedorBuscador">
                                    <!-- <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" id="buscar-tour">Buscar  <i class="fa" aria-hidden="true"></i></button>  -->
                                    <i class="mdi mdi-file-excel" style="font-size: 2.5rem;color: #4FB648; cursor: pointer;" id="btn-descargar-excel" title="Descargar Excel"></i>
                                    <form action="{{url('/listachequeo/informe_planilla_titulacion/descargar-excel')}}" method="POST" style="display: none;" id="descargar-excel-planAccion">
                                        @csrf
                                        <input type="hidden"  name="filtros_busqueda" id="filtros_busqueda"/>
                                    </form>
                                </div>
                                <div class="col-lg-12 m-t-10">
                                    <div class="collapse" id="collapseExample">
                                        
                                            <div class="card card-body">
                                                <div class="row">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <div>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" value="" placeholder="fecha de realización" id="datepicker-autoclose">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="mdi mdi-calendar" style="color: green"></i></span>
                                                                    </div>
                                                                </div><!-- input-group -->
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <select class="form-control select2 selectSearch listaSearch">
                                                                <option value="">Buscar por lista de chequeo</option>
                                                            </select>
                                                        </div>
                                                    </div>
                
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <select class="form-control select2 selectSearch evaluadoSearch">
                                                                <option value="">Buscar por el evaluado</option>
                                                            </select>
                                                        </div>
                                                    </div>
            
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <select class="form-control select2 selectSearch evaluadorSearch">
                                                                <option value="">Buscar por el evaluador</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <select class="form-control select2 selectSearch empresaSearch">
                                                                <option value="">Buscar por la empresa</option>
                                                            </select>
                                                        </div>
                                                    </div>
            
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <button type="button" class="btn btn-primary waves-effect waves-light buscarBoton"><i class="fa fa-search"></i> Buscar</button>
                                                            <button type="button" class="btn btn-primary waves-effect waves-light restablecerBoton"><i class="mdi mdi-autorenew"></i> Restablecer</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="tablaPlanillaTitulacion" class="table table-bordered m-b-0">
                            <thead>
                                {{-- Fila 1: Título principal (Lista de Chequeo - Azul) --}}
                                <tr>
                                    <th colspan="{{ 2 + count($datosPlanilla['categorias']) + 8 }}" class="text-center text-white" style="vertical-align: middle; background-color: {{ $datosPlanilla['lista_chequeo']['color'] }}; font-weight: bold; font-size: 14px;">
                                        {{ $datosPlanilla['lista_chequeo']['nombre'] }}
                                    </th>
                                </tr>
                                
                                {{-- Fila 2: Categorías (Amarillo) --}}
                                <tr>
                                    <th colspan="2" rowspan="3" class="text-center" style="vertical-align: middle;"></th>
                                    @foreach($datosPlanilla['categorias'] as $categoria)
                                        <th colspan="{{ $categoria['colspan'] }}" class="text-center" style="vertical-align: middle; background-color: {{ $categoria['color'] }}; font-weight: bold;">
                                            {{ $categoria['nombre'] }}
                                        </th>
                                    @endforeach
                                    <th class="text-center" style="vertical-align: middle; background-color: #92D050; font-weight: bold;">Responsable</th>
                                    <th class="text-center" style="vertical-align: middle; background-color: #92D050; font-weight: bold;">Responsable</th>
                                </tr>
                                
                                {{-- Fila 3: Etiquetas (Gris) --}}
                                <tr>
                                    @foreach($datosPlanilla['categorias'] as $categoria)
                                        @foreach($categoria['etiquetas'] as $etiqueta)
                                            <th colspan="{{ $etiqueta['colspan'] }}" class="text-center" style="vertical-align: middle; background-color: {{ $etiqueta['color'] }}; color: #fff; font-weight: bold; font-size: 11px;">
                                                {{ $etiqueta['nombre'] }}
                                            </th>
                                        @endforeach
                                    @endforeach
                                    <th rowspan="3" class="text-center" style="vertical-align: middle; font-weight: bold;">Firma Operario</th>
                                    <th rowspan="3" class="text-center" style="vertical-align: middle; background-color: #92D050; font-weight: bold;">Firma Líder U.V.A</th>
                                </tr>
                                
                                {{-- Fila 4: Información adicional de etiquetas (Gris) --}}
                                <tr>
                                    @foreach($datosPlanilla['categorias'] as $categoria)
                                        @foreach($categoria['etiquetas'] as $etiqueta)
                                            <th colspan="{{ $etiqueta['colspan'] }}" class="text-center" style="vertical-align: middle; background-color: {{ $etiqueta['color'] }}; color: #fff; font-weight: bold;">
                                                {{ $etiqueta['info_adicional'] }}
                                            </th>
                                        @endforeach
                                    @endforeach
                                </tr>
                                
                                {{-- Fila 5: Preguntas (Naranja) --}}
                                <tr>
                                    <th class="text-center" style="vertical-align: middle; background-color: #fc9425; font-weight: bold; font-size: 11px;">Fecha</th>
                                    <th class="text-center" style="vertical-align: middle; background-color: #fc9425; font-weight: bold; font-size: 11px;">Hora de titulación</th>
                                    @foreach($datosPlanilla['categorias'] as $categoria)
                                        @foreach($categoria['etiquetas'] as $etiqueta)
                                            @foreach($etiqueta['preguntas'] as $pregunta)
                                                <th class="text-center" style="vertical-align: middle; background-color: {{ $pregunta['color'] }}; font-weight: bold; font-size: 11px;">
                                                    {{ $pregunta['nombre'] }}
                                                </th>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody id="tbody-planilla">
                                @if(count($datosPlanilla['registros']) > 0)
                                    @foreach($datosPlanilla['registros'] as $registro)
                                        <tr>
                                            <td class="text-center" style="vertical-align: middle;">{{ $registro['fecha'] }}</td>
                                            <td class="text-center" style="vertical-align: middle;">{{ $registro['hora'] }}</td>
                                            @foreach($datosPlanilla['categorias'] as $categoria)
                                                @foreach($categoria['etiquetas'] as $etiqueta)
                                                    @foreach($etiqueta['preguntas'] as $pregunta)
                                                        <td class="text-center" style="vertical-align: middle;">
                                                            {{ $registro['respuestas'][$pregunta['id']] ?? '' }}
                                                        </td>
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                            <td class="text-center" style="vertical-align: middle;">{{ $registro['firma_operario'] }}</td>
                                            <td class="text-center" style="vertical-align: middle;">{{ $registro['firma_lider'] }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr id="no-data-row">
                                        <td colspan="{{ 2 + count($datosPlanilla['categorias']) + 2 }}" class="text-center" style="padding: 30px; vertical-align: middle;">
                                            <p style="margin-top: 10px; font-size: 16px; color: #6c757d;">No hay datos disponibles actualmente</p>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

               
                <div class="contenedorPaginacion">
                    <nav class="pagination">
                        <div class="nav-btn prev"></div>
                        <ul class="nav-pages"></ul>
                        <div class="nav-btn next"></div>
                    </nav>
                </div>
                

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection

@section('script')

<script type="text/javascript" src="{{ assets_version('/vertical/assets/js/informe_planilla_titulacion/main.js') }}"></script>
@endsection