<div class="container">
    <div class="row">
        <h2>Uusi tilaus</h2><br>
        <form class="form-horizontal" action="ostoskori.php?toiminto=lisaaostos" method="GET">
            <div class="form-group">
                <label for="lisaaostos" class="col-md-2 control-label">Tuote</label>
                <div class="col-md-3">
                    <input type="text" maxlength="25" class="form-control" id="lisaaostos" name="lisaaostos" value="<?php echo $data->tuotenro;?>" placeholder="tuotenumero">
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
        <br>
    </div>

    <?php $ostoskori = (array) $_SESSION['ostoskori'];
    if (!empty($ostoskori)): ?>
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
                <?php $rivi = 0; $summa = 0;
                foreach ($ostoskori as $ostos):?>
                    <tr>
                        <td><?php echo $ostos->getTilausrivi();?></td>
                        <td><?php echo $ostos->getTuotenro();?></td>
                        <td><?php echo $ostos->getKoodi();?></td>
                        <td><?php echo $ostos->getValmistaja();?></td>
                        <td><?php echo $ostos->getOstohinta();?></td>
                        <td><?php echo $ostos->getMaara();?></td>
                        <td><?php echo $ostos->getSaldo();?></td>
<!--                        <td><a href="#" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-wrench"></span> Muuta kappalemäärää</a></td>
                        <td><a href="#" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span> Poista</a></td>-->
                    </tr>
                <?php $summa += $ostos->getMaara() * $ostos->getOstohinta(); endforeach; ?>
            </tbody>
        </table>
        
        <div class="row">
            <form class="form-horizontal">
                <div class="form-group">
                    <label class="col-md-2 control-label">Summa</label>
                    <div class="col-md-4">
                        <p class="form-control-static"><?php echo $summa;?> EUR (0% alv)</p>
                    </div>
                </div>
            </form>
        </div>
        <hr>
    </div>

    <div class="row">
        <form class="form-horizontal" action="tilausseuranta.php?ostoskori=laheta" method="POST">
            <div class="form-group">
                <label for="ostoviite" class="col-md-2 control-label">Ostoviite</label>
                <div class="col-md-4">
                    <input type="text" maxlength="50" class="form-control" id="ostoviite" name="ostoviite">
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
        <form class="form-horizontal" action="ostoskori.php" method="POST">
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <input type="hidden" name="tyhjenna" value=1>
                    <button type="submit" class="btn btn-default">Tyhjennä ostoskori</button>
                </div>
            </div>
        </form>
    </div>
    <?php endif; ?>
</div>