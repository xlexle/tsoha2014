<div class="container">
    <div class="row">
        <h2>Tuotehaku</h2>
        <p>tuloksia: <?php echo $data->lukumaara;?><br>
           sivu: <?php echo $data->sivu; ?>/<?php echo $data->sivuja; ?></p><br>
        <?php if ($data->sivu > 1): ?>
            <a href="tuotevalikoima.php?haku=listaa&sivu=<?php echo $data->sivu - 1; ?>">Edellinen sivu</a>
        <?php endif; ?>
            &nbsp;
        <?php if ($data->sivu < $data->sivuja): ?>
            <a href="tuotevalikoima.php?haku=listaa&sivu=<?php echo $data->sivu + 1; ?>">Seuraava sivu</a>
        <?php endif; ?>
    </div>
        
    <div class="row">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>Tuotenumero</th>
                    <th>Valmistajan koodi</th>
                    <th>Kuvaus</th>
                    <th>Valmistaja</th>
                    <th>Varastossa</th>
                    <th>EUR</th>
                    <?php if (onYllapitaja()): { ?>
                        <th>Avoimia tilauksia</th>
                    <?php } endif; ?>
                    <th>&nbsp;</th>
                    <?php if (onYllapitaja()): { ?>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    <?php } endif; ?>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php $rivi = $data->rivi; foreach ($data->tuotteet as $tuote):?>
                    <tr>
                        <td><?php echo $rivi++; ?></td>
                        <td><?php echo $tuote->getTuotenro(); ?></td>
                        <td><?php echo $tuote->getKoodi(); ?></td>
                        <td><?php echo $tuote->getKuvaus(); ?></td>
                        <td><?php echo $tuote->getValmistaja(); ?></td>
                        <td><?php echo $tuote->getSaldo(); ?></td>
                        <td><?php echo $tuote->getHinta(); ?></td>
                        <?php if (onYllapitaja()): { ?>
                            <td><?php echo $tuote->getAvoimiaTilauksia(); ?></td>
                        <?php } endif; ?>
                        <td><a href="#" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-eye-open"></span></a></td>  
                        <?php if (onYllapitaja()): { ?>
                            <td><a href="#" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-wrench"></span></a></td>
                            <td><a href="#" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-th-list"></span> Avoimet tilaukset</a></td>
                            <td><a href="#" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-th-list"></span> Kaikki tilaukset</a></td>
                        <?php } else: { ?>
                            <td><a href="#" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-plus"></span> Lisää tilaukselle</a></td>
                        <?php } endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
        
    <div class="row">
        <p><a class = "btn btn-default" href = "tuotevalikoima.php">Uusi haku</a></p>
    </div>
    
</div>