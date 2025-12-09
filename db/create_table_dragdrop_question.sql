CREATE TABLE soal_dragdrop_question
(
  id bigserial NOT NULL,
  soal_id bigint NOT NULL,
  answer_correct text NOT NULL,
  answer_false text,
  urutan integer,
  words_count integer DEFAULT 0,
  created_at timestamp without time zone NOT NULL DEFAULT now(),
  CONSTRAINT soal_dragdrop_question_pkey PRIMARY KEY (id),
  CONSTRAINT soal_dragdrop_fk FOREIGN KEY (soal_id)
      REFERENCES soal (soal_id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE
);