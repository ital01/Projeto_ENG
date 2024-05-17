<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesquisa de Documentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.2/css/dataTables.bootstrap5.min.css">
</head>

<body class="bg-dark">

    @include('layouts.navigation')

    <section class="row container-fluid">

        <div class="col-md-6" id="filtro">
            @include('layouts.filtro')
        </div>

        <div class="col-md-6" id="busca">
            @include('layouts.busca')
        </div>

        <div class="col-md-6 mx-auto" id="iframe">
            @include('layouts.documento')
        </div>

    </section>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#busca').hide();
            $('#filtro').hide();

            $('#toggleFiltro').click(function() {
                $('#filtro').toggle();

                if ($('#filtro').is(':visible')) {
                    $('#iframe').removeClass('w-100').addClass('w-50');
                } else {
                    if ($('#searchByTypeHTML').prop('checked')) {
                        $('#iframe').removeClass('w-100').addClass('w-50');
                    } else {
                        $('#iframe').removeClass('w-50').addClass('w-100');
                    }
                }
            });

            $('#toggleBusca').click(function() {
                $('#busca').toggle();
            });

            var table = $('#docTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": false,
                "info": false,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/2.0.2/i18n/pt-BR.json"
                },
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Todos"]
                ]
            });

            table.on('init.dt', function() {
                var remover = document.querySelector('.dt-search');
                if (remover) {
                    remover.parentNode.removeChild(remover);
                } else {
                    console.log("O elemento não foi encontrado.");
                }

                function filtrarTabela() {
                    var nome = $('#searchByName').val().toLowerCase();
                    var tipo = $('input[name="searchByType"]:checked').val();

                    var filtroNome = nome;
                    if (tipo !== 'todos') {
                        filtroNome += '.' + tipo;
                    }
                    table.column(0).search(filtroNome, true, false).draw();
                }

                $('input[name="searchByType"]').click(function() {
                    filtrarTabela();
                });

                $('#searchByName').on('keyup', function() {
                    var nome = $(this).val().toLowerCase();
                    table.columns(0).search(nome).draw();
                });

                $('#searchForm').submit(function(event) {
                    event.preventDefault();
                    filtrarTabela();
                });
            });

            $('.abrir-doc').on('click', function() {
                var pdfUrl = $(this).data('url');
                var viewer = $('#docViewer');

                viewer.attr('src', pdfUrl).show();
                $('#loadingSpinner').hide();
            });

            $('#loadingSpinner').hide();

            $('#fecharDocumento').click(function() {
                $('#docViewer').hide();
            });
        });
    </script>
</body>

</html>