<div class="container">
    <div class="row">
        <?php $tuote = $data->tuote ?>
        <h2>Tuote <?php echo $tuote->getTuotenro(); ?></h2><br>
        <form class="form-horizontal" action="tuotevalikoima.php?tallenna=<?php $tuote->getTuotenro(); ?>" method="POST">
            <div class="form-group">
                <label for="koodi" class="col-md-2 control-label">Valmistajan koodi</label>
                <div class="col-md-4">
                    <input type="text" maxlength="25" class="form-control" id="koodi" name="koodi" value="<?php echo $tuote->getKoodi(); ?>" 
                           <?php if (!$data->muokkaa): { ?> readonly<?php } endif; ?>>
                </div>
            </div>
            <div class="form-group">
                <label for="kuvaus" class="col-md-2 control-label">Kuvaus</label>
                <div class="col-md-4">
                    <input type="text" maxlength="50" class="form-control" id="kuvaus" name="kuvaus" value="<?php echo $tuote->getKuvaus(); ?>" 
                           <?php if (!$data->muokkaa): { ?> readonly<?php } endif; ?>>
                </div>
            </div>
            <div class="form-group">
                <label for="valmistaja" class="col-md-2 control-label">Valmistaja</label>
                <div class="col-md-4">
                    <input type="text" maxlength="25" class="form-control" id="valmistaja" name="valmistaja" value="<?php echo $tuote->getValmistaja(); ?>" readonly
                           <?php if (!$data->muokkaa): { ?> readonly<?php } endif; ?>>
                </div>
            </div>
            <div class="form-group">
                <label for="hinta" class="col-md-2 control-label">Hinta EUR</label>
                <div class="col-md-2">
                    <input type="text" class="form-control" id="hinta" name="hinta" value="<?php echo $tuote->getHinta(); ?>"
                           <?php if (!$data->muokkaa): { ?> readonly<?php } endif; ?>>
                </div>
            </div>
            <div class="form-group">
                <label for="saldo" class="col-md-2 control-label">Varastosaldo</label>
                <div class="col-md-1">
                    <input type="number" class="form-control" id="saldo" name="saldo" value="<?php echo $tuote->getSaldo(); ?>"
                           <?php if (!$data->muokkaa): { ?> readonly<?php } endif; ?>>
                </div>
            </div>
            <div class="form-group">
                <label for="tilauskynnys" class="col-md-2 control-label">Tilauskynnys</label>
                <div class="col-md-1">
                    <input type="number" class="form-control" id="tilauskynnys" name="tilauskynnys" value="<?php echo $tuote->getTilauskynnys(); ?>"
                           <?php if (!$data->muokkaa): { ?> readonly<?php } endif; ?>>
                </div>
            </div>
            <?php if ($data->muokkaa): { ?>
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-4">
                            <button type="submit" class="btn btn-default">Tallenna muutokset</button>
                        </div>
                    </div>
                <?php } endif; ?>    
        </form>
        <?php if (!$data->muokkaa && onYllapitaja()): { ?>
                <form class="form-horizontal" action="tuotevalikoima.php?muokkaa=<?php echo $tuote->getTuotenro(); ?>" method="POST">
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-4">
                            <button type="submit" class="btn btn-default">Muokkaa</button>
                        </div>
                    </div>
                </form>
            <?php } endif; ?>
        <?php if ($data->muokkaa): { ?>
                <form class="form-horizontal" action="tuotevalikoima.php?tuotenro=<?php echo $tuote->getTuotenro(); ?>" method="POST">
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-4">
                            <button type="submit" class="btn btn-default">Peru muutokset</button>
                        </div>
                    </div>
                </form>
            <?php } else: { ?>
                <form class="form-horizontal" action="tuotevalikoima.php" method="POST">
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-4">
                            <button type="submit" class="btn btn-default">Uusi haku</button>
                        </div>
                    </div>
                </form>
            <?php } endif; ?>
    </div>
</div>