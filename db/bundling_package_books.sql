-- public.bundling_package_books definition

-- Drop table

-- DROP TABLE bundling_package_books;

CREATE TABLE bundling_package_books (
	id serial4 NOT NULL,
	bundling_package_id int4 NULL,
	ebook_id int4 NULL,
	created_at timestamp DEFAULT now() NULL,
	updated_at timestamp DEFAULT now() NULL,
	CONSTRAINT bundling_package_books_pk PRIMARY KEY (id)
);


-- public.bundling_package_books foreign keys

ALTER TABLE public.bundling_package_books ADD CONSTRAINT bundling_package_books_bundling_packages_fk FOREIGN KEY (bundling_package_id) REFERENCES bundling_packages(id);
ALTER TABLE public.bundling_package_books ADD CONSTRAINT bundling_package_books_ebooks_fk FOREIGN KEY (ebook_id) REFERENCES ebooks(id);
