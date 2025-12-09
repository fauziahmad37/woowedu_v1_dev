CREATE TABLE  student_sesi  (
   ss_id serial,
   sesi_id int4,
   student_id int4,
   ss_status int2,  
  CONSTRAINT "student_sesi_pkey" PRIMARY KEY ("ss_id") 
)
;

ALTER TABLE "public"."student_sesi" 
  OWNER TO "postgres";
 
 
 
ALTER TABLE "public"."task_student"   ADD COLUMN "task_nilai" int2; 
ALTER TABLE "public"."task_student"   ADD COLUMN "task_comment_nilai" text;  