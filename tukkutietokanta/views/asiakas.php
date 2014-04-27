<div class="container">
    <div class="row">
        <?php $asiakas = $data->asiakas?>
        <h2>Asiakas <?php echo $asiakas->getTunnus();?></h2><br>
        <form class="form-horizontal" action="asiakashallinta.php?tallenna=<?php echo $asiakas->getTunnus();?>" method="POST">
            <div class="form-group">
                <label for="yritysnimi" class="col-md-2 control-label">Yrityksen nimi</label>
                <div class="col-md-4">
                    <?php if ($data->muokkaa): ?>
                        <input type="text" maxlength="50" class="form-control" id="yritysnimi" name="yritysnimi" value="<?php echo $asiakas->getYritysnimi();?>">
                    <?php else: ?>
                        <p class="form-control-static"><?php echo $asiakas->getYritysnimi();?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <label for="osoite" class="col-md-2 control-label">Osoite</label>
                <div class="col-md-4">
                    <?php if ($data->muokkaa): ?>
                        <input type="text" maxlength="100" class="form-control" id="osoite" name="osoite" value="<?php echo $asiakas->getOsoite();?>">
                    <?php else: ?>
                        <p class="form-control-static"><?php echo $asiakas->getOsoite();?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-md-2 control-label">Sähköposti</label>
                <div class="col-md-4">
                    <?php if ($data->muokkaa): ?>
                        <input type="text" maxlength="50" class="form-control" id="email" name="email" value="<?php echo $asiakas->getEmail();?>">
                    <?php else: ?>
                        <p class="form-control-static"><?php echo $asiakas->getEmail();?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <label for="yhteyshenkilo" class="col-md-2 control-label">Yhteyshenkilö</label>
                <div class="col-md-4">
                    <?php if ($data->muokkaa): ?>
                        <input type="text" maxlength="50" class="form-control" id="yhteyshenkilo" name="yhteyshenkilo" value="<?php echo $asiakas->getYhteyshenkilo();?>">
                    <?php else: ?>
                        <p class="form-control-static"><?php echo $asiakas->getYhteyshenkilo();?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <label for="puhelinnumero" class="col-md-2 control-label">Puhelinnumero</label>
                <div class="col-md-2">
                    <?php if ($data->muokkaa): ?>
                        <input type="text" maxlength="25" class="form-control" id="puhelinnumero" name="puhelinnumero" value="<?php echo $asiakas->getPuhelinnumero();?>">
                    <?php else: ?>
                        <p class="form-control-static"><?php echo $asiakas->getPuhelinnumero();?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <label for="luottoraja" class="col-md-2 control-label">Luottoraja (EUR)</label>
                <div class="col-md-2">
                    <?php if ($data->muokkaa): ?>
                        <input type="text" maxlength="9" class="form-control" id="luottoraja" name="luottoraja" value="<?php echo $asiakas->getLuottoraja();?>">
                    <?php else: ?>
                        <p class="form-control-static"><?php echo $asiakas->getLuottoraja();?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php if ($data->muokkaa): ?>
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-4">
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-ok"></span> Tallenna muutokset</button>
                    </div>
                </div>
            <?php endif; ?>    
        </form>
        <?php if ($data->muokkaa): ?>
            <form class="form-horizontal" action="asiakashallinta.php?asiakasnro=<?php echo $asiakas->getTunnus();?>" method="POST">
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-4">
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span> Peru muutokset</button>
                    </div>
                </div>
            </form>
        <?php elseif (onYllapitaja()): ?>
            <form class="form-horizontal" action="asiakashallinta.php?muokkaa=<?php echo $asiakas->getTunnus();?>" method="POST">
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-4">
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-wrench"></span> Muokkaa</button>
                    </div>
                </div>
            </form>
        <?php endif; ?>  
        <hr>
    </div>
        
    <div class="row">
        <?php if ($data->muokkaa): ?>
            <form class="form-horizontal" action="asiakashallinta.php?poista=<?php echo $asiakas->getTunnus();?>" method="POST">
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-4">
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-trash"></span> Poista asiakas</button>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <form class="form-horizontal" action="tilausseuranta.php?haku=listaa" method="POST">
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-4">
                        <input type="hidden" name="asiakasnro" value="<?php echo $asiakas->getTunnus()?>">
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-repeat"></span> Hae tilaukset</button>
                    </div>
                </div>
            </form>
            <?php if (onYllapitaja()): ?>
                <form class="form-horizontal" action="asiakashallinta.php" method="POST">
                    <div class="form-group">
                        <div class="col-md-offset-2 col-md-4">
                            <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-repeat"></span> Uusi haku</button>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>