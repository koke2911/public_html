<main>
  <div class="container-fluid">
    <form id="form_exportar" name="form_exportar" encType="multipart/form-data">
      <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
          <div class="form-group">
            <label class="small mb-1" for="dt_fecha_ingreso_im">Fecha Ingreso</label>
            <input type='text' class="form-control" id='dt_fecha_ingreso_im' name="dt_fecha_ingreso_im"/>
          </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
          <div class="form-group">
            <label class="small mb-1" for="dt_fecha_vencimiento_im">Fecha Vencimiento</label>
            <input type='text' class="form-control" id='dt_fecha_vencimiento_im' name="dt_fecha_vencimiento_im"/>
          </div>
        </div>
      </div>
    </form>

    <div class="table-responsive">
      <form enctype="multipart/form-data">
        <div class="form-group">
          <div class="file-loading">
            <input id="archivos" name="archivos" type="file" multiple=true class="file-loading">
          </div>
        </div>
      </form>
    </div>
  </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Consumo/importar_planilla.js"></script>