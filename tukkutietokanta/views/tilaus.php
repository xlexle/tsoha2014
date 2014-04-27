<div class="container">
    <?php $tilaus = $data->tilaus?>
    <div class="row">
        <h2>Tilaus <?php echo $tilaus->getTilausnro();?></h2><br>
        <form class="form-horizontal" action="tilausseuranta.php?muokkaa=<?php echo $tilaus->getTilausnro();?>" method="POST">
            <div class="form-group">
                <label for="ostoviite" class="col-md-2 control-label">Ostoviite</label>
                <div class="col-md-4">
                    <?php if ($data->muokkaa):?>
                        <input type="text" maxlength="50" class="form-control" id="ostoviite" name="ostoviite" value="<?php echo $tilaus->getOstoviite();?>">
                    <?php else:?>
                        <p class="form-control-static"><?php echo $tilaus->getOstoviite();?></p>
                    <?php endif;?>
                </div>
                <?php if ($data->muokkaa): ?>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span> Tallenna viite</button>
                    </div>
                <?php endif;?>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Asiakas</label>
                <div class="col-md-4">
                    <p class="form-control-static"><?php echo $tilaus->getAsiakasnro();?> / <?php echo $tilaus->getYritysnimi();?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Luotu</label>
                <div class="col-md-2">
                    <p class="form-control-static"><?php echo formatoi($tilaus->getSaapumisaika());?></p>
                </div>
            </div>
            <?php if ($data->toimitettu): ?>
                <div class="form-group">
                    <label class="col-md-2 control-label">Toimitettu</label>
                    <div class="col-md-2">
                        <p class="form-control-static"><?php echo formatoi($tilaus->getToimitettu());?></p>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($data->laskutettu):?>
                <div class="form-group">
                    <label class="col-md-2 control-label">Laskutettu</label>
                    <div class="col-md-2">
                        <p class="form-control-static"><?php echo formatoi($tilaus->getLaskutettu());?></p>
                    </div>
                </div>
            <?php if ($data->maksettu):?>
            <?php endif; ?>
                <div class="form-group">
                    <label class="col-md-2 control-label">Maksettu</label>
                    <div class="col-md-2">
                        <p class="form-control-static"><?php echo formatoi($tilaus->getMaksettu());?></p>
                    </div>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <div class="row">
        <hr>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>Tuotenumero</th>
                    <th>Valmistajan koodi</th>
                    <th>Valmistaja</th>
                    <th>Kappalehinta (EUR)</th>
                    <th>Saldolla / Tilattu</th>
                    <?php if (!$data->toimitettu):?>
                        <th>Varastossa vapaana</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php $summa = 0; foreach ($data->ostokset as $ostos):?>
                    <tr>
                        <td><?php echo $ostos->getTilausrivi();?></td>
                        <td><?php echo $ostos->getTuotenro();?></td>
                        <td><?php echo $ostos->getKoodi();?></td>
                        <td><?php echo $ostos->getValmistaja();?></td>
                        <td><?php echo $ostos->getOstohinta();?></td>
                        <td><?php echo $ostos->getAllokoitumaara();?> / <?php echo $ostos->getTilattumaara();?></td>
                        <?php if (!$data->toimitettu):?>
                            <td><?php echo $ostos->getSaldo();?></td>
                        <?php endif;?>
                    </tr>
                <?php $summa += $ostos->getTilattuMaara() * $ostos->getOstohinta(); endforeach;?>
            </tbody>
        </table>
        
        <div class="row">
            <form class="form-horizontal">
                <div class="form-group">
                    <label class="col-md-2 control-label">Summa</label>
                    <div class="col-md-4">
                        <p class="form-control-static"><?php echo $summa;?> EUR</p>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <hr>
    
    <div class="row">
        <?php if (!$data->muokkaa && !$data->toimitettu && onYllapitaja()):?>
            <form class="form-horizontal" action="tilausseuranta.php?muokkaa=<?php echo $tilaus->getTilausnro();?>" method="POST">
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-4">
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-wrench"></span> Muokkaa</button>
                    </div>
                </div>
            </form>
        <?php endif;?>
        <?php if (!$data->muokkaa):?>
            <form class="form-horizontal" action="tilausseuranta.php?haku=uusi" method="POST">
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-4">
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-repeat"></span> Uusi haku</button>
                    </div>
                </div>
            </form>
        <?php else:?>
            <form class="form-horizontal" action="tilausseuranta.php?tilausnro=<?php echo $tilaus->getTilausnro();?>" method="POST">
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-4">
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-check"></span> Lopeta muokkaus</button>
                    </div>
                </div>
            </form>
        <?php endif;?>
        <br>
    </div>
</div>