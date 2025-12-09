-- public.transaction_payments definition

-- Drop table

-- DROP TABLE transaction_payments;

CREATE TABLE transaction_payments (
	id bigserial NOT NULL,
	field_table varchar(50) NOT NULL,
	field_id varchar(100) NOT NULL,
	transaction_number varchar(100) NOT NULL,
	total_payment float8 DEFAULT 0 NOT NULL,
	status varchar(50) NULL,
	payment_method varchar(100) NULL,
	created_at timestamp DEFAULT now() NOT NULL,
	updated_at timestamp DEFAULT now() NOT NULL,
	transaction_time timestamp NULL,
	expiry_time timestamp NULL,
	payment_link varchar NULL,
	settlement_time timestamp NULL,
	CONSTRAINT transaction_payments_pk PRIMARY KEY (id)
);
