<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proveedores</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <!-- Bootstrap theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/chosen/chosen.css" />
    <style>
        html, body, .container-fluid { height: 100%; }
        .buttons-container{
            justify-content: space-evenly;
        }

        .form-container{
           margin: auto;
        }
        .container-fluid{
            display: flex;
            align-items: center;
            justify-content: center;background-color: #19BC9D;
        }
        .form-container{
            width: 50%;
        }

        .buttons-container{
            margin-top: 30px;
        }

        .buttons-container > button{
            margin-bottom: 10px;
        }

        @media only screen and (max-width: 1382px) {
            .form-container{
                width: 60%;
            }
        }

        @media only screen and (max-width: 992px) {
            .form-container{
                width: 80%;
            }
        }

        @media only screen and (max-width: 768px) {
            .form-container{
                width: 90%;
            }
        }

        @media only screen and (max-width: 600px) {
            .form-container{
                width: 100%;
            }
        }

        
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="card-body form-parent">
            <div class="form-container">
                <div class="card">
                    <div class="card-body">
                        <form id="formProvider">
                            <div class="form-group">
                                <label for="provider">Proveedor:</label>
                                <select id="idprovider" name="idprovider" class="form-control chosen-select">
                                    <option value="0"><?php echo $this->lang->line('Select'); ?></option>
                                    <?php foreach ($providers as $key => $value) { ?>
                                        <option value="<?php echo $value->Id; ?>"><?php echo $value->provider; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="provider">Código AP</label>
                                <input type="text" class="form-control" id="apCode" name="apCode" placeholder="Ingrese el código AP" tabindex="2">
                            </div>
                            <div class="form-group">
                                <label for="provider">Código Proveedor</label>
                                <input type="text" class="form-control" id="providerCode" name="providerCode" placeholder="Ingrese el código del proveedor" tabindex="3">
                            </div>
                            <div class="row buttons-container">
                                <button type="button" class="btn btn-success" tabindex="4" onclick="saveRecord()">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script lang="javascript" src="https://cdn.sheetjs.com/xlsx-0.19.2/package/dist/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script src="<?php echo base_url(); ?>assets/chosen/chosen.jquery.js"></script>



<script>
    $('.chosen-select').chosen();
    $('.chosen-single').height(36);
    $('.chosen-container').width('100%');
    $('.chosen-single span').css('margin-top', '5px');

    function saveRecord(){
        if($('#idprovider').val() != "0" && $('#apCode').val() != "" && $('#providerCode').val() != "" ){
            $.post("<?php echo base_url(); ?>CaptureProviders/save",{data: $('#formProvider').serialize()},function(result){
                alertify.message("Datos guardados correctamente");

                $('#formProvider')[0].reset();
                $('#idprovider').val("0");
                $('#idprovider').trigger("chosen:updated");
            }).fail(function(){
                alertify.error("Ha ocurrido un error");
            });
            
        }else{
            alertify.error("Llene todos los campos para guardar");
        }
    }

    
    $(document).keydown(function (event) {
        if (event.keyCode === 13 && event.target.nodeName === 'INPUT') {
            var form = event.target.form;
            var index = Array.prototype.indexOf.call(form, event.target);
            form.elements[index + 1].focus();
            event.preventDefault();
        }
    });

    


</script>
</html>