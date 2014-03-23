insert into asiakas VALUES(
	DEFAULT,
	'salasana',
	'Testiasiakas',
	'Testikatu 3, 00001 TESTIKAUPUNKI',
	'testi@testi.fi',
	'Teppo Testaaja',
	'000-73571',
	DEFAULT
);

insert into yllapitaja VALUES
	('admin01','salasana01'),
	('admin02','salasana02'),
	('admin03','salasana03');

insert into tuote VALUES(
	DEFAULT,
	'TUOTE01',
	'on se hiano',
	100.00,
	DEFAULT
);

insert into tilaus (asiakasnro) VALUES(
	1000
);

insert into ostos VALUES(
	10000,
	100000,
	1,
	DEFAULT
);
