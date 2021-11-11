<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Busca de marcas</div>

          <div class="card-body">
            <div class="form-row">
              <div class="col mb-3">
                <label for="inputId" class="form-label">ID</label>
                <input
                  type="number"
                  id="inputId"
                  class="form-control"
                  area-describedby="idHelp"
                  placeholder="ID"
                />
                <div id="idHelp" class="form-text text-muted">
                  Opcional. Informe o Id do registro
                </div>
              </div>

              <div class="col mb-3">
                <label for="inputNome" class="form-label">Nome</label>
                <input
                  type="text"
                  id="inputNome"
                  class="form-control"
                  area-describedby="idHelp"
                  placeholder="Nome"
                />
                <div id="idHelp" class="form-text text-muted">
                  Opcional. Informe o nome da marca
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-sm float-right">
              Pesquisar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
</script>


