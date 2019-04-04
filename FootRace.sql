savepoint mysavepoint;

drop table payment;
drop table disability;
drop table discount;
drop table address;
drop table person;
drop table distanceName;
drop table club;
drop table membership;
drop table race;
drop table raceevent;
drop table runner;


Create table DistanceName (
Race_Type VARCHAR(30) NOT NULL,
PRIMARY KEY (Race_Type)
);

Create table DISCOUNT (
DID NUMBER(2) NOT NULL,
amount NUMBER(3) NOT NULL,
PRIMARY KEY (DID)
);

Create table DISABILITY (
HID NUMBER(2) NOT NULL,
disabilityname VARCHAR2(20) NOT NULL,
DID NUMBER(2),
PRIMARY KEY (HID),
FOREIGN KEY(DID) REFERENCES DISCOUNT(DID)
);

Create table ADDRESS (
AID NUMBER(6) NOT NULL,
street VARCHAR2(20) NOT NULL,
city VARCHAR2(20) NOT NULL,
statename VARCHAR2(20) NOT NULL,
Zip number(5) NOT NULL,
PRIMARY KEY (AID)
);

Create table PERSON (
PID NUMBER(6) NOT NULL,
firstName VARCHAR2(20) NOT NULL,
lastName VARCHAR2(20) NOT NULL,
Sex CHAR(1),
DOB DATE NOT NULL,
EMERG NUMBER(6),
AID NUMBER(6),
HID NUMBER(2),
PRIMARY KEY (PID),
FOREIGN KEY(AID) REFERENCES ADDRESS(AID),
FOREIGN KEY(HID) REFERENCES DISABILITY(HID)
);

Create table CLUB (
club_Name VARCHAR(30) NOT NULL,
AID Number(6),
FOREIGN KEY(AID) REFERENCES ADDRESS(AID),
PRIMARY KEY(club_Name)
);

Create table MEMBERSHIP (
PID number(6) NOT NULL,
club_Name VARCHAR(30) not null,
JoinDate Date,
FOREIGN KEY(PID) REFERENCES PERSON(PID),
FOREIGN KEY(club_Name) REFERENCES CLUB(Club_Name),
PRIMARY KEY(PID, club_Name)
);

Create table RACE (
Race_Name VARCHAR(30) NOT NULL,
Race_Type VARCHAR(30) NOT NULL,
AID NUMBER(6), 
FOREIGN KEY(AID) REFERENCES ADDRESS(AID),
FOREIGN KEY(Race_Type) REFERENCES DistanceName(Race_Type),
PRIMARY KEY(Race_Name)
);

create table raceevent (
eventid NUMBER(6) NOT NULL,
oid NUMBER(6),
racename VARCHAR2(30), 
clubname VARCHAR2(30), 
mymonth NUMBER(2), 
myday NUMBER(2),
myyear NUMBER(4), 
startTime TIMESTAMP(0), 
fee NUMBER(4,2),
PRIMARY KEY(eventID),
FOREIGN KEY(OID) REFERENCES Person(PID),
FOREIGN KEY(ClubName) REFERENCES Club(Club_Name),
FOREIGN KEY(Racename) REFERENCES race(Race_Name)
);

create table runner (
PID NUMBER(6) NOT NULL,
eventID NUMBER(6) NOT NULL,
RNUMBER NUMBER(4), 
FinishTime timestamp(0), 
PRIMARY KEY(PID, EventID),
FOREIGN KEY(PID) REFERENCES Person(PID),
FOREIGN KEY(EventID) REFERENCES raceevent(eventID)
);

create table Payment (
PayID NUMBER(6) NOT NULL,
DatePaid DATE NOT NULL,
Amount NUMBER(4,2), 
PRIMARY KEY(PayID)
);

INSERT INTO Payment 
(PayID, DatePaid, AMOUNT) 
select 1, TO_DATE('2017/05/23', 'YYYY/MM/DD'), 4.50 from dual
union all select 2, TO_DATE('2016/02/28', 'YYYY/MM/DD'), 25.00 from dual
union all select 3, TO_DATE('2018/12/31', 'YYYY/MM/DD'), 11.25 from dual
union all select 4, TO_DATE('2018/07/11', 'YYYY/MM/DD'), 5.00 from dual;


INSERT INTO DISCOUNT 
(DID, AMOUNT) 
select 1, 10 from dual
union all select 2, 25 from dual
union all select 3, 50 FROM dual
union all select 4, 75 FROM dual
union all select 5, 100 FROM dual;


INSERT INTO DISABILITY 
(HID, DISABILITYNAME, DID) 
select 1, 'WheelChair', 3 from dual
union all select 2, 'Blind', 4 from dual
union all select 3, '1 Prosthetic Leg', 2 from dual
union all select 4, '2 Prosthetic Legs', 3 from dual
union all select 5, 'Other', 1 from dual;


INSERT INTO ADDRESS (AID, STREET, CITY, STATENAME, ZIP) 
select 1, '98 New St.', 'Malvern', 'PA', 19355 from dual
union all select 2, '58 Princess Dr.', 'Appleton', 'WI', 54911 from dual
union all select 3, '900 NE. Clark Court', 'Austin', 'MN', 55911 FROM dual
union all select 4, '856 Princeton Ave.', 'Hillsboro', 'OR', 97124 FROM dual
union all select 5, '9793 N. Hanover Rd.', 'Bronx', 'NY', 10465 FROM dual
union all select 6, '55 Monroe Circle', 'Collierville', 'TN', 38017 FROM dual
union all select 7, '7324 Vine Court', 'La Vergne', 'TN', 37086 FROM dual
union all select 8, '916 Edgemont St.', 'Jamaica Plain', 'MA', 02130 FROM dual
union all select 9, '478 Iroquois Dr.', 'Saint Johns', 'FL', 32259 FROM dual
union all select 10, '797 Heritage St.', 'Ozone Park', 'NY', 10005 FROM dual
union all select 11, '946 Henry Smith Rd', 'Mc Lean', 'VA', 22101 FROM dual
union all select 12, '7219 Prospect Street', 'Woodstock', 'GA', 30188 FROM dual
union all select 13, '46 West Helen Lane', 'Clermont', 'FL', 34711 FROM dual
union all select 14, '9189 Sutor Ave.', 'Hopewell', 'VA', 23860 FROM dual;

INSERT INTO PERSON (PID, firstName, lastName, SEX, DOB, EMERG, AID, HID) 
select 1, 'Tyler', 'Fuller', 'M', TO_DATE('1998/10/01', 'YYYY/MM/DD'), NULL, 1, 1 from dual
union all select 2, 'Braydon', 'Krause', 'M', TO_DATE('1996/05/21', 'YYYY/MM/DD'), NULL, 2, NULL from dual
union all select 3, 'Delilah', 'Berger', 'F', TO_DATE('2000/07/19', 'YYYY/MM/DD'), NULL, 3, 2 from dual
union all select 4, 'Armani', 'Norton', 'F', TO_DATE('1994/10/05', 'YYYY/MM/DD'), NULL, 4, NULL from dual
union all select 5, 'Joey', 'Lynn', 'M', TO_DATE('1999/01/18', 'YYYY/MM/DD'), NULL, 5, NULL from dual
union all select 6, 'Zackary', 'Spears', 'M', TO_DATE('1988/03/08', 'YYYY/MM/DD'), NULL, 13, NULL from dual
union all select 7, 'Izabella', 'Jensen', 'F', TO_DATE('1981/06/14', 'YYYY/MM/DD'), 5, 14, NULL from dual;


INSERT INTO CLUB 
(club_Name, AID) 
select 'New York Sprinters', 10 from dual
union all select 'GlobeTrotters', 11 from dual
union all select 'We Got Flip-Flops', 12 from dual;

insert into membership
(PID, club_Name, JoinDate) 
select 1, 'GlobeTrotters', TO_DATE('2015/05/07', 'YYYY/MM/DD') from dual
union all select 2, 'We Got Flip-Flops', TO_DATE('2018/02/20', 'YYYY/MM/DD') from dual
union all select 3, 'We Got Flip-Flops', TO_DATE('2017/07/09', 'YYYY/MM/DD') from dual
union all select 6, 'GlobeTrotters', TO_DATE('2014/05/01', 'YYYY/MM/DD') from dual
union all select 7, 'New York Sprinters', TO_DATE('2015/08/12', 'YYYY/MM/DD') from dual
union all select 5, 'New York Sprinters', TO_DATE('2014/09/24', 'YYYY/MM/DD') from dual;

insert into distanceName (Race_Type)
select '5K' from dual
union all select 'Half Marathon' from dual
union all select 'Marathon' from dual
union all select '10K' from dual;

insert into race (Race_Name, Race_Type, AID)
select 'Happy 5K', '5K', 6 from dual
union all select 'Cancer Half Marathon', 'Half Marathon', 7 from dual
union all select 'Sad Marathon', 'Marathon', 8 from dual
union all select 'Sunny 10K', '10K', 9 from dual
union all select 'Color Run 5K', '5K', 7 from dual;

INSERT INTO raceevent 
(eventid, oid, racename, clubname, mymonth, myday, myyear, startTime, fee)
select 1, 6, 'Happy 5K', 'New York Sprinters', 05, 24, 2017, to_timestamp('01/01/2018 13:02:30','DD/MM/YYYY HH24:MI:SS'), 5.00 from dual
union all select 2, 7, 'Cancer Half Marathon', 'GlobeTrotters', 01, 01, 2018, to_timestamp('01/01/2018 13:02:30','DD/MM/YYYY HH24:MI:SS'), 15.00 from dual
union all select 3, 7, 'Sad Marathon', 'GlobeTrotters', 02, 29, 2016, to_timestamp('01/01/2018 13:02:30','DD/MM/YYYY HH24:MI:SS'), 25.00 from dual
union all select 4, 6, 'Sunny 10K', 'New York Sprinters', 06, 18, 2019, to_timestamp('01/01/2018 13:02:30','DD/MM/YYYY HH24:MI:SS'), 10.00 from dual
union all select 5, 6, 'Color Run 5K', 'We Got Flip-Flops', 07, 12, 2018, to_timestamp('01/01/2018 13:02:30','DD/MM/YYYY HH24:MI:SS'), 5.00 from dual;

insert into runner
(PID, eventID, RNUMBER, finishTime)
select 1, 1, 1157, to_timestamp('01/01/2018 13:02:30','DD/MM/YYYY HH24:MI:SS') from dual
union all select 2, 3, 1157, to_timestamp('01/01/2018 13:02:30','DD/MM/YYYY HH24:MI:SS') from dual
union all select 3, 2, 7473, to_timestamp('01/01/2018 13:02:30','DD/MM/YYYY HH24:MI:SS') from dual
union all select 4, 5, 0489, to_timestamp('01/01/2018 13:02:30','DD/MM/YYYY HH24:MI:SS') from dual
union all select 5, 4, null, null from dual;