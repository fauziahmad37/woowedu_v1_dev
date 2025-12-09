CREATE TABLE soal_pairing_question
(
  id bigserial NOT NULL,
  soal_id bigint NOT NULL,
  has_image smallint DEFAULT 0,
  answer_key text,
  answer_value text,
  urutan integer,
  created_at timestamp without time zone DEFAULT now(),
  CONSTRAINT soal_pairing_question_pkey PRIMARY KEY (id),
  CONSTRAINT soal_id_pairing_fk FOREIGN KEY (soal_id)
      REFERENCES soal (soal_id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
);