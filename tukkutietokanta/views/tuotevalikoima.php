<div class="container">
    <h1>Tuomiopäivän Tukkuliike</h1>
    <p><em>Kaikkea mahdollista pahan päivän varalle</em></p><br><br>
    <div class="row">
        <br>
        <div class="col-md-3">
            <div class="panel panel-default">
                <ul class="nav">
                    <li><a href="#">Luo uusi tilaus</a></li>
                    <li class="active"><a href="#">Tuotevalikoima</a></li>
                    <li><a href="#">Tilausten seuranta</a></li>
                    <li><a href="#">Asiakkaiden hallinta</a></li>
                </ul>
            </div>
        </div>

        <div class="col-md-2">
            <a class="btn btn-default" href="kirjaudu.php">Kirjaudu ulos</a>
        </div>
    </div>

    <div class="row">
        <h2>Tuotevalikoima</h2>
        <br>
        <form class="form-horizontal" method="POST">
            <div class="form-group">
                <label for="valmistaja" class="col-md-2 control-label">Valmistaja</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" id="valmistaja" name="valmistaja">
                </div>
            </div>
            <div class="form-group">
                <label for="hintaAlk" class="col-md-2 control-label">Hinta vähintään</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" id="hintaAlk" name="hintaAlk">
                </div>
            </div>
            <div class="form-group">
                <label for="hintaEnint" class="col-md-2 control-label">Hinta enintään</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" id="hintaEnint" name="hintaEnint">
                </div>
            </div>
            <div class="form-group">
                <label for="saldoAlk" class="col-md-2 control-label">Varastossa vähintään</label>
                <div class="col-md-4">
                    <input type="text" class="form-control" id="saldoAlk" name="saldoAlk">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <a class="btn btn-default" href="#">Hae tuotteita</a>
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <br>
        <form class="form-horizontal" method="POST">
            <div class="form-group">
                <label for="tuotenumero" class="col-md-2 control-label">Etsi tuotenumerolla</label>
                <div class="col-md-4">
                    <input type="number" class="form-control" id="tuotenumero" name="tuotenumero">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-4">
                    <a class="btn btn-default" href="#">Hae tuote</a>
                </div>
            </div>
        </form>  
    </div>

    <div class="row">
        <br>
        <p><a class="btn btn-default" href="#">Listaa kaikki tuotteet</a></p>
        <p><a class="btn btn-default" href="#">Listaa tuotteet joista avoimia tilauksia</a></p>
        <p><a class="btn btn-default" href="#">Luo uusi tuote</a></p>
    </div>
</div>
