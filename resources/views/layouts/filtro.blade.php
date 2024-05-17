<div class="card o-hidden">
    <div class="card-body">
        <form id="searchForm" class="mb-2">
            <div class="form-group mb-2">
                <label for="searchByName" class="form-label">Nome</label>
                <input type="text" id="searchByName" name="searchByName" class="form-control" placeholder="Pesquisa por Nome">
            </div>
            <div class="form-group">
                <label class="form-label">Tipo de arquivo</label>
                <div class="row">
                    <div class="col-auto">
                        <input class="form-check-input border-dark" type="radio" name="searchByType" id="searchByTypeTodos" value="todos" checked>
                        <label class="form-check-label" for="searchByTypeTodos">Todos</label>
                    </div>
                    <div class="col-auto">
                        <input class="form-check-input border-dark" type="radio" name="searchByType" id="searchByTypeHTML" value="html">
                        <label class="form-check-label" for="searchByTypeHTML">HTML</label>
                    </div>
                    <div class="col-auto">
                        <input class="form-check-input border-dark" type="radio" name="searchByType" id="searchByTypePDF" value="pdf">
                        <label class="form-check-label" for="searchByTypePDF">PDF</label>
                    </div>
                </div>
            </div>
            <!-- Adicione mais campos de filtro conforme necessário -->
        </form>

        @if (!empty($docs))
            <div class="table-responsive">
                <table id="docTable" class="table table-striped w-100">
                    <thead>
                        <tr>
                            <th scope="col">Nome do PDF</th>
                            {{-- <th scope="col">Tamanho</th> --}}
                            <th scope="col" class="text-end">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($docs as $doc)
                            <tr>
                                <td>{{ $doc->name }}</td>
                                {{-- <td>{{ $doc->size }}</td> --}}
                                <td class="text-end">
                                    <button class="btn btn-primary btn-sm abrir-doc"
                                        data-url="{{ url('/dashboard/doc/' . rawurlencode($doc->url)) }}"
                                        data-extension="{{ $doc->extension }}">
                                        Abrir
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>Nenhum arquivo PDF encontrado.</p>
        @endif
    </div>
</div>