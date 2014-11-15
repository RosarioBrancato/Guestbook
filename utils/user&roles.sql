--Users
create user guest@localhost identified by "guest";
create user loggedin@localhost identified by "loggedin";
create user admin@localhost identified by "admin";

--Roles
grant select on guestbook.tbl_entry to guest@localhost;
grant select on guestbook.tbl_user to guest@localhost;

grant select, insert, update, delete on guestbook.tbl_entry to loggedin@localhost;
grant select, insert, update on guestbook.tbl_user to loggedin@localhost;
grant select, insert, update on guestbook.tbl_user_data to loggedin@localhost;

grant all on guestbook.* to admin@localhost;
