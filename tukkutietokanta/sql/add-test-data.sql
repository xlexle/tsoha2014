insert into asiakas (salasana, yritysnimi, osoite, email, yhteyshenkilo, puhelinnumero, luottoraja) VALUES
	('salasana','Testiasiakas1','Testikatu 1, 00001 TESTIKAUPUNKI','testi@testi1.fi','Yhteys Henkilo','000-735711',2000),
	('salasana','Testiasiakas2','Testikatu 2, 00001 TESTIKAUPUNKI','testi@testi2.fi','Yhteys Henkilo','000-735712',5000),
	('salasana','Testiasiakas3','Testikatu 3, 00001 TESTIKAUPUNKI','testi@testi3.fi','Yhteys Henkilo','000-735713',20000),
	('salasana','Testiasiakas4','Testikatu 4, 00001 TESTIKAUPUNKI','testi@testi4.fi','Yhteys Henkilo','000-735714', DEFAULT),
	('salasana','Testiasiakas5','Testikatu 5, 00001 TESTIKAUPUNKI','testi@testi5.fi','Yhteys Henkilo','000-735715', DEFAULT),
	('salasana','Testiasiakas6','Testikatu 6, 00001 TESTIKAUPUNKI','testi@testi6.fi','Yhteys Henkilo','000-735716', DEFAULT),
	('salasana','Testiasiakas7','Testikatu 7, 00001 TESTIKAUPUNKI','testi@testi7.fi','Yhteys Henkilo','000-735717', DEFAULT),
	('salasana','Testiasiakas8','Testikatu 8, 00001 TESTIKAUPUNKI','testi@testi8.fi','Yhteys Henkilo','000-735718',200000),
	('salasana','Testiasiakas9','Testikatu 9, 00001 TESTIKAUPUNKI','testi@testi9.fi','Yhteys Henkilo','000-735719', DEFAULT),
	('salasana','Testiasiakas10','Testikatu 10, 00001 TESTIKAUPUNKI','testi@testi10.fi','Yhteys Henkilo','000-123456', DEFAULT),
	('salasana','Testiasiakas11','Testikatu 11, 00001 TESTIKAUPUNKI','testi@testi11.fi','Yhteys Henkilo','000-111122', DEFAULT);

insert into yllapitaja VALUES
	('admin01','salasana01'),
	('admin02','salasana02'),
	('admin03','salasana03');

insert into tuote VALUES
	(DEFAULT,'TUOTE01',DEFAULT,'Valmistaja Oy',12999.99,DEFAULT,DEFAULT),
	(DEFAULT,'TUOTE02',DEFAULT,'Valmistaja Oy',14.00,0,10),
	(DEFAULT,'TUOTE03','tuotekuvaus','Valmistaja Oy',1.39,0,50),
	(DEFAULT,'TUOTE04','harvinaisen pitka kuvaus tassa','Valmistaja Oy',399.00,0,5),
	(DEFAULT,'TUOTE05','tuotekuvaus','Valmistaja Oy',14.10,0,50),
	(DEFAULT,'TUOTE06','tuotekuvaus','Valmistaja Oy',52.00,DEFAULT,DEFAULT),
	(DEFAULT,'TUOTE07','tuotekuvaus','Valmistaja Oy',10.29,291,DEFAULT),
	(DEFAULT,'TUOTE08',DEFAULT,'Valmistaja Oy',42.09,DEFAULT,DEFAULT),
	(DEFAULT,'PRODUCT1','description is descriptive','Manufacturer Corp.',110.99,10,5),
	(DEFAULT,'PRODUCT2','description is descriptive','Manufacturer Corp.',189.99,5,0),
	(DEFAULT,'PRODUCT3','description is descriptive','Manufacturer Corp.',39.99,DEFAULT,DEFAULT);

insert into tilaus (ostoviite, kokonaisarvo, asiakasnro) VALUES
	('TESTIVIITE1',122.00,1003),
	('TESTIVIITE2',399.00,1003),
	('TESTIVIITE3',14.00,1003),
	('TESTIVIITE4',14.00,1003),
	('TESTIVIITE5',14.00,1003),
	('PO0001',136.56,1008),
	('PO0002',14.00,1008),
	('PO0003',70.00,1008),
	('PO0004',1597.39,1008),
	('PO0005',14.00,1008);
	
insert into tilaus VALUES (DEFAULT,'PO0006',14.00, DEFAULT, localtimestamp, localtimestamp, localtimestamp, 1008);

insert into ostos VALUES
	(10000001,100002,1,14.00,5),
	(10000001,100006,2,52.00,1),
	(10000001,100004,3,399.00,1),
	(10000001,100002,4,14.00,6),
	(10000001,100007,5,10.29,1),
	(10000001,100006,6,52.00,1),
	(10000001,100001,7,12999.00,3),
	(10000002,100004,1,399.00,1),
	(10000003,100002,1,14.00,1),
	(10000004,100002,1,14.00,1),
	(10000005,100002,1,14.00,1),
	(10000006,100007,1,10.29,1),
	(10000006,100008,2,42.09,3),
	(10000007,100002,1,14.00,1),
	(10000008,100003,1,1.39,1),
	(10000008,100004,2,399.00,4),
	(10000008,100009,3,110.99,0),
	(10000009,100002,1,14.00,1),
	(10000010,100002,1,14.00,1),
	(10000011,100002,1,14.00,1);
	