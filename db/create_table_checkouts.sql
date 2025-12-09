-- public.checkouts definition

-- Drop table

-- DROP TABLE checkouts;

CREATE TABLE checkouts (
	id bigserial NOT NULL,
	user_id int4 NOT NULL,
	tax float4 DEFAULT 0 NOT NULL,
	discount float4 DEFAULT 0 NOT NULL,
	total_price float8 DEFAULT 0 NOT NULL,
	voucher_id int4 NULL,
	biaya_admin int4 DEFAULT 0 NOT NULL,
	gross_amount float8 DEFAULT 0 NOT NULL,
	snap_token varchar(255) NULL,
	status varchar(100) NULL,
	status_token varchar(100) NULL,
	created_at timestamp DEFAULT now() NOT NULL,
	updated_at timestamp DEFAULT now() NOT NULL,
	CONSTRAINT checkouts_pk PRIMARY KEY (id)
);


-- public.checkouts foreign keys

ALTER TABLE public.checkouts ADD CONSTRAINT checkouts_users_fk FOREIGN KEY (user_id) REFERENCES users(userid);
