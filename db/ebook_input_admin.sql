ALTER TABLE ebooks add column price int;
ALTER TABLE ebooks add column cover_1 text,
		   add column cover_2 text,
		   add column cover_3 text,
		   add column cover_4 text,
		   add column active_status int default 1;

ALTER TABLE ebooks add column start_active timestamp null default null;


DROP TABLE IF EXISTS ebooks_categories;
DROP TABLE IF EXISTS ebooks_subscribe_type;
CREATE TYPE subscribe_periode AS ENUM ('1_month', '3_month', '6_month', '12_month', 'full_access'); 


CREATE TABLE ebooks_categories (
	id bigserial primary key not null,
	ebook_id int,
	category_id int,
	created_at timestamp default current_timestamp,
	updated_at timestamp default current_timestamp,
	constraint fk_ebooks foreign key(ebook_id) references ebooks(id) on update cascade on delete cascade,
	constraint fk_categories foreign key (category_id) references categories(id) on update cascade on delete cascade
);

CREATE TABLE ebooks_subscribe_type (
	id bigserial primary key not null,
	ebook_id int,
	"name" varchar(200),
	subscribe_periode subscribe_periode DEFAULT 'full_access',
	price int,
	created_at timestamp default current_timestamp,
	updated_at timestamp default current_timestamp,
	constraint fk_ebooks foreign key(ebook_id) references ebooks(id) on update cascade on delete cascade
);

CREATE TABLE subscribe_type_benefit (
	id bigserial primary key not null,
	subscribe_type int,
	benefit text,
	created_at timestamp default current_timestamp,
	updated_at timestamp default current_timestamp,
	constraint fk_subscription foreign key(subscribe_type) references ebooks_subscribe_type(id) on update cascade on delete cascade
);
