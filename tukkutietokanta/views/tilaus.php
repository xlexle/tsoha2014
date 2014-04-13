<div class="container">
    <?php $tilaus = $data->tilaus?>
    <div class="row">
        <h2>Tilaus <?php echo $tilaus->getTilausnro();?></h2><br>
        <form class="form-horizontal">
            <div class="form-group">
                <label class="col-md-2 control-label">Ostoviite</label>
                <div class="col-md-4">
                    <p class="form-control-static"><?php echo $tilaus->getOstoviite();?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Asiakasnumero</label>
                <div class="col-md-1">
                    <p class="form-control-static"><?php echo $tilaus->getAsiakasnro();?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Yritysnimi</label>
                <div class="col-md-2">
                    <p class="form-control-static"><?php echo $tilaus->getYritysnimi();?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label">Luotu</label>
                <div class="col-md-2">
                    <p class="form-control-static"><?php echo formatoi($tilaus->getSaapumisaika());?></p>
                </div>
            </div>
            <?php if ($data->toimitettu): { ?>
                <div class="form-group">
                    <label class="col-md-2 control-label">Toimitettu</label>
                    <div class="col-md-2">
                        <p class="form-control-static"><?php echo formatoi($tilaus->getToimitettu());?></p>
                    </div>
                </div>
            <?php } endif; ?>
            <?php if ($data->laskutettu): { ?>
                <div class="form-group">
                    <label class="col-md-2 control-label">Laskutettu</label>
                    <div class="col-md-2">
                        <p class="form-control-static"><?php echo formatoi($tilaus->getLaskutettu());?></p>
                    </div>
                </div>
            <?php if ($data->maksettu): { ?>
            <?php } endif; ?>
                <div class="form-group">
                    <label class="col-md-2 control-label">Maksettu</label>
                    <div class="col-md-2">
                        <p class="form-control-static"><?php echo formatoi($tilaus->getMaksettu());?></p>
                    </div>
                </div>
            <?php } endif; ?>
        </form>
    </div>

    <div class="row">
        <hr><table class="table table-striped">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>Tuotenumero</th>
                    <th>Valmistajan koodi</th>
                    <th>Valmistaja</th>
                    <th>EUR / kpl</th>
                    <th>Kappalemäärä</th>
                    <th>Varastossa</th>
<!--                    <th>&nbsp;</th>
                    <th>&nbsp;</th>-->
                </tr>
            </thead>
            <tbody>
                <?php $rivi = 0; foreach ($data->ostokset as $ostos):?>
                    <tr>
                        <td><?php echo++$rivi;?></td>
                        <td><?php echo $ostos->getTuotenro();?></td>
                        <td><?php echo $ostos->getKoodi();?></td>
                        <td><?php echo $ostos->getValmistaja();?></td>
                        <td><?php echo $ostos->getOstohinta();?></td>
                        <td><?php echo $ostos->getMaara();?></td>
                        <td><?php echo $ostos->getSaldo();?></td>
<!--                        <td><a href="#" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-wrench"></span> Muuta kappalemäärää</a></td>
                        <td><a href="#" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span> Poista</a></td>-->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="row">
            <form class="form-horizontal">
                <div class="form-group">
                    <label class="col-md-2 control-label">Summa</label>
                    <div class="col-md-4">
                        <p class="form-control-static"><?php echo $tilaus->getKokonaisarvo();?> EUR (0% alv)</p>
                    </div>
                </div>
            </form>
        </div>
        <hr>
    </div>

    <div class="row">
        <form class="form-horizontal" action="tilausseuranta.php?haku=uusi" method="POST">
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <button type="submit" class="btn btn-default">Uusi haku</button>
                </div>
            </div>
        </form>
    </div>
</div>