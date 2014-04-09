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

insert into tuote VALUES(DEFAULT,'TUOTE01_TESTATAANPITUUTTA','on se hiano, mutta vois olla hienompikin eiko niin','Awesomeproducts Additions',112499.99,DEFAULT);
insert into tuote VALUES(DEFAULT,'TUOTE02','on se hiano','Awesomeproducts',14.00);
insert into tuote VALUES(DEFAULT,'TUOTE03','on se hiano','Awesomeproducts',1.39);
insert into tuote VALUES(DEFAULT,'AAD2_HF04','on se hiano','SGSurvivalGear',110.99,10);
insert into tuote VALUES(DEFAULT,'TUOTE04','on se hiano','Awesomeproducts',399.00);
insert into tuote VALUES(DEFAULT,'TUOTE05','on se hiano','Awesomeproducts',14.10);
insert into tuote VALUES(DEFAULT,'AAD2_HF02','on se hiano','SGSurvivalGear',189.99,10);
insert into tuote VALUES(DEFAULT,'TUOTE06','on se hiano','Awesomeproducts',52.00);
insert into tuote VALUES(DEFAULT,'TUOTE07','on se hiano','Awesomeproducts',10.29);
insert into tuote VALUES(DEFAULT,'ADDD_HF01','on se hiano','SGSurvivalGear',39.99);
insert into tuote VALUES(DEFAULT,'TUOTE08','on se hiano','Awesomeproducts',42.09,0,0,localtimestamp);

insert into tilaus (asiakasnro) VALUES(
	1001
);

insert into ostos VALUES(
	10000001,
	100001,
	1,
	DEFAULT
);
