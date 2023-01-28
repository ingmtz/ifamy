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
        .card{
            background-color: #19BC9D;
            border-color: #19BC9D;
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
                            <div class="row buttons-container">
                                <button type="button" class="btn btn-success" tabindex="5" onclick="exportRecords()">Descargar a excel</button>
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
    

    function exportRecords(){
        window.open("<?php echo base_url(); ?>CaptureProviders/export", "_blank");
    }

    


</script>
</html>