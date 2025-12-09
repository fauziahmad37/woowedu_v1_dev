DROP TABLE IF EXISTS ratings;
CREATE TABLE ratings
(
  id bigserial NOT NULL,
  created_at timestamp without time zone DEFAULT now(),
  updated_at timestamp without time zone DEFAULT now(),
  book_id integer NOT NULL,
  member_id integer NOT NULL,
  member_role integer DEFAULT 4,
  rate integer DEFAULT 0,
  komentar text,
  CONSTRAINT ratings_pkey PRIMARY KEY (id),
  CONSTRAINT ratings_book_id_member_id_key UNIQUE (book_id, member_id)
)
