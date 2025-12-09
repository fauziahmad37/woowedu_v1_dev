ALTER TABLE public.soal ADD created_at timestamp DEFAULT now() NULL;
ALTER TABLE public.soal ADD "time" time NULL;
ALTER TABLE public.soal ADD point float4 DEFAULT 0 NULL;

ALTER TABLE public.soal ADD response_correct_answer_file varchar(255) NULL;

ALTER TABLE public.soal ADD response_correct_answer varchar NULL;

ALTER TABLE public.soal ADD response_wrong_answer_file varchar(255) NULL;

ALTER TABLE public.soal ADD response_wrong_answer varchar NULL;

ALTER TABLE public.soal ADD alternative_answer varchar NULL;
ALTER TABLE public.soal ADD variation_answer varchar(255) NULL;
