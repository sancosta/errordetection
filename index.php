<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta content="IE=edge" http-equiv="X-UA-Compatible">
        <meta content="width=device-width, initial-scale=1" name="viewport">
        
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Redes de Computadores</title>

        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>

    <body>
        
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                </br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <h2><b>Simulador para envio de dados</b></h2>
                        </div>
                    </div>
                </div>
                </br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Dados para enviar</label>
                            <input type="text" class="form-control" id="dados" placeholder="Dados a serem enviados" value="0010010">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                        <label>Escolha o método</label>
                            <select class="form-control" id="metod">
                                <option value="01">01 - PARIDADE SIMPLES</option>
                                <option value="02">02 - PARIDADE DUPLA</option>
                                <option value="03">03 - CYCLIC REDUNDANCY CHECK (CRC)</option>
                                <option value="04">04 - CHECKSUM</option>
                                <option value="05">05 - HAMMING</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" class="btn btn-default btn-block" onclick="$('#emissor').val('');button()">Aplicar Método</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Quadros enviados pelo emissor (para simular erros altere aqui)</label>
                            <input type="text" class="form-control" id="emissor" placeholder="Quadros enviados">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="button" class="btn btn-default btn-block" onclick="button()">Simular Erro</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Quadros recebidos pelo receptor</label>
                            <input type="text" class="form-control" id="receptor" placeholder="Quadros recebidos">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <div class="text-center" id="resultado"></div>
                            <div class="text-left" id="observacao"></div>
                        </div>
                    </div>
                </div>
            </div>    
        </div>

        <script>

            function button() {

                $.ajax({
                    type	: "POST",
                    url		: "/redes.php",
                    data	: "dados="+$("#dados").val()+"&metod="+$("#metod").val()+"&emissor="+$("#emissor").val(),
                    dataType: "json",
                    success	: function(retorno) {
                        
                        if($("#emissor").val() == ""){
                            $("#emissor").val(retorno.DADO_EMITIDO);
                        }
                        $("#receptor").val(retorno.DADO_RECEBIDO);
                        $("#observacao").html(retorno.OBSERVACAO);
                        
                        if(retorno.DADO_IS_VALID){
                            $("#resultado").html("<p class='bg-danger'><b>ERRO</b></p>");
                        } else {
                            $("#resultado").html("<p class='bg-success'><b>SUCESSO</b></p>");
                        }
                        
                    },
                    error	: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("Erro, Desculpe!");
                    }
                });

                return false;

            };

        </script>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>

    </body>
</html>