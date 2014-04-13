<div class="container">
    <div class="row">
        <h2><?php $kpl = $data->tuloksia; echo $kpl; ?> hakutulos<?php if ($kpl != 1): {?>ta<?php } endif;?></h2><br>
    </div>

    <?php if ($data->sivuja > 1): {?>
        <div class="row">
            <div class="btn-group">            
                <a <?php if ($data->sivu > 1): ?>
                    href="asiakashallinta.php?haku=listaa&sivu=<?php echo $data->sivu - 1; ?>"
                    <?php endif; ?> class="btn btn-default" 
                    <?php if ($data->sivu == 1): ?>disabled<?php endif; ?>>
                    <span class="glyphicon glyphicon-arrow-left"></span>
                </a>
                <button class="btn btn-success" disabled>
                    sivu <?php echo $data->sivu; ?> / <?php echo $data->sivuja; ?>
                </button>
                <a <?php if ($data->sivu < $data->sivuja): ?>
                        href="asiakashallinta.php?haku=listaa&sivu=<?php echo $data->sivu + 1; ?>"
                    <?php endif; ?> class="btn btn-default"
                    <?php if ($data->sivu >= $data->sivuja): ?>disabled<?php endif; ?>>
                    <span class="glyphicon glyphicon-arrow-right"></span>
                </a>
            </div>
        </div>
    <?php } endif; ?>

    <div class="row">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>Tunnus</th>
                    <th>Yritysnimi</th>
                    <th>Email</th>
                    <th>Puhelinnumero</th>
                    <th>Luottoraja EUR</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php $rivi = $data->rivi;
                foreach ($data->asiakkaat as $asiakas):?>
                <tr>
                    <td><?php echo $rivi++;?></td>
                    <td><?php echo $asiakas->getTunnus();?></td>
                    <td><?php echo $asiakas->getYritysnimi();?></td>
                    <td><?php echo $asiakas->getEmail();?></td>
                    <td><?php echo $asiakas->getPuhelinnumero();?></td>
                    <td><?php echo $asiakas->getLuottoraja();?></td>
                    <td><a href="asiakashallinta.php?asiakasnro=<?php echo $asiakas->getTunnus();?>"
                        class="btn btn-xs btn-default" target="_blank"><span class="glyphicon glyphicon-eye-open"></span>
                        </a>
                    </td>
                    <td><a href="asiakashallinta.php?muokkaa=<?php echo $asiakas->getTunnus();?>" 
                        class="btn btn-xs btn-default" target="_blank"><span class="glyphicon glyphicon-wrench"></span>
                        </a>
                    </td>
                    <td><a href="#" class="btn btn-xs btn-default"><span 
                        class="glyphicon glyphicon-th-list" target="_blank"></span> Avoimet tilaukset
                        </a>
                    </td>
                    <td><a href="#" class="btn btn-xs btn-default"><span 
                        class="glyphicon glyphicon-th-list" target="_blank"></span> Kaikki tilaukset
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="row">
        <p><a class = "btn btn-default" href = "asiakashallinta.php">Uusi haku</a></p>
    </div>
</div>