<div class="container">
    <div class="row">
        <h2>Tuotevalikoima</h2><br>
        <form class="form-horizontal" action="tuotevalikoima.php?haku=listaa" method="POST">
            <div class="form-group">
                <label for="valmistaja" class="col-md-2 control-label">Valmistaja</label>
                <div class="col-md-4">
                    <input type="text" maxlength="25" class="form-control" id="valmistaja" name="valmistaja" value="<?php echo $data->valmistaja; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="hinta_min" class="col-md-2 control-label">Hinta vähintään</label>
                <div class="col-md-2">
                    <input type="text" maxlength="12" class="form-control" id="hinta_min" name="hinta_min" value="<?php echo $data->hinta_min; ?>" placeholder="hinta euroina">
                </div>
            </div>
            <div class="form-group">
                <label for="hinta_max" class="col-md-2 control-label">Hinta enintään</label>
                <div class="col-md-2">
                    <input type="text" maxlength="12" class="form-control" id="hinta_max" name="hinta_max" value="<?php echo $data->hinta_max; ?>" placeholder="hinta euroina">
                </div>
            </div>
            <div class="form-group">
                <label for="saldo_min" class="col-md-2 control-label">Varastossa vähintään</label>
                <div class="col-md-1">
                    <input type="number" min="0" class="form-control" id="saldo_min" name="saldo_min" value="<?php echo $data->saldo_min; ?>" placeholder="kpl">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <button type="submit" class="btn btn-default">Hae tuotteita</button>
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <br>
        <form class="form-horizontal" action="tuotevalikoima.php" method="GET">
            <div class="form-group">
                <label for="tuotenro" class="col-md-2 control-label">Etsi tuotenumerolla</label>
                <div class="col-md-2">
                    <input type="text" maxlength="6" class="form-control" id="tuotenro" name="tuotenro" value="<?php echo $data->tuotenro; ?>" placeholder="6 numeroa">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <button type="submit" class="btn btn-default">Hae tuote</button>
                </div>
            </div>
        </form>  
    </div>

    <?php if (onYllapitaja()): { ?>
            <div class="row">
                <br>
                <p><a class="btn btn-default" href="tuotevalikoima.php?haku=avoimet">Listaa tuotteet joista avoimia tilauksia</a></p>
                <p><a class = "btn btn-default" href = "uusituote.php">Luo uusi tuote</a></p>
            </div>
        <?php } endif; ?>
</div>
