create table annotation_history(

	id bigserial primary key not null,
	trans_code varchar(36) unique;
	userid int not null,
	ebook_id int not null,
	annotation_text text default null,
	created_time timestamp not null default current_timestamp
);


create table bookmark_book (
	id bigserial primary key not null,
	trans_code varchar(36) unique,
	userid int not null,
	ebook_id int not null,
	page_text varchar(6),
	page_index int default 0,
	note text default null,
	created_time timestamp not null default current_timestamp
);


