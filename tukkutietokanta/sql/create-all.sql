create sequence asiakas_id start 1000;
create sequence tilaus_id start 10000;
create sequence tuote_id start 100000;

create table asiakas(
	tunnus integer DEFAULT nextval('asiakas_id') PRIMARY KEY,
	salasana varchar(16) NOT NULL,
	yritysnimi varchar(25) NOT NULL,
	osoite varchar(100) NOT NULL,
	email varchar(50) NOT NULL,
	yhteyshenkilo varchar(50) NOT NULL,
	puhelinnumero varchar(25) NOT NULL,
	luottoraja decimal(6,0) DEFAULT 1000.00
);

create table yllapitaja(
	tunnus char(7) PRIMARY KEY,
	salasana varchar(16) NOT NULL
);	

create table tuote(
	tuotenro integer DEFAULT nextval('tuote_id') PRIMARY KEY,
	koodi varchar(25),
	kuvaus varchar(50),
	hinta dec(6,2),
	saldo integer DEFAULT 0,
	tilauskynnys integer
);

create table tilaus(
	tilausnro integer DEFAULT nextval('tilaus_id') PRIMARY KEY,
	kokonaisarvo dec(6,2),
	saapumisaika timestamp NOT NULL DEFAULT localtimestamp,
	toimitettu timestamp,
	laskutettu timestamp,
	maksettu timestamp,
	asiakasnro integer REFERENCES asiakas ON DELETE RESTRICT
);

create table ostos(
	tilausnro integer REFERENCES tilaus ON DELETE RESTRICT,
	tuotenro integer REFERENCES tuote ON DELETE RESTRICT,
	maara integer NOT NULL,
	peruttu timestamp,
	CONSTRAINT pk_ostos 
		PRIMARY KEY (tilausnro, tuotenro)
);