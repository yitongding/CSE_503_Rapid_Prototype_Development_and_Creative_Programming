create table grades (
pk_grade_id int unsigned not null auto_increment,
student_id mediumint unsigned not null,
grade decimal(5,2),
school_code enum('L','B','A','F','E','T','I','W','S','U','M') not null,
dept_id tinyint(3) unsigned not null,
course_code varchar(5) not null,
primary key (pk_grade_id),
foreign key (student_id) references students(id),
foreign key (school_code) references departments(school_code),
foreign key (dept_id) references departments(dept_id),
foreign key (course_code) references courses(course_code)
);


select * from departments where abbreviation='CSE';

insert into courses (school_code, dept_id, course_code, name) values
( 'E', 81, '330S', 'Rapid prototype Development');


insert into students (id, first_name, last_name, email_address) values 
(88, 'Ben', 'Harper', 'bharper@ffym.com'),
(202, 'Matt', 'Freeman', 'mfreeman@kickinbassist.net'),
(115, 'Marc', 'Roberge', 'mroberge@ofarevolution.us');

insert into grades (student_id, grade, school_code, dept_id, course_code) values 
(88, 35.5, 'E', 81, '330S'),
(202, 100, 'E', 81, '330S'),
(115, 75, 'E', 81, '330S'),
(88, 0, 'E', 81, '436S'),
(202, 90.5, 'E', 81, '436S'),
(115, 37, 'E', 81, '436S'),
(88, 95, 'E', 81, '5047'),
(202, 94.8, 'E', 81, '5047'),
(115, 45.5, 'E', 81, '5047');

select * from grades;

select * from courses where school_code='L';

select first_name, last_name, grades.grade
from students join grades on
(students.id=grades.student_id)
where grades.course_code='330S';

select 
first_name, 
last_name, 
avg(grades.grade) as avg_grade, 
email_address
from students join grades on
(students.id=grades.student_id)
group by id
having
avg_grade < 50;

select 
id,
email_address,
avg(grades.grade) as avg_grade
from students join grades on
(students.id=grades.student_id)
where
first_name='Jack' 
and
last_name='Johnson';