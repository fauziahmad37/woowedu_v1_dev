create type is_liked as enum ('Y', 'N');
create table wishlists (
	id serial primary key not null,
	user_id int,
	ebook_id int default NULL,
	liked is_liked default 'Y',
	created_at timestamp default current_timestamp,
	updated_at timestamp default current_timestamp,
	constraint fk_user_wishlist foreign key (user_id) references users(userid) on update cascade on delete cascade,
	constraint fk_ebook_wishlist foreign key (ebook_id) references ebooks(id) on update cascade on delete set null
);

create table shopping_cart (
	id varchar(50) unique primary key default uuid_generate_v4(),
	user_id int,
	ebook_id int,
	created_at timestamp default current_timestamp,
	updated_at timestamp default current_timestamp,
	constraint fk_user_wishlist foreign key (user_id) references users(userid) on update cascade on delete cascade,
	constraint fk_ebook_wishlist foreign key (ebook_id) references ebooks(id) on update cascade on delete set null
);

alter table wishlists rename ebook_id to item_id; 
alter table shopping_cart rename ebook_id to item_id; 
alter table wishlists add column item_type varchar(100) default 'ebook';
alter table shopping_cart add column item_type varchar(100) default 'ebook';

-- rename column ebook_id to item_id shopping_cart
ALTER TABLE public.shopping_cart RENAME COLUMN ebook_id TO item_id;

-- add column item_type to shopping_cart
ALTER TABLE public.shopping_cart ADD item_type varchar(100) DEFAULT 'ebook'::character varying NULL;
