<div class="container">
    <div class="row">
        <h2>Uusi tilaus</h2><br>
        <form class="form-horizontal" action="ostoskori.php?action=add" method="POST">
            <div class="form-group">
                <label for="tuote" class="col-md-2 control-label">Tuote</label>
                <div class="col-md-3">
                    <input type="text" maxlength="25" class="form-control" id="tuote" name="tuote" value="<?php echo $data->tuote;?>" placeholder="tuotenumero tai valmistajan koodi">
                </div>
                <div class="col-md-1">
                    <input type="number" min="1" class="form-control" id="kpl" name="kpl" placeholder="1 kpl">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <button type="submit" class="btn btn-default">Lisää tilaukselle</button>
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>Tuotenumero</th>
                    <th>Valmistajan koodi</th>
                    <th>Valmistaja</th>
                    <th>EUR / kpl</th>
                    <th>Kappalemäärä</th>
                    <th>Varastossa</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php $rivi = 0; foreach ($data->ostoskori as $ostos):?>
                    <tr>
                        <td><?php echo++$rivi;?></td>
                        <td><?php echo $ostos->getTuotenro();?></td>
                        <td><?php echo $ostos->getKoodi();?></td>
                        <td><?php echo $ostos->getValmistaja();?></td>
                        <td><?php echo $ostos->getHinta();?></td>
                        <td><?php echo $ostos->getMaara();?></td>
                        <td><?php echo $ostos->getSaldo();?></td>
                        <td><a href="#" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-wrench"></span> Muuta kappalemäärää</a></td>
                        <td><a href="#" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span> Poista</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <hr>
    </div>

    <div class="row">
        <form class="form-horizontal" action="ostoskori.php?action=send" method="POST">
            <div class="form-group">
                <label for="viite" class="col-md-2 control-label">Ostoviite</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" id="viite" name="viite" value="<?php echo $data->viite; ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <button type="submit" class="btn btn-default">Lähetä tilaus</button>
                </div>
            </div>
        </form>
    </div>
    <div class="row">
        <form class="form-horizontal" action="ostoskori.php?action=cancel" method="POST">
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <button type="submit" class="btn btn-default">Peru tilaus</button>
                </div>
            </div>
        </form>
    </div>
</div>