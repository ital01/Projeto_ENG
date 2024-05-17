<div class="card o-hidden">
    <div class="card-body">
        <form id="buscaForm" class="mb-2" action="/busca" method="POST">
            @csrf
            <div class="form-group mb-2">
                <label for="buscaByName" class="form-label">Nome</label>
                <input type="text" id="buscaByName" name="buscaByName" class="form-control"
                    placeholder="Pesquisa por Nome">
            </div>
            <div class="form-group mb-2">
                <label for="buscaByContent" class="form-label">Conteudo</label>
                <input type="text" id="buscaByContent" name="buscaByContent" class="form-control"
                    placeholder="Pesquisa por Conteudo">
            </div>
            <div class="form-group">
                <label class="form-label">Tipo de arquivo</label>
                <div class="row">
                    <div class="col-auto">
                        <input class="form-check-input border-dark" type="radio" name="buscaByType"
                            id="buscaByTypeTodos" value="todos" checked>
                        <label class="form-check-label" for="buscaByTypeTodos">Todos</label>
                    </div>
                    <div class="col-auto">
                        <input class="form-check-input border-dark" type="radio" name="buscaByType"
                            id="buscaByTypeHTML" value="html">
                        <label class="form-check-label" for="buscaByTypeHTML">HTML</label>
                    </div>
                    <div class="col-auto">
                        <input class="form-check-input border-dark" type="radio" name="buscaByType"
                            id="buscaByTypePDF" value="pdf">
                        <label class="form-check-label" for="buscaByTypePDF">PDF</label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary my-3"> Enviar </button>
        </form>

        <div class="table-busca">
            <table id="buscaTable" class="table table-striped w-100">
                <thead>
                    <tr>
                        <th scope="col">Nome do PDF</th>
                        <th scope="col" class="text-end">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Linhas da tabela -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('#buscaForm').submit(function(event) {
            event.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                success: function(response) {
                    $('#buscaTable tbody').empty();

                    response.resultado.forEach(function(resultado) {
                        var url = 'http://127.0.0.1:8000/dashboard/doc/' +
                            encodeURIComponent(resultado.url);
                        var button = $('<button/>', {
                            'class': 'btn btn-primary btn-sm abrir-doc',
                            'data-url': url,
                            'text': 'Abrir'
                        });
                        var row = $('<tr/>').append(
                            $('<td/>').text(resultado.name),
                            $('<td/>', {
                                'class': 'text-end'
                            }).append(button)
                        );
                        $('#buscaTable tbody').append(row);
                    });

                    var table = $('#buscaTable').DataTable({
                        "paging": true,
                        "searching": false,
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

                    function filtrarTabela() {
                        var nome = $('#buscaByName').val().toLowerCase();
                        var tipo = $('input[name="buscaByType"]:checked').val();

                        var filtroNome = nome;
                        if (tipo !== 'todos') {
                            filtroNome += '.' + tipo;
                        }
                        table.column(0).search(filtroNome, true, false).draw();
                    }

                    $('input[name="buscaByType"]').click(function() {
                        filtrarTabela();
                    });

                    $('#buscaByName').on('keyup', function() {
                        filtrarTabela();
                    });

                    $('.abrir-doc').on('click', function() {
                        var pdfUrl = $(this).data('url');
                        var viewer = $('#docViewer');

                        viewer.attr('src', pdfUrl).show();
                        $('#loadingSpinner').hide();
                    });

                },

                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
