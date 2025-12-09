ALTER TABLE public.transaction_payments ADD payment_link varchar NULL;

ALTER TABLE public.transaction_payments ADD settlement_time timestamp NULL;

ALTER TABLE public.transaction_payments ADD buyer_name varchar(100) NULL;
