ALTER TABLE public.exam_answer ADD result_answer smallint NULL;
COMMENT ON COLUMN public.exam_answer.result_answer IS '0: salah, 1: benar';
