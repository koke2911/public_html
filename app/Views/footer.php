<!-- ESTILOS INTEGRADOS DEL FOOTER -->
<style>
  .app-footer {
    background: var(--card-bg, #FFFFFF);
    border-top: 1px solid var(--glass-border, rgba(255, 90, 31, 0.22));
    font-size: 12.5px;
    color: var(--text-secondary, #8A7362);
    margin-top: auto;
  }

  .footer-link {
    color: var(--text-secondary, #8A7362);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s ease;
  }

  .footer-link:hover {
    color: var(--aqua-bright, #FF5A1F);
    text-decoration: underline;
  }

  .dot-separator {
    color: var(--text-muted, #B7A290);
    user-select: none;
  }
</style>

<!-- MODAL ACTUALIZAR CLAVE -->
<div id="dlg_actualizar_clave" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Actualizar Clave</h4>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <form id="form_actualizar_clave" name="form_actualizar_clave" encType="multipart/form-data">
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="form-group">
                  <label class="small mb-1" for="txt_clave_actual">Clave Actual</label>
                  <input type='password' class="form-control" id='txt_clave_actual' name="txt_clave_actual" />
                </div>
              </div>
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="form-group">
                  <label class="small mb-1" for="txt_clave_nueva">Clave Nueva</label>
                  <input type='password' class="form-control" id='txt_clave_nueva' name="txt_clave_nueva" />
                </div>
              </div>
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="form-group">
                  <label class="small mb-1" for="txt_repetir">Repetir Clave Nueva</label>
                  <input type='password' class="form-control" id='txt_repetir' name="txt_repetir" />
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_actualizar" name="btn_actualizar">Actualizar</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- NUEVO FOOTER -->
<footer class="app-footer">
  <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between py-3 px-4">
    <div class="footer-copyright mb-2 mb-md-0">
      <span class="text-secondary">Copyright &copy; Info Integral y Corretaje. LTDA 2021 - <?php echo date('Y'); ?></span>
    </div>
    <div class="footer-links d-flex align-items-center gap-3">
      <a href="https://softwareapr.cl/info" target="_blank" rel="noopener noreferrer" class="footer-link">Información</a>
      <span class="dot-separator">&middot;</span>
      <a href="https://medidorinteligente.cl" target="_blank" rel="noopener noreferrer" class="footer-link">Medidor inteligente</a>
      <span class="dot-separator">&middot;</span>
      <a href="https://puntoblue.cl/" target="_blank" rel="noopener noreferrer" class="footer-link">Punto blue</a>
    </div>
  </div>
</footer>

<!-- SCRIPTS DEL SISTEMA -->
<script src="<?php echo base_url(); ?>/js/jquery-3.5.1.slim.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/js/bootstrap.bundle.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/js/scripts.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/js/dataTables.select.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/js/buttons.bootstrap4.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/js/jszip.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/js/pdfmake.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/js/vfs_fonts.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/js/buttons.html5.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/js/buttons.print.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/js/buttons.colVis.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/js/sum().js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/assets/demo/datatables-demo.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/js/moment-with-locales.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/js/fnReloadAjax.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/context-menu/context-menu.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/jquery-validation-1.19.2/dist/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/sweetalert2/sweetalert2.all.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/Multiple-Select/dist/js/bootstrap-multiselect.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/autocomplete/bootstrap-autocomplete.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/js/alerta.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/js/menu.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/fileinput-bootstrap/js/plugins/piexif.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/fileinput-bootstrap/js/plugins/sortable.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/fileinput-bootstrap/js/fileinput.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/fileinput-bootstrap/js/locales/fr.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/fileinput-bootstrap/js/locales/es.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/fileinput-bootstrap/themes/fas/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/fileinput-bootstrap/themes/explorer-fas/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/loader-screen-bar/js/JQLoader.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/js/chart.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/js/JsBarcode.all.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>/js/printThis.js" type="text/javascript"></script>
</body>

</html>