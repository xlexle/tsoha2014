<div class="container">
    <div class="row">
        <h2><?php $kpl = $data->tuloksia; echo $kpl; ?> hakutulos<?php if ($kpl != 1):?>ta<?php endif;?></h2><br>
    </div>

    <?php if ($data->sivuja > 1):?>
        <div class="row">
            <div class="btn-group">            
                <a <?php if ($data->sivu > 1): ?>
                    href="tilausseuranta.php?haku=listaa&sivu=<?php echo $data->sivu - 1; ?>"
                    <?php endif; ?> class="btn btn-default" 
                    <?php if ($data->sivu == 1): ?>disabled<?php endif; ?>>
                    <span class="glyphicon glyphicon-arrow-left"></span>
                </a>
                <button class="btn btn-success" disabled>
                    sivu <?php echo $data->sivu; ?> / <?php echo $data->sivuja; ?>
                </button>
                <a <?php if ($data->sivu < $data->sivuja): ?>
                        href="tilausseuranta.php?haku=listaa&sivu=<?php echo $data->sivu + 1; ?>"
                    <?php endif; ?> class="btn btn-default"
                    <?php if ($data->sivu >= $data->sivuja): ?>disabled<?php endif; ?>>
                    <span class="glyphicon glyphicon-arrow-right"></span>
                </a>
            </div>
        </div>
    <?php endif;?>
    <hr>

    <div class="row">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>Tilausnumero</th>
                    <th>Ostoviite</th>
                    <th>Asiakasnumero</th>
                    <th>Arvo (EUR)</th>
                    <th>Luotu</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
            <?php $rivi = $data->rivi;
            foreach ($data->tilaukset as $tilaus):;?>
                <tr>
                    <td><?php echo $rivi++;?></td>
                    <td><?php echo $tilaus->getTilausnro();?></td>
                    <td><?php echo $tilaus->getOstoviite();?></td>
                    <td><?php echo $tilaus->getAsiakasnro();?></td>
                    <td><?php echo $tilaus->getKokonaisarvo();?></td>
                    <td><?php echo formatoi($tilaus->getSaapumisaika());?></td>
                    <td><a href="tilausseuranta.php?tilausnro=<?php echo $tilaus->getTilausnro();?>" target="_blank" title="Avaa tilaus">
                            <span class="glyphicon glyphicon-eye-open"></span></a>
                    </td>
                    <?php if (onYllapitaja()):?>
                        <td><a href="tilausseuranta.php?muokkaa=<?php echo $tilaus->getTilausnro();?>" target="_blank" title="Muokkaa tilausta">
                                <span class="glyphicon glyphicon-wrench"></span></a>
                        </td>
                    <?php endif;?>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <hr>

    <div class="row">
        <p><a class = "btn btn-default col-md-offset-2" href="tilausseuranta.php?haku=uusi"><span class="glyphicon glyphicon-repeat"></span> Uusi haku</a></p>
    </div>
</div>