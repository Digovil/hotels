/*
 Navicat Premium Data Transfer

 Source Server         : Postgresql
 Source Server Type    : PostgreSQL
 Source Server Version : 120017 (120017)
 Source Host           : localhost:5432
 Source Catalog        : DecameronDB
 Source Schema         : public

 Target Server Type    : PostgreSQL
 Target Server Version : 120000
 File Encoding         : 65001

 Date: 06/01/2025 02:25:24
*/


-- ----------------------------
-- Sequence structure for migrations_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "migrations_id_seq";
CREATE SEQUENCE "migrations_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for personal_access_tokens_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "personal_access_tokens_id_seq";
CREATE SEQUENCE "personal_access_tokens_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Table structure for accommodation_types
-- ----------------------------
DROP TABLE IF EXISTS "accommodation_types";
CREATE TABLE "accommodation_types" (
  "accommodation_types_id" uuid NOT NULL,
  "name" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "created_at" timestamp(0) NOT NULL DEFAULT '2025-01-06 07:21:36'::timestamp without time zone,
  "updated_at" timestamp(0) NOT NULL DEFAULT '2025-01-06 07:21:36'::timestamp without time zone
)
;

-- ----------------------------
-- Records of accommodation_types
-- ----------------------------
BEGIN;
INSERT INTO "accommodation_types" ("accommodation_types_id", "name", "created_at", "updated_at") VALUES ('8b5dffb1-fcfb-4228-b976-f2404a00e046', 'SENCILLA', '2025-01-06 07:21:36', '2025-01-06 07:21:36');
INSERT INTO "accommodation_types" ("accommodation_types_id", "name", "created_at", "updated_at") VALUES ('f1036d43-18fc-4ea7-bb36-d996106475c7', 'DOBLE', '2025-01-06 07:21:36', '2025-01-06 07:21:36');
INSERT INTO "accommodation_types" ("accommodation_types_id", "name", "created_at", "updated_at") VALUES ('78afd740-6821-4faf-8404-14510ee93894', 'TRIPLE', '2025-01-06 07:21:36', '2025-01-06 07:21:36');
INSERT INTO "accommodation_types" ("accommodation_types_id", "name", "created_at", "updated_at") VALUES ('31801966-d6c7-4ac7-ac4a-df490bbd05f8', 'CUADRUPLE', '2025-01-06 07:21:36', '2025-01-06 07:21:36');
COMMIT;

-- ----------------------------
-- Table structure for hotel_rooms
-- ----------------------------
DROP TABLE IF EXISTS "hotel_rooms";
CREATE TABLE "hotel_rooms" (
  "hotel_rooms_id" uuid NOT NULL,
  "hotel_id" uuid NOT NULL,
  "room_type_id" uuid NOT NULL,
  "accommodation_type_id" uuid NOT NULL,
  "quantity" int4 NOT NULL,
  "created_at" timestamp(0) NOT NULL DEFAULT '2025-01-06 07:21:36'::timestamp without time zone,
  "updated_at" timestamp(0) NOT NULL DEFAULT '2025-01-06 07:21:36'::timestamp without time zone
)
;

-- ----------------------------
-- Records of hotel_rooms
-- ----------------------------
BEGIN;
INSERT INTO "hotel_rooms" ("hotel_rooms_id", "hotel_id", "room_type_id", "accommodation_type_id", "quantity", "created_at", "updated_at") VALUES ('474d5513-0bea-4b99-baaf-fc1f0927508e', '0f612cb0-60b1-494d-ab67-9e12e5ead1b5', 'b9692280-0a25-404b-b9a8-72f64c0529e1', '78afd740-6821-4faf-8404-14510ee93894', 10, '2025-01-06 07:21:36', '2025-01-06 07:21:36');
INSERT INTO "hotel_rooms" ("hotel_rooms_id", "hotel_id", "room_type_id", "accommodation_type_id", "quantity", "created_at", "updated_at") VALUES ('530ac7e2-73b1-48ae-9926-d273bf201d40', '0f612cb0-60b1-494d-ab67-9e12e5ead1b5', 'b9692280-0a25-404b-b9a8-72f64c0529e1', '31801966-d6c7-4ac7-ac4a-df490bbd05f8', 13, '2025-01-06 07:21:36', '2025-01-06 07:21:36');
COMMIT;

-- ----------------------------
-- Table structure for hotels
-- ----------------------------
DROP TABLE IF EXISTS "hotels";
CREATE TABLE "hotels" (
  "hotels_id" uuid NOT NULL,
  "name" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "address" varchar(200) COLLATE "pg_catalog"."default" NOT NULL,
  "city" varchar(100) COLLATE "pg_catalog"."default" NOT NULL,
  "tax_id" varchar(20) COLLATE "pg_catalog"."default" NOT NULL,
  "total_rooms" int4 NOT NULL,
  "created_at" timestamp(0) NOT NULL DEFAULT '2025-01-06 07:21:36'::timestamp without time zone,
  "updated_at" timestamp(0) NOT NULL DEFAULT '2025-01-06 07:21:36'::timestamp without time zone
)
;

-- ----------------------------
-- Records of hotels
-- ----------------------------
BEGIN;
INSERT INTO "hotels" ("hotels_id", "name", "address", "city", "tax_id", "total_rooms", "created_at", "updated_at") VALUES ('0f612cb0-60b1-494d-ab67-9e12e5ead1b5', 'DECAMERON Hillsport', '49207 Kuvalis Springs', 'Daltonhaven', '319439822-3', 78, '2025-01-06 07:21:36', '2025-01-06 07:21:36');
COMMIT;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS "migrations";
CREATE TABLE "migrations" (
  "id" int4 NOT NULL DEFAULT nextval('migrations_id_seq'::regclass),
  "migration" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "batch" int4 NOT NULL
)
;

-- ----------------------------
-- Records of migrations
-- ----------------------------
BEGIN;
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (1, '2019_12_14_000001_create_personal_access_tokens_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (2, '2025_01_05_080735_create_room_types_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (3, '2025_01_05_080737_create_accommodation_types_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (4, '2025_01_05_080737_create_hotels_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (5, '2025_01_05_080738_create_room_accommodation_rules_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (6, '2025_01_05_080739_create_hotel_rooms_table', 1);
COMMIT;

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS "personal_access_tokens";
CREATE TABLE "personal_access_tokens" (
  "id" int8 NOT NULL DEFAULT nextval('personal_access_tokens_id_seq'::regclass),
  "tokenable_type" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "tokenable_id" int8 NOT NULL,
  "name" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "token" varchar(64) COLLATE "pg_catalog"."default" NOT NULL,
  "abilities" text COLLATE "pg_catalog"."default",
  "last_used_at" timestamp(0),
  "expires_at" timestamp(0),
  "created_at" timestamp(0),
  "updated_at" timestamp(0)
)
;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for room_accommodation_rules
-- ----------------------------
DROP TABLE IF EXISTS "room_accommodation_rules";
CREATE TABLE "room_accommodation_rules" (
  "room_accommodation_rules_id" uuid NOT NULL,
  "room_type_id" uuid NOT NULL,
  "accommodation_type_id" uuid NOT NULL,
  "created_at" timestamp(0) NOT NULL DEFAULT '2025-01-06 07:21:36'::timestamp without time zone,
  "updated_at" timestamp(0) NOT NULL DEFAULT '2025-01-06 07:21:36'::timestamp without time zone
)
;

-- ----------------------------
-- Records of room_accommodation_rules
-- ----------------------------
BEGIN;
INSERT INTO "room_accommodation_rules" ("room_accommodation_rules_id", "room_type_id", "accommodation_type_id", "created_at", "updated_at") VALUES ('92bbadd0-c580-41c5-b71e-1de3033874d5', 'aa393e43-2230-4d3c-88ee-d3cfdf2cdcef', '8b5dffb1-fcfb-4228-b976-f2404a00e046', '2025-01-06 07:21:36', '2025-01-06 07:21:36');
INSERT INTO "room_accommodation_rules" ("room_accommodation_rules_id", "room_type_id", "accommodation_type_id", "created_at", "updated_at") VALUES ('89861dfb-b998-45f4-a3f9-5ca379041b09', 'aa393e43-2230-4d3c-88ee-d3cfdf2cdcef', 'f1036d43-18fc-4ea7-bb36-d996106475c7', '2025-01-06 07:21:36', '2025-01-06 07:21:36');
INSERT INTO "room_accommodation_rules" ("room_accommodation_rules_id", "room_type_id", "accommodation_type_id", "created_at", "updated_at") VALUES ('3ff65f7e-a797-49b9-a9c3-02974467efcc', 'b9692280-0a25-404b-b9a8-72f64c0529e1', '78afd740-6821-4faf-8404-14510ee93894', '2025-01-06 07:21:36', '2025-01-06 07:21:36');
INSERT INTO "room_accommodation_rules" ("room_accommodation_rules_id", "room_type_id", "accommodation_type_id", "created_at", "updated_at") VALUES ('8b8d15f9-34a1-4980-bd3c-a993d834ed0a', 'b9692280-0a25-404b-b9a8-72f64c0529e1', '31801966-d6c7-4ac7-ac4a-df490bbd05f8', '2025-01-06 07:21:36', '2025-01-06 07:21:36');
INSERT INTO "room_accommodation_rules" ("room_accommodation_rules_id", "room_type_id", "accommodation_type_id", "created_at", "updated_at") VALUES ('18b7c251-c06d-4356-89d1-2084a5b76821', 'afac7abd-8195-4d8c-b7f2-8bed8147fd34', '8b5dffb1-fcfb-4228-b976-f2404a00e046', '2025-01-06 07:21:36', '2025-01-06 07:21:36');
INSERT INTO "room_accommodation_rules" ("room_accommodation_rules_id", "room_type_id", "accommodation_type_id", "created_at", "updated_at") VALUES ('529afc86-4b56-4049-90f1-74551cf8d1ab', 'afac7abd-8195-4d8c-b7f2-8bed8147fd34', 'f1036d43-18fc-4ea7-bb36-d996106475c7', '2025-01-06 07:21:36', '2025-01-06 07:21:36');
INSERT INTO "room_accommodation_rules" ("room_accommodation_rules_id", "room_type_id", "accommodation_type_id", "created_at", "updated_at") VALUES ('7eb938f4-acf7-4ca2-ae35-0940bdfac9ef', 'afac7abd-8195-4d8c-b7f2-8bed8147fd34', '78afd740-6821-4faf-8404-14510ee93894', '2025-01-06 07:21:36', '2025-01-06 07:21:36');
COMMIT;

-- ----------------------------
-- Table structure for room_types
-- ----------------------------
DROP TABLE IF EXISTS "room_types";
CREATE TABLE "room_types" (
  "room_types_id" uuid NOT NULL,
  "name" varchar(50) COLLATE "pg_catalog"."default" NOT NULL,
  "created_at" timestamp(0) NOT NULL DEFAULT '2025-01-06 07:21:36'::timestamp without time zone,
  "updated_at" timestamp(0) NOT NULL DEFAULT '2025-01-06 07:21:36'::timestamp without time zone
)
;

-- ----------------------------
-- Records of room_types
-- ----------------------------
BEGIN;
INSERT INTO "room_types" ("room_types_id", "name", "created_at", "updated_at") VALUES ('aa393e43-2230-4d3c-88ee-d3cfdf2cdcef', 'ESTANDAR', '2025-01-06 07:21:36', '2025-01-06 07:21:36');
INSERT INTO "room_types" ("room_types_id", "name", "created_at", "updated_at") VALUES ('b9692280-0a25-404b-b9a8-72f64c0529e1', 'JUNIOR', '2025-01-06 07:21:36', '2025-01-06 07:21:36');
INSERT INTO "room_types" ("room_types_id", "name", "created_at", "updated_at") VALUES ('afac7abd-8195-4d8c-b7f2-8bed8147fd34', 'SUITE', '2025-01-06 07:21:36', '2025-01-06 07:21:36');
COMMIT;

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "migrations_id_seq"
OWNED BY "migrations"."id";
SELECT setval('"migrations_id_seq"', 6, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "personal_access_tokens_id_seq"
OWNED BY "personal_access_tokens"."id";
SELECT setval('"personal_access_tokens_id_seq"', 1, false);

-- ----------------------------
-- Uniques structure for table accommodation_types
-- ----------------------------
ALTER TABLE "accommodation_types" ADD CONSTRAINT "accommodation_types_name_unique" UNIQUE ("name");

-- ----------------------------
-- Primary Key structure for table accommodation_types
-- ----------------------------
ALTER TABLE "accommodation_types" ADD CONSTRAINT "accommodation_types_pkey" PRIMARY KEY ("accommodation_types_id");

-- ----------------------------
-- Checks structure for table hotel_rooms
-- ----------------------------
ALTER TABLE "hotel_rooms" ADD CONSTRAINT "check_quantity_positive" CHECK (quantity > 0);

-- ----------------------------
-- Primary Key structure for table hotel_rooms
-- ----------------------------
ALTER TABLE "hotel_rooms" ADD CONSTRAINT "hotel_rooms_pkey" PRIMARY KEY ("hotel_rooms_id");

-- ----------------------------
-- Uniques structure for table hotels
-- ----------------------------
ALTER TABLE "hotels" ADD CONSTRAINT "hotels_name_unique" UNIQUE ("name");
ALTER TABLE "hotels" ADD CONSTRAINT "hotels_tax_id_unique" UNIQUE ("tax_id");

-- ----------------------------
-- Checks structure for table hotels
-- ----------------------------
ALTER TABLE "hotels" ADD CONSTRAINT "check_total_rooms_positive" CHECK (total_rooms > 0);

-- ----------------------------
-- Primary Key structure for table hotels
-- ----------------------------
ALTER TABLE "hotels" ADD CONSTRAINT "hotels_pkey" PRIMARY KEY ("hotels_id");

-- ----------------------------
-- Primary Key structure for table migrations
-- ----------------------------
ALTER TABLE "migrations" ADD CONSTRAINT "migrations_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Indexes structure for table personal_access_tokens
-- ----------------------------
CREATE INDEX "personal_access_tokens_tokenable_type_tokenable_id_index" ON "personal_access_tokens" USING btree (
  "tokenable_type" COLLATE "pg_catalog"."default" "pg_catalog"."text_ops" ASC NULLS LAST,
  "tokenable_id" "pg_catalog"."int8_ops" ASC NULLS LAST
);

-- ----------------------------
-- Uniques structure for table personal_access_tokens
-- ----------------------------
ALTER TABLE "personal_access_tokens" ADD CONSTRAINT "personal_access_tokens_token_unique" UNIQUE ("token");

-- ----------------------------
-- Primary Key structure for table personal_access_tokens
-- ----------------------------
ALTER TABLE "personal_access_tokens" ADD CONSTRAINT "personal_access_tokens_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Uniques structure for table room_accommodation_rules
-- ----------------------------
ALTER TABLE "room_accommodation_rules" ADD CONSTRAINT "room_accommodation_rules_room_type_id_accommodation_type_id_uni" UNIQUE ("room_type_id", "accommodation_type_id");

-- ----------------------------
-- Primary Key structure for table room_accommodation_rules
-- ----------------------------
ALTER TABLE "room_accommodation_rules" ADD CONSTRAINT "room_accommodation_rules_pkey" PRIMARY KEY ("room_accommodation_rules_id");

-- ----------------------------
-- Uniques structure for table room_types
-- ----------------------------
ALTER TABLE "room_types" ADD CONSTRAINT "room_types_name_unique" UNIQUE ("name");

-- ----------------------------
-- Primary Key structure for table room_types
-- ----------------------------
ALTER TABLE "room_types" ADD CONSTRAINT "room_types_pkey" PRIMARY KEY ("room_types_id");

-- ----------------------------
-- Foreign Keys structure for table hotel_rooms
-- ----------------------------
ALTER TABLE "hotel_rooms" ADD CONSTRAINT "hotel_rooms_accommodation_type_id_foreign" FOREIGN KEY ("accommodation_type_id") REFERENCES "accommodation_types" ("accommodation_types_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "hotel_rooms" ADD CONSTRAINT "hotel_rooms_hotel_id_foreign" FOREIGN KEY ("hotel_id") REFERENCES "hotels" ("hotels_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "hotel_rooms" ADD CONSTRAINT "hotel_rooms_room_type_id_foreign" FOREIGN KEY ("room_type_id") REFERENCES "room_types" ("room_types_id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table room_accommodation_rules
-- ----------------------------
ALTER TABLE "room_accommodation_rules" ADD CONSTRAINT "room_accommodation_rules_accommodation_type_id_foreign" FOREIGN KEY ("accommodation_type_id") REFERENCES "accommodation_types" ("accommodation_types_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "room_accommodation_rules" ADD CONSTRAINT "room_accommodation_rules_room_type_id_foreign" FOREIGN KEY ("room_type_id") REFERENCES "room_types" ("room_types_id") ON DELETE NO ACTION ON UPDATE NO ACTION;
