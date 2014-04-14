<div class="container">
    <div class="row">
        <h2>Ostoskori</h2><br>
        <form class="form-horizontal" action="ostoskori.php" method="GET">
            <div class="form-group">
                <label for="lisaaostos" class="col-md-2 control-label">Tuotenumero</label>
                <div class="col-md-2">
                    <input type="text" maxlength="6" class="form-control" id="lisaaostos" name="lisaaostos" value="<?php echo $data->tuotenro;?>" placeholder="6 numeroa">
                </div>
                <div class="col-md-1">
                    <input type="number" min="1" class="form-control" id="kpl" name="kpl" placeholder="1 kpl">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-plus-sign"></span> Lisää tilaukselle</button>
                </div>
            </div>
        </form>
        <br>
    </div>

    <?php $ostoskori = (array) $_SESSION['ostoskori']; if (!empty($ostoskori)): ?>
    <div class="row">
        <hr>
        <form class="form-horizontal" action="ostoskori.php" method="POST">
            <div class="form-group">
                <div class="col-md-offset-2 col-md-1">
                    <input type="number" min="1" class="form-control" id="rivi" name="rivi" placeholder="rivi">
                </div>
                <div class="col-md-1">
                    <input type="number" min="0" max="999" class="form-control" id="kpl" name="kpl" placeholder="kpl">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span> Muuta kappalemäärää</button>
                </div>
            </div>
        </form><br>
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
                </tr>
            </thead>
            <tbody>
            <?php $summa = 0; foreach ($ostoskori as $ostos):?>
                    <tr>
                        <td><?php echo $ostos->getTilausrivi();?></td>
                        <td><?php echo $ostos->getTuotenro();?></td>
                        <td><?php echo $ostos->getKoodi();?></td>
                        <td><?php echo $ostos->getValmistaja();?></td>
                        <td><?php echo $ostos->getOstohinta();?></td>
                        <td><?php echo $ostos->getMaara();?></td>
                        <td><?php echo $ostos->getSaldo();?></td>
                    </tr>
            <?php $summa += $ostos->getMaara() * $ostos->getOstohinta(); endforeach; ?>
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
        <form class="form-horizontal" action="tilausseuranta.php?ostoskori=laheta" method="POST">
            <div class="form-group">
                <label for="ostoviite" class="col-md-2 control-label">Ostoviite</label>
                <div class="col-md-4">
                    <input type="text" maxlength="50" class="form-control" id="ostoviite" name="ostoviite">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-ok-circle"></span> Lähetä tilaus</button>
                </div>
            </div>
        </form>
    </div>
    
    <div class="row">
        <form class="form-horizontal" action="ostoskori.php" method="POST">
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <input type="hidden" name="tyhjenna" value=1>
                    <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-trash"></span> Tyhjennä ostoskori</button>
                </div>
            </div>
        </form>
    </div>
    <?php endif; ?>
</div>