<div class="container">
    <div class="row">
        <?php $asiakas = $data->asiakas?>
        <h2>Asiakas <?php echo $asiakas->getTunnus();?></h2><br>
        <form class="form-horizontal" action="asiakashallinta.php?tallenna=<?php echo $asiakas->getTunnus();?>" method="POST">
            <div class="form-group">
                <label for="yritysnimi" class="col-md-2 control-label">Yrityksen nimi</label>
                <div class="col-md-4">
                    <input type="text" maxlength="50" class="form-control" id="yritysnimi" name="yritysnimi" value="<?php echo $asiakas->getYritysnimi();?>" 
                           <?php if (!$data->muokkaa): { ?> readonly<?php } endif; ?>>
                </div>
            </div>
            <div class="form-group">
                <label for="osoite" class="col-md-2 control-label">Osoite</label>
                <div class="col-md-4">
                    <input type="text" maxlength="100" class="form-control" id="osoite" name="osoite" value="<?php echo $asiakas->getOsoite();?>" 
                           <?php if (!$data->muokkaa): { ?> readonly<?php } endif; ?>>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-2 control-label">Sähköposti</label>
                <div class="col-md-4">
                    <input type="text" maxlength="50" class="form-control" id="email" name="email" value="<?php echo $asiakas->getEmail();?>"
                           <?php if (!$data->muokkaa): { ?> readonly<?php } endif; ?>>
                </div>
            </div>
            <div class="form-group">
                <label for="yhteyshenkilo" class="col-md-2 control-label">Yhteyshenkilö</label>
                <div class="col-md-4">
                    <input type="text" maxlength ="50" class="form-control" id="yhteyshenkilo" name="yhteyshenkilo" value="<?php echo $asiakas->getYhteyshenkilo();?>"
                           <?php if (!$data->muokkaa): { ?> readonly<?php } endif; ?>>
                </div>
            </div>
            <div class="form-group">
                <label for="puhelinnumero" class="col-md-2 control-label">Puhelinnumero</label>
                <div class="col-md-2">
                    <input type="text" maxlength ="25" class="form-control" id="puhelinnumero" name="puhelinnumero" value="<?php echo $asiakas->getPuhelinnumero();?>"
                           <?php if (!$data->muokkaa): { ?> readonly<?php } endif; ?>>
                </div>
            </div>
            <div class="form-group">
                <label for="luottoraja" class="col-md-2 control-label">Luottoraja (EUR)</label>
                <div class="col-md-2">
                    <input type="text" maxlength ="9" class="form-control" id="luottoraja" name="luottoraja" value="<?php echo $asiakas->getLuottoraja();?>"
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
        <?php if (!$data->muokkaa): { ?>
            <form class="form-horizontal" action="asiakashallinta.php?muokkaa=<?php echo $asiakas->getTunnus();?>" method="POST">
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-4">
                        <button type="submit" class="btn btn-default">Muokkaa</button>
                    </div>
                </div>
            </form>
        <?php } endif; ?>
        <?php if ($data->muokkaa): { ?>
            <form class="form-horizontal" action="asiakashallinta.php?asiakasnro=<?php echo $asiakas->getTunnus();?>" method="POST">
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-4">
                        <button type="submit" class="btn btn-default">Peru muutokset</button>
                    </div>
                </div>
            </form>
            <form class="form-horizontal" action="asiakashallinta.php?poista=<?php echo $asiakas->getTunnus();?>" method="POST">
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-4">
                        <button type="submit" class="btn btn-default">Poista asiakas</button>
                    </div>
                </div>
            </form>
        <?php } endif; ?>
        <?php if (!$data->muokkaa): { ?>
            <form class="form-horizontal" action="asiakashallinta.php" method="POST">
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-4">
                        <button type="submit" class="btn btn-default">Uusi haku</button>
                    </div>
                </div>
            </form>
        <?php } endif; ?>
    </div>
</div>