<div class="container">
    <div class="row">
        <h2>Tilausseuranta</h2><br>
        <form class="form-horizontal" action="tilausseuranta.php?haku=listaa" method="POST">
            <?php if (onYllapitaja()):?>
                    <div class="form-group">
                        <label for="asiakasnro" class="col-md-2 control-label">Asiakasnumero</label>
                        <div class="col-md-2">
                            <input type="text" maxlength="4" class="form-control" id="asiakasnro" name="asiakasnro" placeholder="4 numeroa">
                        </div>
                    </div>
                <?php endif;?>
            <div class="form-group">
                <label for="viite" class="col-md-2 control-label">Ostoviite</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" id="viite" name="viite">
                </div>
            </div>
            <div class="form-group">
                <label for="tuotenro" class="col-md-2 control-label">Tuotenumero</label>
                <div class="col-md-2">
                    <input type="text" class="form-control" id="tuotenro" name="tuotenro" placeholder="6 numeroa">
                </div>
            </div>
<!--            <div class="form-group">
                <label for="luontipvm_min" class="col-md-2 control-label">Luotu</label>
                <div class="col-md-2">
                    <input type="date" class="form-control" id="luontipvm_min" name="luontipvm_min">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" id="luontipvm_max" name="luontipvm_max">
                </div>
            </div>-->
            <div class="form-group">
                <div class="col-md-offset-2 col-md-3">
                    <div class="checkbox">
                        <label><input type="checkbox" name="toimitettu" value=1> Rajaa toimitettuihin</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-3">
                    <div class="checkbox">
                        <label><input type="checkbox" name="laskutettu" value=1> Rajaa laskutettuihin</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-3">
                    <div class="checkbox">
                        <label><input type="checkbox" name="maksettu" value=1> Rajaa maksettuihin</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-th-list"></span> Hae tilauksia</button>
                </div>
            </div>
        </form>
    </div>
    <hr>

    <div class="row">
        <br>
        <form class="form-horizontal" action="tilausseuranta.php" method="GET">
            <div class="form-group">
                <label for="tilausnro" class="col-md-2 control-label">Etsi tilausnumerolla</label>
                <div class="col-md-2">
                    <input type="text" maxlength="8" class="form-control" id="tilausnro" name="tilausnro" placeholder="8 numeroa">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span> Hae tilaus</button>
                </div>
            </div>
        </form>  
    </div>
</div>