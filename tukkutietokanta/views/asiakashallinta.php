<div class="container">
    <div class="row">
        <h2>Asiakashallinta</h2><br>
        <form class="form-horizontal" action="asiakashallinta.php" method="GET">
            <div class="form-group">
                <label for="asiakasnro" class="col-md-2 control-label">Etsi asiakasnumerolla</label>
                <div class="col-md-2">
                    <input type="text" maxlength="4" class="form-control" id="asiakasnro" name="asiakasnro" placeholder="4 numeroa">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span> Hae asiakas</button>
                </div>
            </div>
        </form>
    </div>
    <hr>

    <div class="row">
        <br>
        <p><a class="btn btn-default col-md-offset-2" href="asiakashallinta.php?haku=listaa"><span class="glyphicon glyphicon-th-list"></span> Hae kaikki asiakkaat</a></p>
        <p><a class="btn btn-default col-md-offset-2" href="asiakashallinta.php?asiakas=uusi"><span class="glyphicon glyphicon-plus-sign"></span> Uusi asiakas</a></p>
    </div>
</div>