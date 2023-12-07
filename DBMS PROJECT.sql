create database college_db;
use college_db;

create table PERSON(
	PersonID int primary key,
    FirstName varchar(255),
    MiddleName varchar(255),
    LastName varchar(255),
    Gender varchar(255)
);

create table PHONE(
	PersonID int primary key,
    PhNo varchar(255),
    constraint PHONE_FK_PERSON FOREIGN KEY(PersonID) references PERSON(PersonID)
);

create table STUDENT(
	StudentID int primary key,
    PassHash varchar(255),
    PersonId int unique,
    constraint STUDENT_FK_PERSON foreign key(PersonID) references PERSON(PersonID)
);

create table DEPARTMENT(
	DeptNo int primary key,
    DeptName varchar(255) unique
);

create table INSTRUCTOR(
	InstructorID int primary key,
    PassHash varchar(255), 
    PersonID int unique,
    DeptNo int,
    constraint INSTRUCTOR_FK_PERSON foreign key(PersonID) references PERSON(PersonID)
);

create table COURSE(
	CourseID varchar(255) primary key,
    CourseName varchar(255), 
    DeptNo int,
    InstructorID int,
	ClassesTaken int,
    constraint COURSE_FK_DEPARTMENT foreign key(DeptNo) references DEPARTMENT(DeptNo),
    constraint COURSE_FK_INSTRUCTOR foreign key(InstructorID) references INSTRUCTOR(InstructorID)
);

create table UNDERTAKES(
	StudentID int,
    CourseID varchar(255) ,
    Attendance int,
    InternalMarks int, 
    PaperMarks int,
    primary key(StudentID, CourseID),
    constraint UNDERTAKES_FK_COURSE foreign key(CourseID) references COURSE(CourseID),
    constraint UNDERTAKES_FK_STUDENT foreign key(StudentID) references STUDENT(StudentID)
);

create table HEAD(
	DeptNo int primary key,
    Head int unique
);

select * from student;
LOAD DATA LOCAL INFILE "C:/Users/HP/Downloads/dbms/PERSON.csv" INTO TABLE PERSON FIELDS TERMINATED BY ',' ENCLOSED BY '"' IGNORE 1 ROWS;
LOAD DATA LOCAL INFILE "C:/Users/HP/Downloads/dbms/PHONE.csv" INTO TABLE PHONE FIELDS TERMINATED BY ',' ENCLOSED BY '"' IGNORE 1 ROWS;
LOAD DATA LOCAL INFILE "C:/Users/HP/Downloads/dbms/STUDENT.csv" INTO TABLE STUDENT FIELDS TERMINATED BY ',' ENCLOSED BY '"' IGNORE 1 ROWS;
LOAD DATA LOCAL INFILE "C:/Users/HP/Downloads/dbms/DEPARTMENT.csv" INTO TABLE DEPARTMENT FIELDS TERMINATED BY ',' ENCLOSED BY '"' IGNORE 1 ROWS;
LOAD DATA LOCAL INFILE "C:/Users/HP/Downloads/dbms/INSTRUCTOR.csv" INTO TABLE INSTRUCTOR FIELDS TERMINATED BY ',' ENCLOSED BY '"' IGNORE 1 ROWS;
LOAD DATA LOCAL INFILE "C:/Users/HP/Downloads/dbms/COURSE.csv" INTO TABLE COURSE FIELDS TERMINATED BY ',' ENCLOSED BY '"' IGNORE 1 ROWS;
LOAD DATA LOCAL INFILE "C:/Users/HP/Downloads/dbms/UNDERTAKES.csv" INTO TABLE UNDERTAKES FIELDS TERMINATED BY ',' ENCLOSED BY '"' IGNORE 1 ROWS;
LOAD DATA LOCAL INFILE "C:/Users/HP/Downloads/dbms/HEAD.csv" INTO TABLE HEAD FIELDS TERMINATED BY ',' ENCLOSED BY '"' IGNORE 1 ROWS;



