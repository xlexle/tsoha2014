<div class="container">
    <div class="row">
        <h2>Uusi tuote</h2><br>
        <form class="form-horizontal" action="tuotevalikoima.php?tuote=perusta" method="POST">
            <div class="form-group">
                <label for="koodi" class="col-md-2 control-label">Valmistajan koodi</label>
                <div class="col-md-4">
                    <input type="text" maxlength="25" class="form-control" id="koodi" name="koodi" placeholder="pakollinen" 
                           value="<?php echo $data->koodi;?>">
                </div>
            </div>
            <div class="form-group">
                <label for="kuvaus" class="col-md-2 control-label">Kuvaus</label>
                <div class="col-md-4">
                    <input type="text" maxlength="50" class="form-control" id="kuvaus" name="kuvaus" 
                           value="<?php echo $data->kuvaus;?>">
                </div>
            </div>
            <div class="form-group">
                <label for="valmistaja" class="col-md-2 control-label">Valmistaja</label>
                <div class="col-md-4">
                    <input type="text" maxlength="25" class="form-control" id="valmistaja" name="valmistaja" placeholder="pakollinen" 
                           value="<?php echo $data->valmistaja;?>">
                </div>
            </div>
            <div class="form-group">
                <label for="hinta" class="col-md-2 control-label">Hinta EUR</label>
                <div class="col-md-2">
                    <input type="text" maxlength="11" class="form-control" id="hinta" name="hinta" placeholder="pakollinen" 
                           value="<?php echo $data->hinta;?>">
                </div>
            </div>
            <div class="form-group">
                <label for="saldo" class="col-md-2 control-label">Varastosaldo</label>
                <div class="col-md-1">
                    <input type="number" min="0" class="form-control" id="saldo" name="saldo" 
                           value="<?php if (!empty($data->saldo)): echo $data->saldo; else: ?>0<?php endif; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="tilauskynnys" class="col-md-2 control-label">Tilauskynnys</label>
                <div class="col-md-1">
                    <input type="number" min="0" class="form-control" id="tilauskynnys" name="tilauskynnys" 
                           value="<?php if (!empty($data->tilauskynnys)): echo $data->tilauskynnys; else: ?>0<?php endif; ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <button type="submit" class="btn btn-default">Perusta tuote</button>
                </div>
            </div>
        </form>
        <form class="form-horizontal" action="tuotevalikoima.php">
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <button type="submit" class="btn btn-default">Takaisin</button>
                </div>
            </div>
        </form>
    </div>
</div>