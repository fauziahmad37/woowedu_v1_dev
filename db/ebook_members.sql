-- public.ebook_members definition

-- Drop table

-- DROP TABLE public.ebook_members;

CREATE TABLE public.ebook_members (
	id serial4 NOT NULL,
	ebook_id int4 NULL,
	user_id int4 NULL,
	read_status int2 DEFAULT 0 NULL,
	start_activation timestamp NULL,
	end_activation timestamp NULL,
	created_at timestamp DEFAULT now() NULL,
	updated_at timestamp NULL,
	CONSTRAINT ebook_members_pk PRIMARY KEY (id),
	CONSTRAINT ebook_members_ebooks_fk FOREIGN KEY (ebook_id) REFERENCES public.ebooks(id),
	CONSTRAINT ebook_members_users_fk FOREIGN KEY (user_id) REFERENCES public.users(userid)
);
