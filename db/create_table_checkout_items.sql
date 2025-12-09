-- public.checkout_items definition

-- Drop table

-- DROP TABLE checkout_items;

CREATE TABLE checkout_items (
	id bigserial NOT NULL,
	checkout_id int8 NOT NULL,
	item_id int8 NOT NULL,
	item_name varchar(255) NOT NULL,
	item_price float8 DEFAULT 0 NOT NULL,
	item_qty float4 DEFAULT 0 NOT NULL,
	created_at timestamp DEFAULT now() NOT NULL,
	updated_at timestamp DEFAULT now() NOT NULL,
	CONSTRAINT checkout_items_pk PRIMARY KEY (id)
);


-- public.checkout_items foreign keys

ALTER TABLE public.checkout_items ADD CONSTRAINT checkout_items_checkouts_fk FOREIGN KEY (checkout_id) REFERENCES checkouts(id);
