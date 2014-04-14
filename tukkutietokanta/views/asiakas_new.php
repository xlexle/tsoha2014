<div class="container">
    <div class="row">
        <h2>Uusi asiakas</h2><br>
        <form class="form-horizontal" action="asiakashallinta.php?asiakas=perusta" method="POST">
            <div class="form-group">
                <label for="yritysnimi" class="col-md-2 control-label">Yrityksen nimi</label>
                <div class="col-md-4">
                    <input type="text" maxlength="50" class="form-control" id="yritysnimi" name="yritysnimi" 
                           value="<?php echo $data->yritysnimi;?>">
                </div>
            </div>
            <div class="form-group">
                <label for="osoite" class="col-md-2 control-label">Osoite</label>
                <div class="col-md-4">
                    <input type="text" maxlength="100" class="form-control" id="osoite" name="osoite" 
                           value="<?php echo $data->osoite;?>">
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-2 control-label">Sähköposti</label>
                <div class="col-md-4">
                    <input type="text" maxlength="50" class="form-control" id="email" name="email" 
                           value="<?php echo $data->email;?>">
                </div>
            </div>
            <div class="form-group">
                <label for="yhteyshenkilo" class="col-md-2 control-label">Yhteyshenkilö</label>
                <div class="col-md-4">
                    <input type="text" maxlength ="50" class="form-control" id="yhteyshenkilo" name="yhteyshenkilo" 
                           value="<?php echo $data->yhteyshenkilo;?>">
                </div>
            </div>
            <div class="form-group">
                <label for="puhelinnumero" class="col-md-2 control-label">Puhelinnumero</label>
                <div class="col-md-2">
                    <input type="text" maxlength ="25" class="form-control" id="puhelinnumero" name="puhelinnumero" 
                           value="<?php echo $data->puhelinnumero;?>">
                </div>
            </div>
            <div class="form-group">
                <label for="luottoraja" class="col-md-2 control-label">Luottoraja (EUR)</label>
                <div class="col-md-2">
                    <input type="text" maxlength ="9" class="form-control" id="luottoraja" name="luottoraja" 
                           value="<?php echo $data->luottoraja;?>">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-plus-sign"></span> Perusta asiakas</button>
                </div>
            </div>  
        </form>
    </div>
    <hr>
    
    <div class="row">
        <form class="form-horizontal" action="asiakashallinta.php">
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Takaisin</button>
                </div>
            </div>
        </form>
    </div>
</div>