<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center"><i class="fas fa-receipt"></i> Registro de ventas</h3>
    <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
    <div class="container-fluid">
      <br>
      <div class="card shadow mb-12">
        <div class="card-body">
          <div class="container-fluid">
            <center>
              <button type="button" name="btn_emitir" id="btn_emitir" class="btn btn-success"><i class="fas fa-receipt"></i> Emitir DTE</button>
              <!-- <button type="button" name="btn_imprimir" id="btn_imprimir" class="btn btn-primary"><i class="fas fa-print"></i> Imprimir DTE</button>
               <button type="button" name="btn_aviso_cobranza" id="btn_aviso_cobranza" class="btn btn-info"><i class="fas fa-print"></i> Imprimir Aviso de Cobranza</button>-->
            </center>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="dlg_buscar_socio" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form id="form">
          <div class="modal-header">
            <h4 class="modal-title">Emitir DTE</h4>
          </div>
          <div class="modal-body">
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
              <div class="form-group">
                <label for="certificate">Tipo de documento</label>
                <select name="documents_type_id" id="documents_type_id" required class="form-control">
                  <option value="33">Factura electrónica</option>
                  <option value="34">Factura exenta electrónica</option>
                  <option value="39">Boleta electrónica</option>
                  <option value="41" selected>Boleta exenta electrónica</option>
                </select>
              </div>
              <div class="form-group">
                <label for="user_id">RUT Socio</label>
                <input name="user_id" id="user_id" class="form-control">
              </div>
            </div>
            <table class="table table-bordered" id="details">
              <thead class="thead-dark">
                <tr>
                  <th>Cantidad</th>
                  <th>Descripción</th>
                  <th>Precio</th>
                  <th>Tipo</th>
                  <th></th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
            <button type="button" name="btn_add" id="btn_add" class="btn btn-success"><i class="fas fa-plus"></i></button>
            <div class="form-group">
              <label for="user_id">Comentarios</label>
              <textarea name="comments" id="comments" class="form-control"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <input type="submit" value="Crear" class="btn btn-success">
            <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Finanzas/registro_ventas.js"></script>