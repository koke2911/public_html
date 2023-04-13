<main>
  <div class="container-fluid">
    <h3 class="mt-4" align="center">Carga de lecturas por planilla</h3>
    <!-- <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div> -->

    <div class="container-fluid">
      <br>
      <div class="card shadow mb-12">
        <div class="card-body">
          <div class="container-fluid">
            <center>
             <button type="button" name="btn_subir_planilla" id="btn_subir_planilla" class="btn btn-info"><i class="fas fa-file-certificate"></i> Subir Lecturas</button><BR>
             
             <A HREF="https://docs.google.com/document/d/1snNPX3wPbmukrqVsTVUwbFdPnDkYB0FJRAX62x4wASU/edit?usp=sharing" target="_blank"> Ver Documentacion </A>
            </center><BR>
            <center>
             <button type="button" name="btn_exporta_planilla" id="btn_exporta_planilla" class="btn btn-info"><i class="fas fa-file-certificate"></i> Reporte Toma de lectura</button><BR>
             
             <!-- <A HREF="https://docs.google.com/document/d/1snNPX3wPbmukrqVsTVUwbFdPnDkYB0FJRAX62x4wASU/edit?usp=sharing" target="_blank"> Ver Documentacion </A> -->
            </center>
          </div>
        </div>
      </div>
      <br>
          <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
              <div id="dlg_importar_certificado" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-xl">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Importar Datos</h4>
                        </div>
                        <div class="modal-body">
                          <div id="divContenedorImportar2"></div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
                        </div>
                      </div>
                    </div>
              </div>
            </div>
      </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Consumo/lectura_planilla.js"></script>