-- public.bundling_packages definition

-- Drop table

-- DROP TABLE bundling_packages;

CREATE TABLE bundling_packages (
	id serial4 NOT NULL,
	package_name varchar(255) NULL,
	publisher_id int4 NULL,
	price int4 NULL,
	description varchar(1000) NULL,
	status int2 DEFAULT 1 NULL,
	created_at timestamp DEFAULT now() NULL,
	updated_at timestamp DEFAULT now() NULL,
	package_image varchar(255) NULL,
	CONSTRAINT bundling_packages_pk PRIMARY KEY (id)
);


-- public.bundling_packages foreign keys

ALTER TABLE public.bundling_packages ADD CONSTRAINT bundling_packages_publishers_fk FOREIGN KEY (publisher_id) REFERENCES publishers(id);
