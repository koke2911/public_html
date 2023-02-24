<main>
  <input type="hidden" name="tipo" id="tipo" value="<?php echo $tipo; ?>">
  <div class="alert alerta-fijo hidden" role="alert" id="alerta"></div>
  <div class="container-fluid">
   <form enctype="multipart/form-data" id="form">
      <div class="form-group">
        <div class="form-group">
          <label for="socios">Importar Archivo</label>
          <input id="socios" name="socios" type="file" class="form-control" accept=".xlsx">
        </div>
      </div>
      
      <input type="submit" value="Subir" class="btn btn-success">
    </form>
  </div>
</main>
<script type="text/javascript" src="<?php echo base_url(); ?>/js/Configuracion/apr_carga_masiva.js"></script>