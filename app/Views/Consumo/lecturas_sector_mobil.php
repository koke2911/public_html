<main>
  <div class="container-fluid px-2 px-md-4">
    <h4 class="mt-3 mb-3 text-center font-weight-bold">
      <i class="fas fa-book-reader text-primary mr-2"></i>Lecturas por Sector
    </h4>

    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-6">
        <div class="alert alerta-fijo hidden mb-3 text-center shadow-sm" role="alert" id="alerta"></div>
      </div>
    </div>
    <!-- Buscador / Filtros -->
    <div class="card shadow-sm mb-3 border-0 rounded-lg">
      <div class="card-header bg-white font-weight-bold d-flex justify-content-between align-items-center cursor-pointer"
        data-toggle="collapse" data-target="#informeInventario" aria-expanded="true">
        <span><i class="fas fa-search text-muted mr-2"></i>Filtros de Búsqueda</span>
        <i class="fas fa-chevron-down text-muted"></i>
      </div>
      <div class="card-body collapse show" id="informeInventario">
        <form id="form_inf_inventario" name="form_inf_inventario">
          <div class="form-row">
            <div class="col-12 col-md-4 mb-2 mb-md-0">
              <label class="small font-weight-bold mb-1" for="cmb_sectores">Sector</label>
              <select id="cmb_sectores" name="cmb_sectores" class="form-control form-control-alternative"></select>
            </div>
            <div class="col-12 col-md-4 mb-2 mb-md-0">
              <label class="small font-weight-bold mb-1" for="dt_mes_consumo">Mes de Consumo</label>
              <input type='text' class="form-control form-control-alternative" id='dt_mes_consumo' name="dt_mes_consumo" placeholder="MM-YYYY" disabled />
            </div>
            <div class="col-12 col-md-4">
              <label class="small font-weight-bold mb-1" for="dt_fecha_vencimiento">Fecha Vencimiento</label>
              <input type='text' class="form-control form-control-alternative" id='dt_fecha_vencimiento' name="dt_fecha_vencimiento" placeholder="DD-MM-YYYY" disabled />
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Contenedor Escritorio (Tabla) -->
    <div class="card shadow-sm border-0 rounded-lg d-none d-md-block">
      <div class="card-body p-3">
        <div class="table-responsive">
          <table id="grid_lecturas_sector" class="table table-hover align-items-center table-flush w-100">
            <thead class="thead-light">
              <tr>
                <th>Id. Socio</th>
                <th>Id. Metros</th>
                <th width="10%">Ruta</th>
                <th width="10%">ROL</th>
                <th width="25%">Socio</th>
                <th width="15%">N° Medidor</th>
                <th width="15%">L. Anterior</th>
                <th width="15%">L. Actual</th>
                <th width="10%">Acción</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>

    <!-- Contenedor Móvil (Tarjetas Dinámicas) -->
    <div class="d-block d-md-none" id="cards_container">
      <div class="text-center text-muted my-4" id="cards_empty_state">
        <i class="fas fa-info-circle fa-2x mb-2"></i>
        <p>Seleccione un sector y mes de consumo para cargar las lecturas.</p>
      </div>
    </div>

  </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Consumo/lecturas_sector_mobile.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Consumo/lecturas_sector_mobile.js"></script>