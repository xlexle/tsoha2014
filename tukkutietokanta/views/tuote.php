<div class="container">
    <div class="row">
        <?php $tuote = $data->tuote?>
        <h2>Tuote <?php echo $tuote->getTuotenro();?><?php if ($data->poistettu): {?> (poistettu valikoimasta)<?php } endif; ?></h2><br>
        <form class="form-horizontal" action="tuotevalikoima.php?tallenna=<?php echo $tuote->getTuotenro();?>" method="POST">
            <div class="form-group">
                <label for="koodi" class="col-md-2 control-label">Valmistajan koodi</label>
                <div class="col-md-4">
                    <?php if ($data->muokkaa): { ?>
                        <input type="text" maxlength="25" class="form-control" id="koodi" name="koodi" value="<?php echo $tuote->getKoodi();?>">
                    <?php } else: { ?>
                        <p class="form-control-static"><?php echo $tuote->getKoodi();?></p>
                    <?php } endif; ?>
                </div>
            </div>
            <div class="form-group">
                <label for="kuvaus" class="col-md-2 control-label">Kuvaus</label>
                <div class="col-md-4">
                    <?php if ($data->muokkaa): { ?>
                        <input type="text" maxlength="50" class="form-control" id="kuvaus" name="kuvaus" value="<?php echo $tuote->getKuvaus();?>">
                    <?php } else: { ?>
                        <p class="form-control-static"><?php echo $tuote->getKuvaus();?></p>
                    <?php } endif; ?>
                </div>
            </div>
            <div class="form-group">
                <label for="valmistaja" class="col-md-2 control-label">Valmistaja</label>
                <div class="col-md-4">
                    <?php if ($data->muokkaa): { ?>
                        <input type="text" maxlength="25" class="form-control" id="valmistaja" name="valmistaja" value="<?php echo $tuote->getValmistaja();?>">
                    <?php } else: { ?>
                        <p class="form-control-static"><?php echo $tuote->getValmistaja();?></p>
                    <?php } endif; ?>
                </div>
            </div>
            <div class="form-group">
                <label for="hinta" class="col-md-2 control-label">Hinta EUR</label>
                <div class="col-md-2">
                    <?php if ($data->muokkaa): { ?>
                        <input type="text" maxlength="12" class="form-control" id="hinta" name="hinta" value="<?php echo $tuote->getHinta();?>">
                    <?php } else: { ?>
                        <p class="form-control-static"><?php echo $tuote->getHinta();?></p>
                    <?php } endif; ?>
                </div>
            </div>
            <?php if (!$data->poistettu): { ?>
                <div class="form-group">
                    <label for="saldo" class="col-md-2 control-label">Varastosaldo</label>
                    <div class="col-md-1">
                        <?php if ($data->muokkaa): { ?>
                            <input type="number" min="0" class="form-control" id="saldo" name="saldo" value="<?php echo $tuote->getSaldo();?>">
                        <?php } else: { ?>
                            <p class="form-control-static"><?php echo $tuote->getHinta();?></p>
                        <?php } endif; ?>
                    </div>
                </div>
            <?php } endif; ?>   
            <div class="form-group">
                <label for="tilauskynnys" class="col-md-2 control-label">Tilauskynnys</label>
                <div class="col-md-1">
                    <?php if ($data->muokkaa): { ?>
                            <input type="number" min="0" class="form-control" id="tilauskynnys" name="tilauskynnys" value="<?php echo $tuote->getTilauskynnys();?>">
                    <?php } else: { ?>
                        <p class="form-control-static"><?php echo $tuote->getTilauskynnys();?></p>
                    <?php } endif; ?>
                </div>
            </div>
            <?php if ($data->poistettu): { ?>
                <div class="form-group">
                    <label class="col-md-2 control-label">Poistettu</label>
                    <div class="col-md-2">
                        <p class="form-control-static"><?php echo formatoi($tuote->getPoistettu());?></p>
                    </div>
                </div>
            <?php } endif; ?>
            <?php if ($data->muokkaa): { ?>
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-4">
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-ok"></span> Tallenna muutokset</button>
                    </div>
                </div>
            <?php } endif; ?>    
        </form>
        <?php if (!onYllapitaja()): { ?>
            <form class="form-horizontal" action="ostoskori.php?lisaaostos=<?php echo $tuote->getTuotenro();?>" method="POST">
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-4">
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-shopping-cart"></span> Lisää ostoskoriin</button>
                    </div>
                </div>
            </form>
        <?php } endif; ?>
        <?php if (!$data->muokkaa && !$data->poistettu && onYllapitaja()): { ?>
            <form class="form-horizontal" action="tuotevalikoima.php?muokkaa=<?php echo $tuote->getTuotenro();?>" method="POST">
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-4">
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-wrench"></span> Muokkaa</button>
                    </div>
                </div>
            </form>
        <?php } endif; ?>
        <?php if ($data->muokkaa): { ?>
            <form class="form-horizontal" action="tuotevalikoima.php?tuotenro=<?php echo $tuote->getTuotenro();?>" method="POST">
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-4">
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span> Peru muutokset</button>
                    </div>
                </div>
            </form>
        <?php } endif; ?>
    </div>
    <hr>
    
    <div class="row">
        <?php if ($data->muokkaa): { ?>
            <form class="form-horizontal" action="tuotevalikoima.php?poista=<?php echo $tuote->getTuotenro();?>" method="POST">
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-4">
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-remove-circle"></span> Poista tuote valikoimasta</button>
                    </div>
                </div>
            </form>
        <?php } elseif ($data->poistettu): {?>
            <form class="form-horizontal" action="tuotevalikoima.php?palauta=<?php echo $tuote->getTuotenro();?>" method="POST">
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-4">
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-ok-circle"></span> Palauta tuote valikoimaan</button>
                    </div>
                </div>
            </form>
            <form class="form-horizontal" action="tuotevalikoima.php?poistafinal=<?php echo $tuote->getTuotenro();?>" method="POST">
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-4">
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-trash"></span> Poista tuote lopullisesti</button>
                    </div>
                </div>
            </form>
        <?php } endif; ?>
        <?php if (!$data->muokkaa): { ?>
            <form class="form-horizontal" action="tuotevalikoima.php" method="POST">
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-4">
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-repeat"></span> Uusi haku</button>
                    </div>
                </div>
            </form>
        <?php } endif; ?>
    </div>
</div>