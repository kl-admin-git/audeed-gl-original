let paginacion = 1;
let totalpaginas = 0;
let inicializacionPaginacion = false;
let arrayFiltros = {};

$(document).ready(function() {
    // Select2
    $(".select2").select2({});

    // Date Picker
    $('#datepicker-autoclose').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd',
        language: 'es'
    });

    $('#btn-descargar-excel').on('click', function() {
        $('#descargar-excel-planAccion').submit();
    });
    IniciarVista();
});

function IniciarVista() {
    arrayFiltros['filtro_realizacion'] = $('#datepicker-autoclose').val();
    arrayFiltros['filtro_lista_chequeo'] = $('.listaSearch').val();
    arrayFiltros['filtro_evaluado'] = $('.evaluadoSearch').val();
    arrayFiltros['filtro_evaluador'] = $('.evaluadorSearch').val();
    arrayFiltros['filtro_empresa'] = $('.empresaSearch').val();

    $.ajax({
        type: 'POST',
        url: '/listachequeo/informe_planilla_titulacion/GetDataInit',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            paginacion: paginacion,
            arrayFiltros: JSON.stringify(arrayFiltros)
        },
        cache: false,
        dataType: 'json',
        beforeSend: function() {
            CargandoMostrar();
        },
        success: function(data) {
            CargandoNoMostrar();
            switch (data.responseCode) {
                case 202:
                    totalpaginas = data.totalPaginas || 1;
                    
                    if (!inicializacionPaginacion && totalpaginas > 0) {
                        InicializacionPaginacion($('.pagination'), totalpaginas, paginacion);
                    }
                    
                    if (totalpaginas > 0) {
                        _navPage($('.pagination'), totalpaginas, paginacion - 1, 5);
                    }

                    // Actualizar TABLA
                    let datos = data.data;
                    let registros = datos.registros;
                    let categorias = datos.categorias;
                    let tbody = $('#tbody-planilla');
                    tbody.empty();

                    if (registros.length === 0) {
                        tbody.html('<tr id="no-data-row"><td colspan="100%" class="text-center p-3">No hay datos disponibles actualmente</td></tr>');
                    } else {
                        registros.forEach(function(registro) {
                            let tr = $('<tr>');
                            tr.append($('<td class="text-center" style="vertical-align: middle;">').text(registro.fecha));
                            tr.append($('<td class="text-center" style="vertical-align: middle;">').text(registro.hora));

                            // Iterar categorías -> etiquetas -> preguntas para mantener orden de columnas
                            categorias.forEach(function(cat) {
                                cat.etiquetas.forEach(function(etiq) {
                                    etiq.preguntas.forEach(function(preg) {
                                        let respuesta = registro.respuestas[preg.id] || '';
                                        tr.append($('<td class="text-center" style="vertical-align: middle;">').text(respuesta));
                                    });
                                });
                            });

                            tr.append($('<td class="text-center" style="vertical-align: middle;">').text(registro.firma_operario));
                            tr.append($('<td class="text-center" style="vertical-align: middle;">').text(registro.firma_lider));
                            
                            tbody.append(tr);
                        });
                    }
                    break;

                case 402:
                    toastr.error(data.message);
                    break;

                default:
                    break;
            }
        },
        error: function(data) {
            CargandoNoMostrar();
            console.error('Error al cargar los datos');
        }
    });
}

function OnClickBuscarBoton() {
    paginacion = 1;
    inicializacionPaginacion = false;
    $('.pagination').html(`<div class="nav-btn prev"></div>
                          <ul class="nav-pages"></ul>
                          <div class="nav-btn next"></div>`);
    pageNum = 0;
    pageOffset = 0;
    IniciarVista();
}

function OnClickRestablecerBusqueda() {
    let url = window.location.origin + '/listachequeo/informe_planilla_titulacion';
    window.location.href = url;
}

$('.buscarBoton').on('click', OnClickBuscarBoton);
$('.restablecerBoton').on('click', OnClickRestablecerBusqueda);

// PAGINACION
var pageNum = 0, pageOffset = 0;

function InicializacionPaginacion(baseElement, pages, pageShow) {
    $(baseElement).unbind("click");
    _initNav(baseElement, pageShow, pages);
    inicializacionPaginacion = true;
}

function _initNav(baseElement, pageShow, pages) {
    // Limpiar páginas existentes
    $('.nav-pages', baseElement).empty();
    
    // Crear páginas
    for (i = 1; i < pages + 1; i++) {
        $((i == 1 ? '<li class="active">' : '<li>') + (i) + '</li>').appendTo('.nav-pages', baseElement).css('min-width', '4em');
    }

    // Calcular valores iniciales
    function ow(e) { return e.first().outerWidth() }
    var w = ow($('.nav-pages li', baseElement)), bw = ow($('.nav-btn', baseElement));
    baseElement.css('width', w * (pages <= 5 ? pages : pageShow) + (bw * (pages <= 5 ? 2 : 4)) + 'px');
    $('.nav-pages', baseElement).css('margin-left', bw + 'px');

    // Inicializar eventos
    baseElement.on('click', '.nav-pages li, .nav-btn', function(e) {
        if ($(e.target).is('.nav-btn')) {
            var toPage;
            if ($(this).hasClass('prev')) {
                toPage = pageNum - 1;
                if (toPage >= 0) {
                    paginacion = toPage + 1;
                    IniciarVista();
                }
            } else {
                toPage = pageNum + 1;
                if (toPage < totalpaginas) {
                    paginacion = toPage + 1;
                    IniciarVista();
                }
            }
        } else {
            var toPage = $(this).index();
            paginacion = (toPage + 1);
            IniciarVista();
        }
        _navPage(baseElement, pages, toPage, pageShow);
    });
}

function _navPage(baseElement, pages, toPage, pageShow) {
    var sel = $('.nav-pages li', baseElement), w = sel.first().outerWidth(),
        diff = toPage - pageNum;

    if (toPage >= 0 && toPage <= pages - 1) {
        sel.removeClass('active').eq(toPage).addClass('active');
        pageNum = toPage;
    } else {
        return false;
    }

    if (toPage <= (pages - (pageShow + (diff > 0 ? 0 : 1))) && toPage >= 0) {
        pageOffset = pageOffset + -w * diff;
    } else {
        pageOffset = (toPage > 0) ? -w * (pages - pageShow) : 0;
    }

    sel.parent().css('left', pageOffset + 'px');
}
// PAGINACION - FIN
