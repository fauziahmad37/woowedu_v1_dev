ALTER TABLE public.ebooks_subscribe_type ADD "name" varchar(200) NULL;
ALTER TABLE public.ebooks_subscribe_type ADD CONSTRAINT ebooks_subscribe_type_ebooks_fk FOREIGN KEY (ebook_id) REFERENCES ebooks(id);
