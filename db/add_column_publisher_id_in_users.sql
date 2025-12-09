ALTER TABLE public.users ADD publisher_id int4 NULL;
ALTER TABLE public.users ADD CONSTRAINT users_publishers_fk FOREIGN KEY (publisher_id) REFERENCES publishers(id);

ALTER TABLE public.bundling_packages ADD start_date timestamp NULL;
ALTER TABLE public.bundling_packages ADD end_date timestamp NULL;
ALTER TABLE public.bundling_packages ADD stock int2 NULL;

ALTER TABLE public.bundling_package_books ADD normal_price float4 DEFAULT 0 NULL;
ALTER TABLE public.bundling_package_books ADD discount_price float4 DEFAULT 0 NULL;
