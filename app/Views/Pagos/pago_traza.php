<input type="hidden" name="txt_id_caja" id="txt_id_caja" value="<?php echo $id_caja; ?>">
<main>
  <div class="container-fluid">
    <div class="table-responsive">
      <table id="grid_pago_traza" class="table table-bordered" width="100%">
        <thead class="thead-dark">
          <tr>
            <th width="30%">Estado</th>
            <th width="30%">Observación</th>
            <th width="20%">Usuario</th>
            <th width="20%">Fecha</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Pagos/pago_traza.js"></script>