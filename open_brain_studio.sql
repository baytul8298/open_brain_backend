--
-- PostgreSQL database dump
--

\restrict dvPa9aXUYcpbHuvBACmKGfZVll11qYcGGfhgY8LRtnJgJhaMdKS3Oa4WhoIM1aK

-- Dumped from database version 18.3
-- Dumped by pg_dump version 18.3

-- Started on 2026-10-25 00:58:25

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 15 (class 2615 OID 32442)
-- Name: analytics; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA analytics;


--
-- TOC entry 16 (class 2615 OID 32443)
-- Name: audit; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA audit;


--
-- TOC entry 10 (class 2615 OID 32437)
-- Name: auth; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA auth;


--
-- TOC entry 13 (class 2615 OID 32440)
-- Name: commerce; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA commerce;


--
-- TOC entry 14 (class 2615 OID 32441)
-- Name: comms; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA comms;


--
-- TOC entry 11 (class 2615 OID 32438)
-- Name: core; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA core;


--
-- TOC entry 12 (class 2615 OID 32439)
-- Name: learn; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA learn;


--
-- TOC entry 5 (class 3079 OID 31827)
-- Name: btree_gin; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS btree_gin WITH SCHEMA public;


--
-- TOC entry 4 (class 3079 OID 31746)
-- Name: pg_trgm; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS pg_trgm WITH SCHEMA public;


--
-- TOC entry 3 (class 3079 OID 31708)
-- Name: pgcrypto; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS pgcrypto WITH SCHEMA public;


--
-- TOC entry 2 (class 3079 OID 31697)
-- Name: uuid-ossp; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS "uuid-ossp" WITH SCHEMA public;


--
-- TOC entry 1153 (class 1247 OID 32330)
-- Name: assignment_status; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.assignment_status AS ENUM (
    'draft',
    'active',
    'closed',
    'archived'
);


--
-- TOC entry 1174 (class 1247 OID 32410)
-- Name: board_type; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.board_type AS ENUM (
    'nctb_national',
    'dhaka',
    'rajshahi',
    'chittagong',
    'sylhet',
    'barisal',
    'comilla',
    'jessore',
    'dinajpur',
    'mymensingh',
    'madrasah',
    'technical',
    'general'
);


--
-- TOC entry 1171 (class 1247 OID 32400)
-- Name: certificate_status; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.certificate_status AS ENUM (
    'not_earned',
    'earned',
    'issued',
    'revoked'
);


--
-- TOC entry 1141 (class 1247 OID 32286)
-- Name: course_status; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.course_status AS ENUM (
    'draft',
    'pending_review',
    'live',
    'archived',
    'suspended'
);


--
-- TOC entry 1165 (class 1247 OID 32372)
-- Name: day_of_week; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.day_of_week AS ENUM (
    'saturday',
    'sunday',
    'monday',
    'tuesday',
    'wednesday',
    'thursday',
    'friday'
);


--
-- TOC entry 1147 (class 1247 OID 32306)
-- Name: enrollment_status; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.enrollment_status AS ENUM (
    'active',
    'paused',
    'cancelled',
    'expired',
    'pending'
);


--
-- TOC entry 1168 (class 1247 OID 32388)
-- Name: media_type; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.media_type AS ENUM (
    'image',
    'video',
    'document',
    'audio',
    'archive'
);


--
-- TOC entry 1162 (class 1247 OID 32364)
-- Name: message_status; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.message_status AS ENUM (
    'sent',
    'delivered',
    'read'
);


--
-- TOC entry 1150 (class 1247 OID 32318)
-- Name: payment_status; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.payment_status AS ENUM (
    'pending',
    'completed',
    'failed',
    'refunded',
    'disputed'
);


--
-- TOC entry 1144 (class 1247 OID 32298)
-- Name: pricing_model; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.pricing_model AS ENUM (
    'monthly_subscription',
    'one_time',
    'free'
);


--
-- TOC entry 1159 (class 1247 OID 32352)
-- Name: session_status; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.session_status AS ENUM (
    'scheduled',
    'live',
    'completed',
    'cancelled',
    'rescheduled'
);


--
-- TOC entry 1156 (class 1247 OID 32340)
-- Name: submission_status; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.submission_status AS ENUM (
    'not_submitted',
    'submitted',
    'late',
    'graded',
    'returned'
);


--
-- TOC entry 1135 (class 1247 OID 32264)
-- Name: user_role; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.user_role AS ENUM (
    'student',
    'teacher',
    'parent',
    'admin',
    'super_admin'
);


--
-- TOC entry 1138 (class 1247 OID 32276)
-- Name: user_status; Type: TYPE; Schema: public; Owner: -
--

CREATE TYPE public.user_status AS ENUM (
    'active',
    'inactive',
    'suspended',
    'pending_verification'
);


--
-- TOC entry 300 (class 1259 OID 33874)
-- Name: audit_logs; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.audit_logs (
    id bigint NOT NULL,
    user_id uuid,
    action character varying(255) NOT NULL,
    ip_address character varying(45),
    user_agent text,
    payload text,
    status_code smallint,
    created_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- TOC entry 299 (class 1259 OID 33873)
-- Name: audit_logs_id_seq; Type: SEQUENCE; Schema: auth; Owner: -
--

CREATE SEQUENCE auth.audit_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6155 (class 0 OID 0)
-- Dependencies: 299
-- Name: audit_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: auth; Owner: -
--

ALTER SEQUENCE auth.audit_logs_id_seq OWNED BY auth.audit_logs.id;


--
-- TOC entry 283 (class 1259 OID 33708)
-- Name: cache; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


--
-- TOC entry 284 (class 1259 OID 33718)
-- Name: cache_locks; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


--
-- TOC entry 247 (class 1259 OID 32618)
-- Name: email_verification_tokens; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.email_verification_tokens (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    user_id uuid NOT NULL,
    token_hash text NOT NULL,
    used boolean DEFAULT false NOT NULL,
    expires_at timestamp with time zone NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL
);


--
-- TOC entry 289 (class 1259 OID 33759)
-- Name: failed_jobs; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- TOC entry 288 (class 1259 OID 33758)
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: auth; Owner: -
--

CREATE SEQUENCE auth.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6156 (class 0 OID 0)
-- Dependencies: 288
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: auth; Owner: -
--

ALTER SEQUENCE auth.failed_jobs_id_seq OWNED BY auth.failed_jobs.id;


--
-- TOC entry 287 (class 1259 OID 33744)
-- Name: job_batches; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


--
-- TOC entry 286 (class 1259 OID 33729)
-- Name: jobs; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


--
-- TOC entry 285 (class 1259 OID 33728)
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: auth; Owner: -
--

CREATE SEQUENCE auth.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6157 (class 0 OID 0)
-- Dependencies: 285
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: auth; Owner: -
--

ALTER SEQUENCE auth.jobs_id_seq OWNED BY auth.jobs.id;


--
-- TOC entry 282 (class 1259 OID 33699)
-- Name: migrations; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- TOC entry 281 (class 1259 OID 33698)
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: auth; Owner: -
--

CREATE SEQUENCE auth.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6158 (class 0 OID 0)
-- Dependencies: 281
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: auth; Owner: -
--

ALTER SEQUENCE auth.migrations_id_seq OWNED BY auth.migrations.id;


--
-- TOC entry 294 (class 1259 OID 33809)
-- Name: model_has_permissions; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.model_has_permissions (
    permission_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id uuid NOT NULL
);


--
-- TOC entry 295 (class 1259 OID 33823)
-- Name: model_has_roles; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.model_has_roles (
    role_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id uuid NOT NULL
);


--
-- TOC entry 298 (class 1259 OID 33855)
-- Name: modules; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.modules (
    id bigint NOT NULL,
    module_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- TOC entry 297 (class 1259 OID 33854)
-- Name: modules_id_seq; Type: SEQUENCE; Schema: auth; Owner: -
--

CREATE SEQUENCE auth.modules_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6159 (class 0 OID 0)
-- Dependencies: 297
-- Name: modules_id_seq; Type: SEQUENCE OWNED BY; Schema: auth; Owner: -
--

ALTER SEQUENCE auth.modules_id_seq OWNED BY auth.modules.id;


--
-- TOC entry 309 (class 1259 OID 34011)
-- Name: nav_buttons; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.nav_buttons (
    id bigint NOT NULL,
    submenu_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    key_value character varying(255) NOT NULL,
    sort_order integer DEFAULT 0 NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- TOC entry 308 (class 1259 OID 34010)
-- Name: nav_buttons_id_seq; Type: SEQUENCE; Schema: auth; Owner: -
--

CREATE SEQUENCE auth.nav_buttons_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6160 (class 0 OID 0)
-- Dependencies: 308
-- Name: nav_buttons_id_seq; Type: SEQUENCE OWNED BY; Schema: auth; Owner: -
--

ALTER SEQUENCE auth.nav_buttons_id_seq OWNED BY auth.nav_buttons.id;


--
-- TOC entry 305 (class 1259 OID 33970)
-- Name: nav_menus; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.nav_menus (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    key character varying(255) NOT NULL,
    icon_key character varying(255),
    "to" character varying(255),
    search_key character varying(255),
    divider_title character varying(255),
    guard character varying(255) DEFAULT 'web'::character varying NOT NULL,
    sort_order integer DEFAULT 0 NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    parent_menu_id bigint,
    type character varying(255) DEFAULT 'admin'::character varying NOT NULL,
    CONSTRAINT nav_menus_type_check CHECK (((type)::text = ANY ((ARRAY['admin'::character varying, 'teacher'::character varying, 'student'::character varying])::text[])))
);


--
-- TOC entry 304 (class 1259 OID 33969)
-- Name: nav_menus_id_seq; Type: SEQUENCE; Schema: auth; Owner: -
--

CREATE SEQUENCE auth.nav_menus_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6161 (class 0 OID 0)
-- Dependencies: 304
-- Name: nav_menus_id_seq; Type: SEQUENCE OWNED BY; Schema: auth; Owner: -
--

ALTER SEQUENCE auth.nav_menus_id_seq OWNED BY auth.nav_menus.id;


--
-- TOC entry 311 (class 1259 OID 34034)
-- Name: nav_parent_menus; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.nav_parent_menus (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard character varying(255) DEFAULT 'web'::character varying NOT NULL,
    sort_order integer DEFAULT 0 NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    type character varying(255) DEFAULT 'admin'::character varying NOT NULL,
    CONSTRAINT nav_parent_menus_type_check CHECK (((type)::text = ANY ((ARRAY['admin'::character varying, 'teacher'::character varying, 'student'::character varying])::text[])))
);


--
-- TOC entry 310 (class 1259 OID 34033)
-- Name: nav_parent_menus_id_seq; Type: SEQUENCE; Schema: auth; Owner: -
--

CREATE SEQUENCE auth.nav_parent_menus_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6162 (class 0 OID 0)
-- Dependencies: 310
-- Name: nav_parent_menus_id_seq; Type: SEQUENCE OWNED BY; Schema: auth; Owner: -
--

ALTER SEQUENCE auth.nav_parent_menus_id_seq OWNED BY auth.nav_parent_menus.id;


--
-- TOC entry 307 (class 1259 OID 33990)
-- Name: nav_submenus; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.nav_submenus (
    id bigint NOT NULL,
    menu_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    search_key character varying(255),
    "to" character varying(255),
    sort_order integer DEFAULT 0 NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- TOC entry 306 (class 1259 OID 33989)
-- Name: nav_submenus_id_seq; Type: SEQUENCE; Schema: auth; Owner: -
--

CREATE SEQUENCE auth.nav_submenus_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6163 (class 0 OID 0)
-- Dependencies: 306
-- Name: nav_submenus_id_seq; Type: SEQUENCE OWNED BY; Schema: auth; Owner: -
--

ALTER SEQUENCE auth.nav_submenus_id_seq OWNED BY auth.nav_submenus.id;


--
-- TOC entry 248 (class 1259 OID 32641)
-- Name: oauth_accounts; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.oauth_accounts (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    user_id uuid NOT NULL,
    provider character varying(32) NOT NULL,
    provider_user_id text NOT NULL,
    access_token text,
    refresh_token text,
    expires_at timestamp with time zone,
    created_at timestamp with time zone DEFAULT now() NOT NULL
);


--
-- TOC entry 323 (class 1259 OID 40960)
-- Name: otp_verifications; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.otp_verifications (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    user_id uuid NOT NULL,
    phone character varying(20) NOT NULL,
    otp_code character varying(64) NOT NULL,
    type character varying(30) DEFAULT 'registration'::character varying NOT NULL,
    is_used boolean DEFAULT false NOT NULL,
    expires_at timestamp(0) with time zone NOT NULL,
    created_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- TOC entry 246 (class 1259 OID 32595)
-- Name: password_reset_tokens; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.password_reset_tokens (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    user_id uuid NOT NULL,
    token_hash text NOT NULL,
    used boolean DEFAULT false NOT NULL,
    expires_at timestamp with time zone NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL
);


--
-- TOC entry 291 (class 1259 OID 33782)
-- Name: permissions; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.permissions (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    display_name character varying(255),
    description text,
    module_id bigint NOT NULL,
    sub_module character varying(255),
    sub_module_id bigint
);


--
-- TOC entry 290 (class 1259 OID 33781)
-- Name: permissions_id_seq; Type: SEQUENCE; Schema: auth; Owner: -
--

CREATE SEQUENCE auth.permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6164 (class 0 OID 0)
-- Dependencies: 290
-- Name: permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: auth; Owner: -
--

ALTER SEQUENCE auth.permissions_id_seq OWNED BY auth.permissions.id;


--
-- TOC entry 322 (class 1259 OID 34382)
-- Name: personal_access_tokens; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id uuid NOT NULL,
    name text NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- TOC entry 321 (class 1259 OID 34381)
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: auth; Owner: -
--

CREATE SEQUENCE auth.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6165 (class 0 OID 0)
-- Dependencies: 321
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: auth; Owner: -
--

ALTER SEQUENCE auth.personal_access_tokens_id_seq OWNED BY auth.personal_access_tokens.id;


--
-- TOC entry 296 (class 1259 OID 33837)
-- Name: role_has_permissions; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.role_has_permissions (
    permission_id bigint NOT NULL,
    role_id bigint NOT NULL
);


--
-- TOC entry 313 (class 1259 OID 34089)
-- Name: role_nav_permissions; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.role_nav_permissions (
    id bigint NOT NULL,
    role_id bigint NOT NULL,
    nav_item_type character varying(20) NOT NULL,
    nav_item_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- TOC entry 312 (class 1259 OID 34088)
-- Name: role_nav_permissions_id_seq; Type: SEQUENCE; Schema: auth; Owner: -
--

CREATE SEQUENCE auth.role_nav_permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6166 (class 0 OID 0)
-- Dependencies: 312
-- Name: role_nav_permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: auth; Owner: -
--

ALTER SEQUENCE auth.role_nav_permissions_id_seq OWNED BY auth.role_nav_permissions.id;


--
-- TOC entry 293 (class 1259 OID 33796)
-- Name: roles; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.roles (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    display_name character varying(255),
    description text,
    role_type character varying(255)
);


--
-- TOC entry 292 (class 1259 OID 33795)
-- Name: roles_id_seq; Type: SEQUENCE; Schema: auth; Owner: -
--

CREATE SEQUENCE auth.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6167 (class 0 OID 0)
-- Dependencies: 292
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: auth; Owner: -
--

ALTER SEQUENCE auth.roles_id_seq OWNED BY auth.roles.id;


--
-- TOC entry 245 (class 1259 OID 32572)
-- Name: sessions; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.sessions (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    user_id uuid NOT NULL,
    refresh_token text NOT NULL,
    device_info jsonb,
    ip_address inet,
    is_revoked boolean DEFAULT false NOT NULL,
    expires_at timestamp with time zone NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL
);


--
-- TOC entry 303 (class 1259 OID 33924)
-- Name: sub_modules; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.sub_modules (
    id bigint NOT NULL,
    module_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- TOC entry 302 (class 1259 OID 33923)
-- Name: sub_modules_id_seq; Type: SEQUENCE; Schema: auth; Owner: -
--

CREATE SEQUENCE auth.sub_modules_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6168 (class 0 OID 0)
-- Dependencies: 302
-- Name: sub_modules_id_seq; Type: SEQUENCE OWNED BY; Schema: auth; Owner: -
--

ALTER SEQUENCE auth.sub_modules_id_seq OWNED BY auth.sub_modules.id;


--
-- TOC entry 315 (class 1259 OID 34107)
-- Name: user_nav_permissions; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.user_nav_permissions (
    id bigint NOT NULL,
    user_id uuid NOT NULL,
    nav_item_type character varying(20) NOT NULL,
    nav_item_id bigint NOT NULL,
    is_granted boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- TOC entry 314 (class 1259 OID 34106)
-- Name: user_nav_permissions_id_seq; Type: SEQUENCE; Schema: auth; Owner: -
--

CREATE SEQUENCE auth.user_nav_permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6169 (class 0 OID 0)
-- Dependencies: 314
-- Name: user_nav_permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: auth; Owner: -
--

ALTER SEQUENCE auth.user_nav_permissions_id_seq OWNED BY auth.user_nav_permissions.id;


--
-- TOC entry 244 (class 1259 OID 32543)
-- Name: users; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.users (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    email character varying(320) NOT NULL,
    phone character varying(20),
    password_hash text NOT NULL,
    role public.user_role DEFAULT 'student'::public.user_role NOT NULL,
    status public.user_status DEFAULT 'pending_verification'::public.user_status NOT NULL,
    email_verified boolean DEFAULT false NOT NULL,
    phone_verified boolean DEFAULT false NOT NULL,
    last_login_at timestamp with time zone,
    last_login_ip inet,
    login_attempts smallint DEFAULT 0 NOT NULL,
    locked_until timestamp with time zone,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL,
    username character varying(100),
    user_type character varying(50),
    salt character varying(255),
    language character varying(10) DEFAULT 'en'::character varying NOT NULL,
    remember_token character varying(100),
    email_verified_at timestamp(0) with time zone,
    two_factor_secret text,
    two_factor_recovery_codes text,
    two_factor_confirmed_at timestamp(0) with time zone,
    password_changed_at timestamp(0) with time zone,
    failed_login_attempts smallint DEFAULT '0'::smallint NOT NULL
);


--
-- TOC entry 301 (class 1259 OID 33891)
-- Name: web_sessions; Type: TABLE; Schema: auth; Owner: -
--

CREATE TABLE auth.web_sessions (
    id character varying(255) NOT NULL,
    user_id uuid,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


--
-- TOC entry 268 (class 1259 OID 33199)
-- Name: coupons; Type: TABLE; Schema: commerce; Owner: -
--

CREATE TABLE commerce.coupons (
    id integer NOT NULL,
    teacher_id uuid,
    course_id uuid,
    code character varying(32) NOT NULL,
    description text,
    discount_type character varying(10) NOT NULL,
    discount_value numeric(10,2) NOT NULL,
    max_uses integer,
    used_count integer DEFAULT 0 NOT NULL,
    min_order_amount numeric(10,2),
    valid_from timestamp with time zone DEFAULT now() NOT NULL,
    valid_until timestamp with time zone,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    CONSTRAINT coupons_discount_type_check CHECK (((discount_type)::text = ANY ((ARRAY['percentage'::character varying, 'fixed'::character varying])::text[])))
);


--
-- TOC entry 267 (class 1259 OID 33198)
-- Name: coupons_id_seq; Type: SEQUENCE; Schema: commerce; Owner: -
--

CREATE SEQUENCE commerce.coupons_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6170 (class 0 OID 0)
-- Dependencies: 267
-- Name: coupons_id_seq; Type: SEQUENCE OWNED BY; Schema: commerce; Owner: -
--

ALTER SEQUENCE commerce.coupons_id_seq OWNED BY commerce.coupons.id;


--
-- TOC entry 265 (class 1259 OID 33118)
-- Name: enrollments; Type: TABLE; Schema: commerce; Owner: -
--

CREATE TABLE commerce.enrollments (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    student_id uuid NOT NULL,
    course_id uuid NOT NULL,
    status public.enrollment_status DEFAULT 'active'::public.enrollment_status NOT NULL,
    enrolled_at timestamp with time zone DEFAULT now() NOT NULL,
    expires_at timestamp with time zone,
    cancelled_at timestamp with time zone,
    cancellation_reason text,
    progress_pct smallint DEFAULT 0 NOT NULL,
    last_lesson_id uuid,
    last_accessed_at timestamp with time zone,
    completed_at timestamp with time zone,
    certificate_id uuid,
    current_plan_price numeric(10,2) DEFAULT 0.00 NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL
);


--
-- TOC entry 269 (class 1259 OID 33232)
-- Name: payments; Type: TABLE; Schema: commerce; Owner: -
--

CREATE TABLE commerce.payments (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    student_id uuid NOT NULL,
    course_id uuid NOT NULL,
    enrollment_id uuid,
    subscription_id uuid,
    coupon_id integer,
    gross_amount numeric(10,2) NOT NULL,
    discount_amount numeric(10,2) DEFAULT 0.00 NOT NULL,
    net_amount numeric(10,2) NOT NULL,
    platform_fee numeric(10,2) NOT NULL,
    teacher_amount numeric(10,2) NOT NULL,
    currency character(3) DEFAULT 'BDT'::bpchar NOT NULL,
    status public.payment_status DEFAULT 'pending'::public.payment_status NOT NULL,
    method_id integer,
    gateway character varying(32),
    gateway_txn_id character varying(128),
    gateway_response jsonb,
    gateway_fee numeric(10,2) DEFAULT 0.00 NOT NULL,
    invoice_number character varying(32),
    invoice_url text,
    refunded_at timestamp with time zone,
    refund_amount numeric(10,2),
    refund_reason text,
    refund_txn_id character varying(128),
    paid_at timestamp with time zone,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL
);


--
-- TOC entry 271 (class 1259 OID 33312)
-- Name: payouts; Type: TABLE; Schema: commerce; Owner: -
--

CREATE TABLE commerce.payouts (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    teacher_id uuid NOT NULL,
    amount numeric(12,2) NOT NULL,
    status character varying(20) DEFAULT 'pending'::character varying NOT NULL,
    method character varying(32) NOT NULL,
    account_info jsonb,
    processed_at timestamp with time zone,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    CONSTRAINT payouts_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'processing'::character varying, 'completed'::character varying, 'failed'::character varying])::text[])))
);


--
-- TOC entry 266 (class 1259 OID 33158)
-- Name: subscriptions; Type: TABLE; Schema: commerce; Owner: -
--

CREATE TABLE commerce.subscriptions (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    enrollment_id uuid NOT NULL,
    student_id uuid NOT NULL,
    course_id uuid NOT NULL,
    status public.enrollment_status DEFAULT 'active'::public.enrollment_status NOT NULL,
    billing_cycle character varying(16) DEFAULT 'monthly'::character varying NOT NULL,
    price_per_cycle numeric(10,2) NOT NULL,
    currency character(3) DEFAULT 'BDT'::bpchar NOT NULL,
    current_period_start timestamp with time zone NOT NULL,
    current_period_end timestamp with time zone NOT NULL,
    next_billing_date timestamp with time zone,
    trial_end_at timestamp with time zone,
    cancelled_at timestamp with time zone,
    pause_until timestamp with time zone,
    auto_renew boolean DEFAULT true NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL
);


--
-- TOC entry 270 (class 1259 OID 33293)
-- Name: teacher_wallet; Type: TABLE; Schema: commerce; Owner: -
--

CREATE TABLE commerce.teacher_wallet (
    teacher_id uuid NOT NULL,
    available numeric(12,2) DEFAULT 0.00 NOT NULL,
    pending numeric(12,2) DEFAULT 0.00 NOT NULL,
    lifetime_earned numeric(14,2) DEFAULT 0.00 NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL
);


--
-- TOC entry 272 (class 1259 OID 33334)
-- Name: wallet_transactions; Type: TABLE; Schema: commerce; Owner: -
--

CREATE TABLE commerce.wallet_transactions (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    teacher_id uuid NOT NULL,
    payment_id uuid,
    payout_id uuid,
    type character varying(16) NOT NULL,
    amount numeric(12,2) NOT NULL,
    balance_after numeric(12,2) NOT NULL,
    description text,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    CONSTRAINT wallet_transactions_type_check CHECK (((type)::text = ANY ((ARRAY['credit'::character varying, 'debit'::character varying, 'reversal'::character varying])::text[])))
);


--
-- TOC entry 279 (class 1259 OID 33626)
-- Name: announcements; Type: TABLE; Schema: comms; Owner: -
--

CREATE TABLE comms.announcements (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    teacher_id uuid NOT NULL,
    course_id uuid,
    title character varying(200) NOT NULL,
    body text NOT NULL,
    is_pinned boolean DEFAULT false NOT NULL,
    send_email boolean DEFAULT true NOT NULL,
    send_push boolean DEFAULT true NOT NULL,
    published_at timestamp with time zone,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL
);


--
-- TOC entry 280 (class 1259 OID 33658)
-- Name: notifications; Type: TABLE; Schema: comms; Owner: -
--

CREATE TABLE comms.notifications (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    user_id uuid NOT NULL,
    type_id integer NOT NULL,
    title character varying(200) NOT NULL,
    body text,
    data jsonb DEFAULT '{}'::jsonb NOT NULL,
    action_url text,
    is_read boolean DEFAULT false NOT NULL,
    read_at timestamp with time zone,
    sent_at timestamp with time zone DEFAULT now() NOT NULL,
    email_sent boolean DEFAULT false NOT NULL,
    push_sent boolean DEFAULT false NOT NULL,
    sms_sent boolean DEFAULT false NOT NULL
);


--
-- TOC entry 278 (class 1259 OID 33577)
-- Name: reviews; Type: TABLE; Schema: comms; Owner: -
--

CREATE TABLE comms.reviews (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    course_id uuid NOT NULL,
    teacher_id uuid NOT NULL,
    student_id uuid NOT NULL,
    enrollment_id uuid NOT NULL,
    rating smallint NOT NULL,
    title character varying(120),
    body text,
    is_verified boolean DEFAULT false NOT NULL,
    is_featured boolean DEFAULT false NOT NULL,
    is_hidden boolean DEFAULT false NOT NULL,
    helpful_count integer DEFAULT 0 NOT NULL,
    reply text,
    reply_at timestamp with time zone,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL,
    CONSTRAINT reviews_rating_check CHECK (((rating >= 1) AND (rating <= 5)))
);


--
-- TOC entry 320 (class 1259 OID 34340)
-- Name: class_names; Type: TABLE; Schema: core; Owner: -
--

CREATE TABLE core.class_names (
    id smallint NOT NULL,
    name character varying(120) NOT NULL,
    name_bn character varying(120),
    slug character varying(255),
    icon character varying(255),
    color character varying(255),
    sort_order smallint DEFAULT '0'::smallint,
    is_active boolean DEFAULT true,
    created_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- TOC entry 319 (class 1259 OID 34339)
-- Name: class_names_id_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE core.class_names_id_seq
    AS smallint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6171 (class 0 OID 0)
-- Dependencies: 319
-- Name: class_names_id_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE core.class_names_id_seq OWNED BY core.class_names.id;


--
-- TOC entry 317 (class 1259 OID 34280)
-- Name: class_schedule; Type: TABLE; Schema: core; Owner: -
--

CREATE TABLE core.class_schedule (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    class_room_id uuid NOT NULL,
    day_of_week character varying(10) NOT NULL,
    start_time time(0) without time zone NOT NULL,
    end_time time(0) without time zone NOT NULL,
    timezone character varying(64) DEFAULT 'Asia/Dhaka'::character varying NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    effective_from date,
    effective_until date,
    created_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- TOC entry 318 (class 1259 OID 34297)
-- Name: class_students; Type: TABLE; Schema: core; Owner: -
--

CREATE TABLE core.class_students (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    class_room_id uuid NOT NULL,
    student_id uuid NOT NULL,
    enrollment_id uuid,
    status character varying(20) DEFAULT 'active'::character varying NOT NULL,
    joined_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    left_at timestamp(0) with time zone,
    notes text,
    created_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- TOC entry 316 (class 1259 OID 34252)
-- Name: classes; Type: TABLE; Schema: core; Owner: -
--

CREATE TABLE core.classes (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    course_id uuid,
    teacher_id uuid NOT NULL,
    subject_id smallint,
    name character varying(120) NOT NULL,
    slug character varying(140) NOT NULL,
    description text,
    class_type character varying(20) DEFAULT 'live'::character varying NOT NULL,
    status character varying(20) DEFAULT 'draft'::character varying NOT NULL,
    max_students integer,
    enrolled_count integer DEFAULT 0 NOT NULL,
    start_date date,
    end_date date,
    grade_level_ids text DEFAULT '{}'::text NOT NULL,
    language_id integer DEFAULT 1 NOT NULL,
    meeting_platform character varying(32),
    meeting_url text,
    meeting_id character varying(128),
    meeting_password character varying(64),
    thumbnail_url text,
    is_featured boolean DEFAULT false NOT NULL,
    is_public boolean DEFAULT true NOT NULL,
    notes text,
    created_by uuid,
    published_at timestamp(0) with time zone,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone,
    deleted_at timestamp(0) with time zone
);


--
-- TOC entry 233 (class 1259 OID 32460)
-- Name: course_format; Type: TABLE; Schema: core; Owner: -
--

CREATE TABLE core.course_format (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    created_at timestamp with time zone DEFAULT now(),
    updated_at timestamp with time zone DEFAULT now()
);


--
-- TOC entry 232 (class 1259 OID 32459)
-- Name: course_format_id_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE core.course_format_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6172 (class 0 OID 0)
-- Dependencies: 232
-- Name: course_format_id_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE core.course_format_id_seq OWNED BY core.course_format.id;


--
-- TOC entry 241 (class 1259 OID 32516)
-- Name: grade_level; Type: TABLE; Schema: core; Owner: -
--

CREATE TABLE core.grade_level (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    created_at timestamp with time zone DEFAULT now(),
    updated_at timestamp with time zone DEFAULT now(),
    name_bn character varying(120),
    slug character varying(255),
    icon character varying(255),
    color character varying(255),
    sort_order smallint DEFAULT '0'::smallint,
    is_active boolean DEFAULT true
);


--
-- TOC entry 240 (class 1259 OID 32515)
-- Name: grade_level_id_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE core.grade_level_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6173 (class 0 OID 0)
-- Dependencies: 240
-- Name: grade_level_id_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE core.grade_level_id_seq OWNED BY core.grade_level.id;


--
-- TOC entry 243 (class 1259 OID 32531)
-- Name: language_medium; Type: TABLE; Schema: core; Owner: -
--

CREATE TABLE core.language_medium (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    created_at timestamp with time zone DEFAULT now(),
    updated_at timestamp with time zone DEFAULT now()
);


--
-- TOC entry 242 (class 1259 OID 32530)
-- Name: language_medium_id_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE core.language_medium_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6174 (class 0 OID 0)
-- Dependencies: 242
-- Name: language_medium_id_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE core.language_medium_id_seq OWNED BY core.language_medium.id;


--
-- TOC entry 235 (class 1259 OID 32475)
-- Name: lesson_type; Type: TABLE; Schema: core; Owner: -
--

CREATE TABLE core.lesson_type (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    created_at timestamp with time zone DEFAULT now(),
    updated_at timestamp with time zone DEFAULT now()
);


--
-- TOC entry 234 (class 1259 OID 32474)
-- Name: lesson_type_id_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE core.lesson_type_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6175 (class 0 OID 0)
-- Dependencies: 234
-- Name: lesson_type_id_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE core.lesson_type_id_seq OWNED BY core.lesson_type.id;


--
-- TOC entry 254 (class 1259 OID 32787)
-- Name: media; Type: TABLE; Schema: core; Owner: -
--

CREATE TABLE core.media (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    uploader_id uuid NOT NULL,
    media_type public.media_type NOT NULL,
    original_name character varying(255) NOT NULL,
    storage_key text NOT NULL,
    public_url text NOT NULL,
    cdn_url text,
    mime_type character varying(127) NOT NULL,
    size_bytes bigint NOT NULL,
    width_px integer,
    height_px integer,
    duration_sec integer,
    metadata jsonb DEFAULT '{}'::jsonb NOT NULL,
    is_public boolean DEFAULT false NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL
);


--
-- TOC entry 239 (class 1259 OID 32503)
-- Name: notification_type; Type: TABLE; Schema: core; Owner: -
--

CREATE TABLE core.notification_type (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    created_at timestamp with time zone DEFAULT now(),
    updated_at timestamp with time zone DEFAULT now()
);


--
-- TOC entry 238 (class 1259 OID 32502)
-- Name: notification_type_id_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE core.notification_type_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6176 (class 0 OID 0)
-- Dependencies: 238
-- Name: notification_type_id_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE core.notification_type_id_seq OWNED BY core.notification_type.id;


--
-- TOC entry 237 (class 1259 OID 32490)
-- Name: payment_method; Type: TABLE; Schema: core; Owner: -
--

CREATE TABLE core.payment_method (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    created_at timestamp with time zone DEFAULT now(),
    updated_at timestamp with time zone DEFAULT now()
);


--
-- TOC entry 236 (class 1259 OID 32489)
-- Name: payment_method_id_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE core.payment_method_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6177 (class 0 OID 0)
-- Dependencies: 236
-- Name: payment_method_id_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE core.payment_method_id_seq OWNED BY core.payment_method.id;


--
-- TOC entry 249 (class 1259 OID 32662)
-- Name: profiles; Type: TABLE; Schema: core; Owner: -
--

CREATE TABLE core.profiles (
    id uuid NOT NULL,
    first_name character varying(80) NOT NULL,
    last_name character varying(80) NOT NULL,
    display_name character varying(120),
    avatar_url text,
    cover_url text,
    bio text,
    date_of_birth date,
    gender character varying(16),
    country character varying(2) DEFAULT 'BD'::character varying NOT NULL,
    city character varying(80),
    timezone character varying(64) DEFAULT 'Asia/Dhaka'::character varying NOT NULL,
    language character varying(8) DEFAULT 'bn'::character varying NOT NULL,
    notifications_prefs jsonb DEFAULT '{}'::jsonb NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL,
    address character varying(500),
    contact_no character varying(20),
    location character varying(500)
);


--
-- TOC entry 250 (class 1259 OID 32689)
-- Name: student_profiles; Type: TABLE; Schema: core; Owner: -
--

CREATE TABLE core.student_profiles (
    user_id uuid NOT NULL,
    grade_level_id integer,
    school_name character varying(200),
    board public.board_type,
    roll_number character varying(30),
    registration_number character varying(30),
    parent_name character varying(160),
    parent_phone character varying(20),
    parent_email character varying(320),
    points integer DEFAULT 0 NOT NULL,
    last_activity_at timestamp with time zone,
    updated_at timestamp with time zone DEFAULT now() NOT NULL,
    interested_subjects jsonb
);


--
-- TOC entry 253 (class 1259 OID 32763)
-- Name: subjects; Type: TABLE; Schema: core; Owner: -
--

CREATE TABLE core.subjects (
    id smallint NOT NULL,
    name character varying(120) NOT NULL,
    slug character varying(120) NOT NULL,
    name_bn character varying(120),
    icon character varying(64),
    color character varying(7),
    parent_id smallint,
    sort_order smallint DEFAULT 0 NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL
);


--
-- TOC entry 252 (class 1259 OID 32762)
-- Name: subjects_id_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE core.subjects_id_seq
    AS smallint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6178 (class 0 OID 0)
-- Dependencies: 252
-- Name: subjects_id_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE core.subjects_id_seq OWNED BY core.subjects.id;


--
-- TOC entry 333 (class 1259 OID 41046)
-- Name: teacher_availability; Type: TABLE; Schema: core; Owner: -
--

CREATE TABLE core.teacher_availability (
    id bigint NOT NULL,
    user_id uuid NOT NULL,
    day_of_week smallint NOT NULL,
    time_slot time(0) without time zone NOT NULL,
    created_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- TOC entry 332 (class 1259 OID 41045)
-- Name: teacher_availability_id_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE core.teacher_availability_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6179 (class 0 OID 0)
-- Dependencies: 332
-- Name: teacher_availability_id_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE core.teacher_availability_id_seq OWNED BY core.teacher_availability.id;


--
-- TOC entry 325 (class 1259 OID 40978)
-- Name: teacher_educations; Type: TABLE; Schema: core; Owner: -
--

CREATE TABLE core.teacher_educations (
    id bigint NOT NULL,
    user_id uuid NOT NULL,
    type character varying(20) NOT NULL,
    title character varying(200) NOT NULL,
    institution character varying(200),
    start_year smallint,
    end_year smallint,
    is_current boolean DEFAULT false NOT NULL,
    sort_order smallint DEFAULT '0'::smallint NOT NULL,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone,
    cgpa character varying(255)
);


--
-- TOC entry 324 (class 1259 OID 40977)
-- Name: teacher_educations_id_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE core.teacher_educations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6180 (class 0 OID 0)
-- Dependencies: 324
-- Name: teacher_educations_id_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE core.teacher_educations_id_seq OWNED BY core.teacher_educations.id;


--
-- TOC entry 327 (class 1259 OID 40994)
-- Name: teacher_experiences; Type: TABLE; Schema: core; Owner: -
--

CREATE TABLE core.teacher_experiences (
    id bigint NOT NULL,
    user_id uuid NOT NULL,
    role_type character varying(30) NOT NULL,
    start_month smallint,
    start_year smallint,
    description text,
    expertise_tags jsonb DEFAULT '[]'::jsonb NOT NULL,
    is_current boolean DEFAULT true NOT NULL,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- TOC entry 326 (class 1259 OID 40993)
-- Name: teacher_experiences_id_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE core.teacher_experiences_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6181 (class 0 OID 0)
-- Dependencies: 326
-- Name: teacher_experiences_id_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE core.teacher_experiences_id_seq OWNED BY core.teacher_experiences.id;


--
-- TOC entry 329 (class 1259 OID 41019)
-- Name: teacher_grade_levels; Type: TABLE; Schema: core; Owner: -
--

CREATE TABLE core.teacher_grade_levels (
    user_id uuid NOT NULL,
    grade_level_id integer NOT NULL,
    created_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- TOC entry 251 (class 1259 OID 32711)
-- Name: teacher_profiles; Type: TABLE; Schema: core; Owner: -
--

CREATE TABLE core.teacher_profiles (
    user_id uuid NOT NULL,
    teacher_type_id integer DEFAULT 1 NOT NULL,
    headline character varying(200),
    specializations text[],
    qualifications jsonb,
    experience_years smallint,
    institution character varying(200),
    department character varying(200),
    verified boolean DEFAULT false NOT NULL,
    verified_at timestamp with time zone,
    verified_by uuid,
    id_document_url text,
    id_verified boolean DEFAULT false NOT NULL,
    rating_avg numeric(3,2) DEFAULT 0.00 NOT NULL,
    rating_count integer DEFAULT 0 NOT NULL,
    total_students integer DEFAULT 0 NOT NULL,
    total_courses smallint DEFAULT 0 NOT NULL,
    total_revenue numeric(14,2) DEFAULT 0.00 NOT NULL,
    payout_account jsonb,
    payout_threshold numeric(10,2) DEFAULT 500.00 NOT NULL,
    commission_rate numeric(5,4) DEFAULT 0.1500 NOT NULL,
    is_featured boolean DEFAULT false NOT NULL,
    social_links jsonb DEFAULT '{}'::jsonb NOT NULL,
    languages_taught integer[] DEFAULT '{1}'::integer[] NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL,
    session_types jsonb DEFAULT '[]'::jsonb NOT NULL,
    is_flexible_time boolean DEFAULT false NOT NULL
);


--
-- TOC entry 328 (class 1259 OID 41010)
-- Name: teacher_subjects; Type: TABLE; Schema: core; Owner: -
--

CREATE TABLE core.teacher_subjects (
    user_id uuid NOT NULL,
    subject_id smallint NOT NULL,
    created_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- TOC entry 331 (class 1259 OID 41029)
-- Name: teacher_teaching_styles; Type: TABLE; Schema: core; Owner: -
--

CREATE TABLE core.teacher_teaching_styles (
    id bigint NOT NULL,
    user_id uuid NOT NULL,
    name character varying(100) NOT NULL,
    description text,
    color character varying(20) DEFAULT 'blue'::character varying NOT NULL,
    sort_order smallint DEFAULT '0'::smallint NOT NULL,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- TOC entry 330 (class 1259 OID 41028)
-- Name: teacher_teaching_styles_id_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE core.teacher_teaching_styles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6182 (class 0 OID 0)
-- Dependencies: 330
-- Name: teacher_teaching_styles_id_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE core.teacher_teaching_styles_id_seq OWNED BY core.teacher_teaching_styles.id;


--
-- TOC entry 231 (class 1259 OID 32445)
-- Name: teacher_type; Type: TABLE; Schema: core; Owner: -
--

CREATE TABLE core.teacher_type (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    created_at timestamp with time zone DEFAULT now(),
    updated_at timestamp with time zone DEFAULT now()
);


--
-- TOC entry 230 (class 1259 OID 32444)
-- Name: teacher_type_id_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE core.teacher_type_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6183 (class 0 OID 0)
-- Dependencies: 230
-- Name: teacher_type_id_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE core.teacher_type_id_seq OWNED BY core.teacher_type.id;


--
-- TOC entry 335 (class 1259 OID 41062)
-- Name: teacher_verifications; Type: TABLE; Schema: core; Owner: -
--

CREATE TABLE core.teacher_verifications (
    id bigint NOT NULL,
    user_id uuid NOT NULL,
    doc_type character varying(30) NOT NULL,
    doc_url text NOT NULL,
    status character varying(20) DEFAULT 'pending'::character varying NOT NULL,
    reviewed_by uuid,
    reviewer_notes text,
    reviewed_at timestamp(0) with time zone,
    submitted_at timestamp(0) with time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- TOC entry 334 (class 1259 OID 41061)
-- Name: teacher_verifications_id_seq; Type: SEQUENCE; Schema: core; Owner: -
--

CREATE SEQUENCE core.teacher_verifications_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6184 (class 0 OID 0)
-- Dependencies: 334
-- Name: teacher_verifications_id_seq; Type: SEQUENCE OWNED BY; Schema: core; Owner: -
--

ALTER SEQUENCE core.teacher_verifications_id_seq OWNED BY core.teacher_verifications.id;


--
-- TOC entry 276 (class 1259 OID 33494)
-- Name: assignment_submissions; Type: TABLE; Schema: learn; Owner: -
--

CREATE TABLE learn.assignment_submissions (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    assignment_id uuid NOT NULL,
    student_id uuid NOT NULL,
    enrollment_id uuid NOT NULL,
    status public.submission_status DEFAULT 'not_submitted'::public.submission_status NOT NULL,
    submitted_at timestamp with time zone,
    is_late boolean DEFAULT false NOT NULL,
    files jsonb DEFAULT '[]'::jsonb NOT NULL,
    student_note text,
    marks smallint,
    grade character varying(4),
    feedback text,
    graded_by uuid,
    graded_at timestamp with time zone,
    returned_at timestamp with time zone,
    resubmit_allowed boolean DEFAULT false NOT NULL,
    resubmit_count smallint DEFAULT 0 NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL
);


--
-- TOC entry 275 (class 1259 OID 33446)
-- Name: assignments; Type: TABLE; Schema: learn; Owner: -
--

CREATE TABLE learn.assignments (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    course_id uuid NOT NULL,
    teacher_id uuid NOT NULL,
    section_id uuid,
    title character varying(200) NOT NULL,
    description text NOT NULL,
    instructions text,
    attachments jsonb DEFAULT '[]'::jsonb NOT NULL,
    status public.assignment_status DEFAULT 'draft'::public.assignment_status NOT NULL,
    due_date timestamp with time zone,
    total_marks smallint DEFAULT 100 NOT NULL,
    pass_marks smallint DEFAULT 40 NOT NULL,
    allow_late_submit boolean DEFAULT false NOT NULL,
    late_penalty_pct smallint DEFAULT 0 NOT NULL,
    max_file_size_mb smallint DEFAULT 10 NOT NULL,
    allowed_file_types text[] DEFAULT '{}'::text[] NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL
);


--
-- TOC entry 277 (class 1259 OID 33542)
-- Name: certificates; Type: TABLE; Schema: learn; Owner: -
--

CREATE TABLE learn.certificates (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    student_id uuid NOT NULL,
    course_id uuid NOT NULL,
    enrollment_id uuid NOT NULL,
    status public.certificate_status DEFAULT 'not_earned'::public.certificate_status NOT NULL,
    certificate_number character varying(32),
    issued_at timestamp with time zone,
    pdf_url text,
    final_score numeric(5,2),
    created_at timestamp with time zone DEFAULT now() NOT NULL
);


--
-- TOC entry 257 (class 1259 OID 32889)
-- Name: course_requirements; Type: TABLE; Schema: learn; Owner: -
--

CREATE TABLE learn.course_requirements (
    id integer NOT NULL,
    course_id uuid NOT NULL,
    type character varying(16) NOT NULL,
    content text NOT NULL,
    sort_order smallint DEFAULT 0 NOT NULL,
    CONSTRAINT course_requirements_type_check CHECK (((type)::text = ANY ((ARRAY['prerequisite'::character varying, 'outcome'::character varying, 'target_student'::character varying])::text[])))
);


--
-- TOC entry 256 (class 1259 OID 32888)
-- Name: course_requirements_id_seq; Type: SEQUENCE; Schema: learn; Owner: -
--

CREATE SEQUENCE learn.course_requirements_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 6185 (class 0 OID 0)
-- Dependencies: 256
-- Name: course_requirements_id_seq; Type: SEQUENCE OWNED BY; Schema: learn; Owner: -
--

ALTER SEQUENCE learn.course_requirements_id_seq OWNED BY learn.course_requirements.id;


--
-- TOC entry 262 (class 1259 OID 33030)
-- Name: course_schedule; Type: TABLE; Schema: learn; Owner: -
--

CREATE TABLE learn.course_schedule (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    course_id uuid NOT NULL,
    day_of_week public.day_of_week NOT NULL,
    start_time time without time zone NOT NULL,
    end_time time without time zone NOT NULL,
    timezone character varying(64) DEFAULT 'Asia/Dhaka'::character varying NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    effective_from date,
    effective_until date,
    created_at timestamp with time zone DEFAULT now() NOT NULL
);


--
-- TOC entry 255 (class 1259 OID 32816)
-- Name: courses; Type: TABLE; Schema: learn; Owner: -
--

CREATE TABLE learn.courses (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    teacher_id uuid NOT NULL,
    subject_id smallint NOT NULL,
    title character varying(120) NOT NULL,
    slug character varying(140) NOT NULL,
    short_description character varying(300) NOT NULL,
    full_description text,
    thumbnail_url text,
    trailer_url text,
    grade_level_ids integer[] DEFAULT '{}'::integer[] NOT NULL,
    board public.board_type,
    language_id integer DEFAULT 1 NOT NULL,
    format_id integer DEFAULT 1 NOT NULL,
    status public.course_status DEFAULT 'draft'::public.course_status NOT NULL,
    tags text[] DEFAULT '{}'::text[] NOT NULL,
    pricing_model public.pricing_model DEFAULT 'monthly_subscription'::public.pricing_model NOT NULL,
    price numeric(10,2) DEFAULT 0.00 NOT NULL,
    original_price numeric(10,2),
    currency character(3) DEFAULT 'BDT'::bpchar NOT NULL,
    max_students integer,
    start_date date,
    end_date date,
    session_duration_min smallint DEFAULT 60 NOT NULL,
    enrolled_count integer DEFAULT 0 NOT NULL,
    completed_count integer DEFAULT 0 NOT NULL,
    rating_avg numeric(3,2) DEFAULT 0.00 NOT NULL,
    rating_count integer DEFAULT 0 NOT NULL,
    total_lessons smallint DEFAULT 0 NOT NULL,
    total_sections smallint DEFAULT 0 NOT NULL,
    total_duration_min integer DEFAULT 0 NOT NULL,
    published_at timestamp with time zone,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL,
    deleted_at timestamp with time zone
);


--
-- TOC entry 273 (class 1259 OID 33365)
-- Name: lesson_progress; Type: TABLE; Schema: learn; Owner: -
--

CREATE TABLE learn.lesson_progress (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    student_id uuid NOT NULL,
    lesson_id uuid NOT NULL,
    course_id uuid NOT NULL,
    enrollment_id uuid NOT NULL,
    is_completed boolean DEFAULT false NOT NULL,
    watch_seconds integer DEFAULT 0 NOT NULL,
    last_position_sec integer DEFAULT 0 NOT NULL,
    completed_at timestamp with time zone,
    first_opened_at timestamp with time zone,
    last_opened_at timestamp with time zone,
    notes text,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL
);


--
-- TOC entry 259 (class 1259 OID 32933)
-- Name: lessons; Type: TABLE; Schema: learn; Owner: -
--

CREATE TABLE learn.lessons (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    section_id uuid NOT NULL,
    course_id uuid NOT NULL,
    title character varying(200) NOT NULL,
    description text,
    lesson_type_id integer DEFAULT 1 NOT NULL,
    sort_order smallint DEFAULT 0 NOT NULL,
    is_free_preview boolean DEFAULT false NOT NULL,
    is_published boolean DEFAULT false NOT NULL,
    video_url text,
    video_duration_sec integer,
    document_url text,
    document_pages smallint,
    external_url text,
    content_json jsonb,
    scheduled_at timestamp with time zone,
    live_platform character varying(32),
    live_meeting_url text,
    live_meeting_id character varying(128),
    live_password character varying(64),
    thumbnail_url text,
    attachments jsonb DEFAULT '[]'::jsonb NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL
);


--
-- TOC entry 263 (class 1259 OID 33052)
-- Name: live_sessions; Type: TABLE; Schema: learn; Owner: -
--

CREATE TABLE learn.live_sessions (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    course_id uuid NOT NULL,
    lesson_id uuid,
    teacher_id uuid NOT NULL,
    title character varying(200) NOT NULL,
    description text,
    status public.session_status DEFAULT 'scheduled'::public.session_status NOT NULL,
    scheduled_start_at timestamp with time zone NOT NULL,
    scheduled_end_at timestamp with time zone NOT NULL,
    actual_start_at timestamp with time zone,
    actual_end_at timestamp with time zone,
    platform character varying(32) DEFAULT 'learnforge'::character varying NOT NULL,
    meeting_url text,
    meeting_id character varying(128),
    recording_url text,
    recording_available boolean DEFAULT false NOT NULL,
    attendee_count integer DEFAULT 0 NOT NULL,
    notes text,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL
);


--
-- TOC entry 274 (class 1259 OID 33410)
-- Name: quiz_attempts; Type: TABLE; Schema: learn; Owner: -
--

CREATE TABLE learn.quiz_attempts (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    student_id uuid NOT NULL,
    quiz_id uuid NOT NULL,
    enrollment_id uuid NOT NULL,
    attempt_number smallint DEFAULT 1 NOT NULL,
    started_at timestamp with time zone DEFAULT now() NOT NULL,
    submitted_at timestamp with time zone,
    time_taken_sec integer,
    score smallint,
    max_score smallint NOT NULL,
    percentage numeric(5,2),
    passed boolean,
    answers jsonb DEFAULT '[]'::jsonb NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL
);


--
-- TOC entry 261 (class 1259 OID 33007)
-- Name: quiz_questions; Type: TABLE; Schema: learn; Owner: -
--

CREATE TABLE learn.quiz_questions (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    quiz_id uuid NOT NULL,
    question_text text NOT NULL,
    question_image text,
    question_type character varying(20) DEFAULT 'mcq'::character varying NOT NULL,
    options jsonb,
    correct_answer text,
    explanation text,
    points smallint DEFAULT 1 NOT NULL,
    sort_order smallint DEFAULT 0 NOT NULL,
    CONSTRAINT quiz_questions_question_type_check CHECK (((question_type)::text = ANY ((ARRAY['mcq'::character varying, 'multi_select'::character varying, 'true_false'::character varying, 'short_answer'::character varying, 'fill_blank'::character varying])::text[])))
);


--
-- TOC entry 260 (class 1259 OID 32974)
-- Name: quizzes; Type: TABLE; Schema: learn; Owner: -
--

CREATE TABLE learn.quizzes (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    lesson_id uuid NOT NULL,
    course_id uuid NOT NULL,
    title character varying(200),
    instructions text,
    time_limit_min smallint,
    pass_percentage smallint DEFAULT 60 NOT NULL,
    attempts_allowed smallint DEFAULT 3 NOT NULL,
    randomize_questions boolean DEFAULT false NOT NULL,
    show_answers_after boolean DEFAULT true NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL
);


--
-- TOC entry 258 (class 1259 OID 32909)
-- Name: sections; Type: TABLE; Schema: learn; Owner: -
--

CREATE TABLE learn.sections (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    course_id uuid NOT NULL,
    title character varying(200) NOT NULL,
    description text,
    sort_order smallint DEFAULT 0 NOT NULL,
    is_free boolean DEFAULT false NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL
);


--
-- TOC entry 264 (class 1259 OID 33093)
-- Name: session_attendance; Type: TABLE; Schema: learn; Owner: -
--

CREATE TABLE learn.session_attendance (
    id uuid DEFAULT public.uuid_generate_v4() NOT NULL,
    session_id uuid NOT NULL,
    student_id uuid NOT NULL,
    joined_at timestamp with time zone,
    left_at timestamp with time zone,
    duration_min smallint,
    attended boolean DEFAULT false NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL
);


--
-- TOC entry 5603 (class 2604 OID 33877)
-- Name: audit_logs id; Type: DEFAULT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.audit_logs ALTER COLUMN id SET DEFAULT nextval('auth.audit_logs_id_seq'::regclass);


--
-- TOC entry 5598 (class 2604 OID 33762)
-- Name: failed_jobs id; Type: DEFAULT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.failed_jobs ALTER COLUMN id SET DEFAULT nextval('auth.failed_jobs_id_seq'::regclass);


--
-- TOC entry 5597 (class 2604 OID 33732)
-- Name: jobs id; Type: DEFAULT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.jobs ALTER COLUMN id SET DEFAULT nextval('auth.jobs_id_seq'::regclass);


--
-- TOC entry 5596 (class 2604 OID 33702)
-- Name: migrations id; Type: DEFAULT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.migrations ALTER COLUMN id SET DEFAULT nextval('auth.migrations_id_seq'::regclass);


--
-- TOC entry 5602 (class 2604 OID 33858)
-- Name: modules id; Type: DEFAULT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.modules ALTER COLUMN id SET DEFAULT nextval('auth.modules_id_seq'::regclass);


--
-- TOC entry 5614 (class 2604 OID 34014)
-- Name: nav_buttons id; Type: DEFAULT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.nav_buttons ALTER COLUMN id SET DEFAULT nextval('auth.nav_buttons_id_seq'::regclass);


--
-- TOC entry 5606 (class 2604 OID 33973)
-- Name: nav_menus id; Type: DEFAULT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.nav_menus ALTER COLUMN id SET DEFAULT nextval('auth.nav_menus_id_seq'::regclass);


--
-- TOC entry 5617 (class 2604 OID 34037)
-- Name: nav_parent_menus id; Type: DEFAULT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.nav_parent_menus ALTER COLUMN id SET DEFAULT nextval('auth.nav_parent_menus_id_seq'::regclass);


--
-- TOC entry 5611 (class 2604 OID 33993)
-- Name: nav_submenus id; Type: DEFAULT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.nav_submenus ALTER COLUMN id SET DEFAULT nextval('auth.nav_submenus_id_seq'::regclass);


--
-- TOC entry 5600 (class 2604 OID 33785)
-- Name: permissions id; Type: DEFAULT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.permissions ALTER COLUMN id SET DEFAULT nextval('auth.permissions_id_seq'::regclass);


--
-- TOC entry 5645 (class 2604 OID 34385)
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('auth.personal_access_tokens_id_seq'::regclass);


--
-- TOC entry 5622 (class 2604 OID 34092)
-- Name: role_nav_permissions id; Type: DEFAULT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.role_nav_permissions ALTER COLUMN id SET DEFAULT nextval('auth.role_nav_permissions_id_seq'::regclass);


--
-- TOC entry 5601 (class 2604 OID 33799)
-- Name: roles id; Type: DEFAULT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.roles ALTER COLUMN id SET DEFAULT nextval('auth.roles_id_seq'::regclass);


--
-- TOC entry 5605 (class 2604 OID 33927)
-- Name: sub_modules id; Type: DEFAULT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.sub_modules ALTER COLUMN id SET DEFAULT nextval('auth.sub_modules_id_seq'::regclass);


--
-- TOC entry 5623 (class 2604 OID 34110)
-- Name: user_nav_permissions id; Type: DEFAULT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.user_nav_permissions ALTER COLUMN id SET DEFAULT nextval('auth.user_nav_permissions_id_seq'::regclass);


--
-- TOC entry 5522 (class 2604 OID 33202)
-- Name: coupons id; Type: DEFAULT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.coupons ALTER COLUMN id SET DEFAULT nextval('commerce.coupons_id_seq'::regclass);


--
-- TOC entry 5641 (class 2604 OID 34343)
-- Name: class_names id; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.class_names ALTER COLUMN id SET DEFAULT nextval('core.class_names_id_seq'::regclass);


--
-- TOC entry 5376 (class 2604 OID 32463)
-- Name: course_format id; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.course_format ALTER COLUMN id SET DEFAULT nextval('core.course_format_id_seq'::regclass);


--
-- TOC entry 5388 (class 2604 OID 32519)
-- Name: grade_level id; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.grade_level ALTER COLUMN id SET DEFAULT nextval('core.grade_level_id_seq'::regclass);


--
-- TOC entry 5393 (class 2604 OID 32534)
-- Name: language_medium id; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.language_medium ALTER COLUMN id SET DEFAULT nextval('core.language_medium_id_seq'::regclass);


--
-- TOC entry 5379 (class 2604 OID 32478)
-- Name: lesson_type id; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.lesson_type ALTER COLUMN id SET DEFAULT nextval('core.lesson_type_id_seq'::regclass);


--
-- TOC entry 5385 (class 2604 OID 32506)
-- Name: notification_type id; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.notification_type ALTER COLUMN id SET DEFAULT nextval('core.notification_type_id_seq'::regclass);


--
-- TOC entry 5382 (class 2604 OID 32493)
-- Name: payment_method id; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.payment_method ALTER COLUMN id SET DEFAULT nextval('core.payment_method_id_seq'::regclass);


--
-- TOC entry 5441 (class 2604 OID 32766)
-- Name: subjects id; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.subjects ALTER COLUMN id SET DEFAULT nextval('core.subjects_id_seq'::regclass);


--
-- TOC entry 5661 (class 2604 OID 41049)
-- Name: teacher_availability id; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.teacher_availability ALTER COLUMN id SET DEFAULT nextval('core.teacher_availability_id_seq'::regclass);


--
-- TOC entry 5650 (class 2604 OID 40981)
-- Name: teacher_educations id; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.teacher_educations ALTER COLUMN id SET DEFAULT nextval('core.teacher_educations_id_seq'::regclass);


--
-- TOC entry 5653 (class 2604 OID 40997)
-- Name: teacher_experiences id; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.teacher_experiences ALTER COLUMN id SET DEFAULT nextval('core.teacher_experiences_id_seq'::regclass);


--
-- TOC entry 5658 (class 2604 OID 41032)
-- Name: teacher_teaching_styles id; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.teacher_teaching_styles ALTER COLUMN id SET DEFAULT nextval('core.teacher_teaching_styles_id_seq'::regclass);


--
-- TOC entry 5373 (class 2604 OID 32448)
-- Name: teacher_type id; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.teacher_type ALTER COLUMN id SET DEFAULT nextval('core.teacher_type_id_seq'::regclass);


--
-- TOC entry 5663 (class 2604 OID 41065)
-- Name: teacher_verifications id; Type: DEFAULT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.teacher_verifications ALTER COLUMN id SET DEFAULT nextval('core.teacher_verifications_id_seq'::regclass);


--
-- TOC entry 5468 (class 2604 OID 32892)
-- Name: course_requirements id; Type: DEFAULT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.course_requirements ALTER COLUMN id SET DEFAULT nextval('learn.course_requirements_id_seq'::regclass);


--
-- TOC entry 5848 (class 2606 OID 33885)
-- Name: audit_logs audit_logs_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.audit_logs
    ADD CONSTRAINT audit_logs_pkey PRIMARY KEY (id);


--
-- TOC entry 5815 (class 2606 OID 33727)
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- TOC entry 5813 (class 2606 OID 33717)
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- TOC entry 5717 (class 2606 OID 32633)
-- Name: email_verification_tokens email_verification_tokens_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.email_verification_tokens
    ADD CONSTRAINT email_verification_tokens_pkey PRIMARY KEY (id);


--
-- TOC entry 5719 (class 2606 OID 32635)
-- Name: email_verification_tokens email_verification_tokens_token_hash_key; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.email_verification_tokens
    ADD CONSTRAINT email_verification_tokens_token_hash_key UNIQUE (token_hash);


--
-- TOC entry 5822 (class 2606 OID 33774)
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 5824 (class 2606 OID 33776)
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- TOC entry 5820 (class 2606 OID 33757)
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- TOC entry 5817 (class 2606 OID 33742)
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 5811 (class 2606 OID 33707)
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- TOC entry 5836 (class 2606 OID 33822)
-- Name: model_has_permissions model_has_permissions_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.model_has_permissions
    ADD CONSTRAINT model_has_permissions_pkey PRIMARY KEY (permission_id, model_id, model_type);


--
-- TOC entry 5839 (class 2606 OID 33836)
-- Name: model_has_roles model_has_roles_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.model_has_roles
    ADD CONSTRAINT model_has_roles_pkey PRIMARY KEY (role_id, model_id, model_type);


--
-- TOC entry 5843 (class 2606 OID 33864)
-- Name: modules modules_module_name_unique; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.modules
    ADD CONSTRAINT modules_module_name_unique UNIQUE (module_name);


--
-- TOC entry 5845 (class 2606 OID 33862)
-- Name: modules modules_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.modules
    ADD CONSTRAINT modules_pkey PRIMARY KEY (id);


--
-- TOC entry 5864 (class 2606 OID 34026)
-- Name: nav_buttons nav_buttons_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.nav_buttons
    ADD CONSTRAINT nav_buttons_pkey PRIMARY KEY (id);


--
-- TOC entry 5858 (class 2606 OID 33988)
-- Name: nav_menus nav_menus_key_unique; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.nav_menus
    ADD CONSTRAINT nav_menus_key_unique UNIQUE (key);


--
-- TOC entry 5860 (class 2606 OID 33986)
-- Name: nav_menus nav_menus_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.nav_menus
    ADD CONSTRAINT nav_menus_pkey PRIMARY KEY (id);


--
-- TOC entry 5866 (class 2606 OID 34049)
-- Name: nav_parent_menus nav_parent_menus_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.nav_parent_menus
    ADD CONSTRAINT nav_parent_menus_pkey PRIMARY KEY (id);


--
-- TOC entry 5862 (class 2606 OID 34004)
-- Name: nav_submenus nav_submenus_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.nav_submenus
    ADD CONSTRAINT nav_submenus_pkey PRIMARY KEY (id);


--
-- TOC entry 5721 (class 2606 OID 32654)
-- Name: oauth_accounts oauth_accounts_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.oauth_accounts
    ADD CONSTRAINT oauth_accounts_pkey PRIMARY KEY (id);


--
-- TOC entry 5723 (class 2606 OID 32656)
-- Name: oauth_accounts oauth_accounts_provider_provider_user_id_key; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.oauth_accounts
    ADD CONSTRAINT oauth_accounts_provider_provider_user_id_key UNIQUE (provider, provider_user_id);


--
-- TOC entry 5894 (class 2606 OID 40976)
-- Name: otp_verifications otp_verifications_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.otp_verifications
    ADD CONSTRAINT otp_verifications_pkey PRIMARY KEY (id);


--
-- TOC entry 5713 (class 2606 OID 32610)
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (id);


--
-- TOC entry 5715 (class 2606 OID 32612)
-- Name: password_reset_tokens password_reset_tokens_token_hash_key; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_token_hash_key UNIQUE (token_hash);


--
-- TOC entry 5827 (class 2606 OID 33794)
-- Name: permissions permissions_name_guard_name_unique; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.permissions
    ADD CONSTRAINT permissions_name_guard_name_unique UNIQUE (name, guard_name);


--
-- TOC entry 5829 (class 2606 OID 33792)
-- Name: permissions permissions_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);


--
-- TOC entry 5889 (class 2606 OID 34394)
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- TOC entry 5891 (class 2606 OID 34397)
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- TOC entry 5868 (class 2606 OID 34100)
-- Name: role_nav_permissions rnp_unique; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.role_nav_permissions
    ADD CONSTRAINT rnp_unique UNIQUE (role_id, nav_item_type, nav_item_id);


--
-- TOC entry 5841 (class 2606 OID 33853)
-- Name: role_has_permissions role_has_permissions_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.role_has_permissions
    ADD CONSTRAINT role_has_permissions_pkey PRIMARY KEY (permission_id, role_id);


--
-- TOC entry 5870 (class 2606 OID 34098)
-- Name: role_nav_permissions role_nav_permissions_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.role_nav_permissions
    ADD CONSTRAINT role_nav_permissions_pkey PRIMARY KEY (id);


--
-- TOC entry 5831 (class 2606 OID 33808)
-- Name: roles roles_name_guard_name_unique; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.roles
    ADD CONSTRAINT roles_name_guard_name_unique UNIQUE (name, guard_name);


--
-- TOC entry 5833 (class 2606 OID 33806)
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- TOC entry 5709 (class 2606 OID 32587)
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- TOC entry 5711 (class 2606 OID 32589)
-- Name: sessions sessions_refresh_token_key; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.sessions
    ADD CONSTRAINT sessions_refresh_token_key UNIQUE (refresh_token);


--
-- TOC entry 5856 (class 2606 OID 33932)
-- Name: sub_modules sub_modules_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.sub_modules
    ADD CONSTRAINT sub_modules_pkey PRIMARY KEY (id);


--
-- TOC entry 5872 (class 2606 OID 34120)
-- Name: user_nav_permissions unp_unique; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.user_nav_permissions
    ADD CONSTRAINT unp_unique UNIQUE (user_id, nav_item_type, nav_item_id);


--
-- TOC entry 5874 (class 2606 OID 34118)
-- Name: user_nav_permissions user_nav_permissions_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.user_nav_permissions
    ADD CONSTRAINT user_nav_permissions_pkey PRIMARY KEY (id);


--
-- TOC entry 5701 (class 2606 OID 32569)
-- Name: users users_email_key; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.users
    ADD CONSTRAINT users_email_key UNIQUE (email);


--
-- TOC entry 5703 (class 2606 OID 32571)
-- Name: users users_phone_key; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.users
    ADD CONSTRAINT users_phone_key UNIQUE (phone);


--
-- TOC entry 5705 (class 2606 OID 32567)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 5707 (class 2606 OID 33780)
-- Name: users users_username_unique; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.users
    ADD CONSTRAINT users_username_unique UNIQUE (username);


--
-- TOC entry 5853 (class 2606 OID 33900)
-- Name: web_sessions web_sessions_pkey; Type: CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.web_sessions
    ADD CONSTRAINT web_sessions_pkey PRIMARY KEY (id);


--
-- TOC entry 5769 (class 2606 OID 33221)
-- Name: coupons coupons_code_key; Type: CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.coupons
    ADD CONSTRAINT coupons_code_key UNIQUE (code);


--
-- TOC entry 5771 (class 2606 OID 33219)
-- Name: coupons coupons_pkey; Type: CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.coupons
    ADD CONSTRAINT coupons_pkey PRIMARY KEY (id);


--
-- TOC entry 5763 (class 2606 OID 33140)
-- Name: enrollments enrollments_pkey; Type: CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.enrollments
    ADD CONSTRAINT enrollments_pkey PRIMARY KEY (id);


--
-- TOC entry 5765 (class 2606 OID 33142)
-- Name: enrollments enrollments_student_id_course_id_key; Type: CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.enrollments
    ADD CONSTRAINT enrollments_student_id_course_id_key UNIQUE (student_id, course_id);


--
-- TOC entry 5773 (class 2606 OID 33260)
-- Name: payments payments_gateway_txn_id_key; Type: CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.payments
    ADD CONSTRAINT payments_gateway_txn_id_key UNIQUE (gateway_txn_id);


--
-- TOC entry 5775 (class 2606 OID 33262)
-- Name: payments payments_invoice_number_key; Type: CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.payments
    ADD CONSTRAINT payments_invoice_number_key UNIQUE (invoice_number);


--
-- TOC entry 5777 (class 2606 OID 33258)
-- Name: payments payments_pkey; Type: CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.payments
    ADD CONSTRAINT payments_pkey PRIMARY KEY (id);


--
-- TOC entry 5781 (class 2606 OID 33328)
-- Name: payouts payouts_pkey; Type: CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.payouts
    ADD CONSTRAINT payouts_pkey PRIMARY KEY (id);


--
-- TOC entry 5767 (class 2606 OID 33182)
-- Name: subscriptions subscriptions_pkey; Type: CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.subscriptions
    ADD CONSTRAINT subscriptions_pkey PRIMARY KEY (id);


--
-- TOC entry 5779 (class 2606 OID 33306)
-- Name: teacher_wallet teacher_wallet_pkey; Type: CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.teacher_wallet
    ADD CONSTRAINT teacher_wallet_pkey PRIMARY KEY (teacher_id);


--
-- TOC entry 5783 (class 2606 OID 33349)
-- Name: wallet_transactions wallet_transactions_pkey; Type: CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.wallet_transactions
    ADD CONSTRAINT wallet_transactions_pkey PRIMARY KEY (id);


--
-- TOC entry 5807 (class 2606 OID 33647)
-- Name: announcements announcements_pkey; Type: CONSTRAINT; Schema: comms; Owner: -
--

ALTER TABLE ONLY comms.announcements
    ADD CONSTRAINT announcements_pkey PRIMARY KEY (id);


--
-- TOC entry 5809 (class 2606 OID 33681)
-- Name: notifications notifications_pkey; Type: CONSTRAINT; Schema: comms; Owner: -
--

ALTER TABLE ONLY comms.notifications
    ADD CONSTRAINT notifications_pkey PRIMARY KEY (id);


--
-- TOC entry 5803 (class 2606 OID 33605)
-- Name: reviews reviews_course_id_student_id_key; Type: CONSTRAINT; Schema: comms; Owner: -
--

ALTER TABLE ONLY comms.reviews
    ADD CONSTRAINT reviews_course_id_student_id_key UNIQUE (course_id, student_id);


--
-- TOC entry 5805 (class 2606 OID 33603)
-- Name: reviews reviews_pkey; Type: CONSTRAINT; Schema: comms; Owner: -
--

ALTER TABLE ONLY comms.reviews
    ADD CONSTRAINT reviews_pkey PRIMARY KEY (id);


--
-- TOC entry 5886 (class 2606 OID 34353)
-- Name: class_names class_names_pkey; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.class_names
    ADD CONSTRAINT class_names_pkey PRIMARY KEY (id);


--
-- TOC entry 5880 (class 2606 OID 34296)
-- Name: class_schedule class_schedule_pkey; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.class_schedule
    ADD CONSTRAINT class_schedule_pkey PRIMARY KEY (id);


--
-- TOC entry 5882 (class 2606 OID 34315)
-- Name: class_students class_students_pkey; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.class_students
    ADD CONSTRAINT class_students_pkey PRIMARY KEY (id);


--
-- TOC entry 5876 (class 2606 OID 34277)
-- Name: classes classes_pkey; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.classes
    ADD CONSTRAINT classes_pkey PRIMARY KEY (id);


--
-- TOC entry 5884 (class 2606 OID 34313)
-- Name: class_students core_class_students_class_room_id_student_id_unique; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.class_students
    ADD CONSTRAINT core_class_students_class_room_id_student_id_unique UNIQUE (class_room_id, student_id);


--
-- TOC entry 5878 (class 2606 OID 34279)
-- Name: classes core_classes_slug_unique; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.classes
    ADD CONSTRAINT core_classes_slug_unique UNIQUE (slug);


--
-- TOC entry 5909 (class 2606 OID 41059)
-- Name: teacher_availability core_teacher_availability_user_id_day_of_week_time_slot_unique; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.teacher_availability
    ADD CONSTRAINT core_teacher_availability_user_id_day_of_week_time_slot_unique UNIQUE (user_id, day_of_week, time_slot);


--
-- TOC entry 5679 (class 2606 OID 32473)
-- Name: course_format course_format_name_key; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.course_format
    ADD CONSTRAINT course_format_name_key UNIQUE (name);


--
-- TOC entry 5681 (class 2606 OID 32471)
-- Name: course_format course_format_pkey; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.course_format
    ADD CONSTRAINT course_format_pkey PRIMARY KEY (id);


--
-- TOC entry 5693 (class 2606 OID 32529)
-- Name: grade_level grade_level_name_key; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.grade_level
    ADD CONSTRAINT grade_level_name_key UNIQUE (name);


--
-- TOC entry 5695 (class 2606 OID 32527)
-- Name: grade_level grade_level_pkey; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.grade_level
    ADD CONSTRAINT grade_level_pkey PRIMARY KEY (id);


--
-- TOC entry 5697 (class 2606 OID 32542)
-- Name: language_medium language_medium_name_key; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.language_medium
    ADD CONSTRAINT language_medium_name_key UNIQUE (name);


--
-- TOC entry 5699 (class 2606 OID 32540)
-- Name: language_medium language_medium_pkey; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.language_medium
    ADD CONSTRAINT language_medium_pkey PRIMARY KEY (id);


--
-- TOC entry 5683 (class 2606 OID 32488)
-- Name: lesson_type lesson_type_name_key; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.lesson_type
    ADD CONSTRAINT lesson_type_name_key UNIQUE (name);


--
-- TOC entry 5685 (class 2606 OID 32486)
-- Name: lesson_type lesson_type_pkey; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.lesson_type
    ADD CONSTRAINT lesson_type_pkey PRIMARY KEY (id);


--
-- TOC entry 5737 (class 2606 OID 32808)
-- Name: media media_pkey; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.media
    ADD CONSTRAINT media_pkey PRIMARY KEY (id);


--
-- TOC entry 5739 (class 2606 OID 32810)
-- Name: media media_storage_key_key; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.media
    ADD CONSTRAINT media_storage_key_key UNIQUE (storage_key);


--
-- TOC entry 5691 (class 2606 OID 32514)
-- Name: notification_type notification_type_pkey; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.notification_type
    ADD CONSTRAINT notification_type_pkey PRIMARY KEY (id);


--
-- TOC entry 5687 (class 2606 OID 32501)
-- Name: payment_method payment_method_name_key; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.payment_method
    ADD CONSTRAINT payment_method_name_key UNIQUE (name);


--
-- TOC entry 5689 (class 2606 OID 32499)
-- Name: payment_method payment_method_pkey; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.payment_method
    ADD CONSTRAINT payment_method_pkey PRIMARY KEY (id);


--
-- TOC entry 5725 (class 2606 OID 32683)
-- Name: profiles profiles_pkey; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.profiles
    ADD CONSTRAINT profiles_pkey PRIMARY KEY (id);


--
-- TOC entry 5727 (class 2606 OID 32700)
-- Name: student_profiles student_profiles_pkey; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.student_profiles
    ADD CONSTRAINT student_profiles_pkey PRIMARY KEY (user_id);


--
-- TOC entry 5731 (class 2606 OID 32779)
-- Name: subjects subjects_name_key; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.subjects
    ADD CONSTRAINT subjects_name_key UNIQUE (name);


--
-- TOC entry 5733 (class 2606 OID 32777)
-- Name: subjects subjects_pkey; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.subjects
    ADD CONSTRAINT subjects_pkey PRIMARY KEY (id);


--
-- TOC entry 5735 (class 2606 OID 32781)
-- Name: subjects subjects_slug_key; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.subjects
    ADD CONSTRAINT subjects_slug_key UNIQUE (slug);


--
-- TOC entry 5912 (class 2606 OID 41057)
-- Name: teacher_availability teacher_availability_pkey; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.teacher_availability
    ADD CONSTRAINT teacher_availability_pkey PRIMARY KEY (id);


--
-- TOC entry 5897 (class 2606 OID 40991)
-- Name: teacher_educations teacher_educations_pkey; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.teacher_educations
    ADD CONSTRAINT teacher_educations_pkey PRIMARY KEY (id);


--
-- TOC entry 5900 (class 2606 OID 41008)
-- Name: teacher_experiences teacher_experiences_pkey; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.teacher_experiences
    ADD CONSTRAINT teacher_experiences_pkey PRIMARY KEY (id);


--
-- TOC entry 5904 (class 2606 OID 41027)
-- Name: teacher_grade_levels teacher_grade_levels_pkey; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.teacher_grade_levels
    ADD CONSTRAINT teacher_grade_levels_pkey PRIMARY KEY (user_id, grade_level_id);


--
-- TOC entry 5729 (class 2606 OID 32746)
-- Name: teacher_profiles teacher_profiles_pkey; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.teacher_profiles
    ADD CONSTRAINT teacher_profiles_pkey PRIMARY KEY (user_id);


--
-- TOC entry 5902 (class 2606 OID 41018)
-- Name: teacher_subjects teacher_subjects_pkey; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.teacher_subjects
    ADD CONSTRAINT teacher_subjects_pkey PRIMARY KEY (user_id, subject_id);


--
-- TOC entry 5907 (class 2606 OID 41043)
-- Name: teacher_teaching_styles teacher_teaching_styles_pkey; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.teacher_teaching_styles
    ADD CONSTRAINT teacher_teaching_styles_pkey PRIMARY KEY (id);


--
-- TOC entry 5675 (class 2606 OID 32458)
-- Name: teacher_type teacher_type_name_key; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.teacher_type
    ADD CONSTRAINT teacher_type_name_key UNIQUE (name);


--
-- TOC entry 5677 (class 2606 OID 32456)
-- Name: teacher_type teacher_type_pkey; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.teacher_type
    ADD CONSTRAINT teacher_type_pkey PRIMARY KEY (id);


--
-- TOC entry 5915 (class 2606 OID 41077)
-- Name: teacher_verifications teacher_verifications_pkey; Type: CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.teacher_verifications
    ADD CONSTRAINT teacher_verifications_pkey PRIMARY KEY (id);


--
-- TOC entry 5793 (class 2606 OID 33521)
-- Name: assignment_submissions assignment_submissions_assignment_id_student_id_key; Type: CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.assignment_submissions
    ADD CONSTRAINT assignment_submissions_assignment_id_student_id_key UNIQUE (assignment_id, student_id);


--
-- TOC entry 5795 (class 2606 OID 33519)
-- Name: assignment_submissions assignment_submissions_pkey; Type: CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.assignment_submissions
    ADD CONSTRAINT assignment_submissions_pkey PRIMARY KEY (id);


--
-- TOC entry 5791 (class 2606 OID 33478)
-- Name: assignments assignments_pkey; Type: CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.assignments
    ADD CONSTRAINT assignments_pkey PRIMARY KEY (id);


--
-- TOC entry 5797 (class 2606 OID 33559)
-- Name: certificates certificates_certificate_number_key; Type: CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.certificates
    ADD CONSTRAINT certificates_certificate_number_key UNIQUE (certificate_number);


--
-- TOC entry 5799 (class 2606 OID 33557)
-- Name: certificates certificates_pkey; Type: CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.certificates
    ADD CONSTRAINT certificates_pkey PRIMARY KEY (id);


--
-- TOC entry 5801 (class 2606 OID 33561)
-- Name: certificates certificates_student_id_course_id_key; Type: CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.certificates
    ADD CONSTRAINT certificates_student_id_course_id_key UNIQUE (student_id, course_id);


--
-- TOC entry 5745 (class 2606 OID 32903)
-- Name: course_requirements course_requirements_pkey; Type: CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.course_requirements
    ADD CONSTRAINT course_requirements_pkey PRIMARY KEY (id);


--
-- TOC entry 5755 (class 2606 OID 33046)
-- Name: course_schedule course_schedule_pkey; Type: CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.course_schedule
    ADD CONSTRAINT course_schedule_pkey PRIMARY KEY (id);


--
-- TOC entry 5741 (class 2606 OID 32865)
-- Name: courses courses_pkey; Type: CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.courses
    ADD CONSTRAINT courses_pkey PRIMARY KEY (id);


--
-- TOC entry 5743 (class 2606 OID 32867)
-- Name: courses courses_slug_key; Type: CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.courses
    ADD CONSTRAINT courses_slug_key UNIQUE (slug);


--
-- TOC entry 5785 (class 2606 OID 33387)
-- Name: lesson_progress lesson_progress_pkey; Type: CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.lesson_progress
    ADD CONSTRAINT lesson_progress_pkey PRIMARY KEY (id);


--
-- TOC entry 5787 (class 2606 OID 33389)
-- Name: lesson_progress lesson_progress_student_id_lesson_id_key; Type: CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.lesson_progress
    ADD CONSTRAINT lesson_progress_student_id_lesson_id_key UNIQUE (student_id, lesson_id);


--
-- TOC entry 5749 (class 2606 OID 32958)
-- Name: lessons lessons_pkey; Type: CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.lessons
    ADD CONSTRAINT lessons_pkey PRIMARY KEY (id);


--
-- TOC entry 5757 (class 2606 OID 33077)
-- Name: live_sessions live_sessions_pkey; Type: CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.live_sessions
    ADD CONSTRAINT live_sessions_pkey PRIMARY KEY (id);


--
-- TOC entry 5789 (class 2606 OID 33430)
-- Name: quiz_attempts quiz_attempts_pkey; Type: CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.quiz_attempts
    ADD CONSTRAINT quiz_attempts_pkey PRIMARY KEY (id);


--
-- TOC entry 5753 (class 2606 OID 33024)
-- Name: quiz_questions quiz_questions_pkey; Type: CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.quiz_questions
    ADD CONSTRAINT quiz_questions_pkey PRIMARY KEY (id);


--
-- TOC entry 5751 (class 2606 OID 32996)
-- Name: quizzes quizzes_pkey; Type: CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.quizzes
    ADD CONSTRAINT quizzes_pkey PRIMARY KEY (id);


--
-- TOC entry 5747 (class 2606 OID 32927)
-- Name: sections sections_pkey; Type: CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.sections
    ADD CONSTRAINT sections_pkey PRIMARY KEY (id);


--
-- TOC entry 5759 (class 2606 OID 33105)
-- Name: session_attendance session_attendance_pkey; Type: CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.session_attendance
    ADD CONSTRAINT session_attendance_pkey PRIMARY KEY (id);


--
-- TOC entry 5761 (class 2606 OID 33107)
-- Name: session_attendance session_attendance_session_id_student_id_key; Type: CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.session_attendance
    ADD CONSTRAINT session_attendance_session_id_student_id_key UNIQUE (session_id, student_id);


--
-- TOC entry 5846 (class 1259 OID 33887)
-- Name: audit_logs_ip_address_index; Type: INDEX; Schema: auth; Owner: -
--

CREATE INDEX audit_logs_ip_address_index ON auth.audit_logs USING btree (ip_address);


--
-- TOC entry 5849 (class 1259 OID 33886)
-- Name: audit_logs_user_id_created_at_index; Type: INDEX; Schema: auth; Owner: -
--

CREATE INDEX audit_logs_user_id_created_at_index ON auth.audit_logs USING btree (user_id, created_at);


--
-- TOC entry 5850 (class 1259 OID 33888)
-- Name: audit_logs_user_id_index; Type: INDEX; Schema: auth; Owner: -
--

CREATE INDEX audit_logs_user_id_index ON auth.audit_logs USING btree (user_id);


--
-- TOC entry 5818 (class 1259 OID 33743)
-- Name: jobs_queue_index; Type: INDEX; Schema: auth; Owner: -
--

CREATE INDEX jobs_queue_index ON auth.jobs USING btree (queue);


--
-- TOC entry 5834 (class 1259 OID 33815)
-- Name: model_has_permissions_model_id_model_type_index; Type: INDEX; Schema: auth; Owner: -
--

CREATE INDEX model_has_permissions_model_id_model_type_index ON auth.model_has_permissions USING btree (model_id, model_type);


--
-- TOC entry 5837 (class 1259 OID 33829)
-- Name: model_has_roles_model_id_model_type_index; Type: INDEX; Schema: auth; Owner: -
--

CREATE INDEX model_has_roles_model_id_model_type_index ON auth.model_has_roles USING btree (model_id, model_type);


--
-- TOC entry 5825 (class 1259 OID 33866)
-- Name: permissions_module_id_index; Type: INDEX; Schema: auth; Owner: -
--

CREATE INDEX permissions_module_id_index ON auth.permissions USING btree (module_id);


--
-- TOC entry 5887 (class 1259 OID 34398)
-- Name: personal_access_tokens_expires_at_index; Type: INDEX; Schema: auth; Owner: -
--

CREATE INDEX personal_access_tokens_expires_at_index ON auth.personal_access_tokens USING btree (expires_at);


--
-- TOC entry 5892 (class 1259 OID 34395)
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: auth; Owner: -
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON auth.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- TOC entry 5851 (class 1259 OID 33902)
-- Name: web_sessions_last_activity_index; Type: INDEX; Schema: auth; Owner: -
--

CREATE INDEX web_sessions_last_activity_index ON auth.web_sessions USING btree (last_activity);


--
-- TOC entry 5854 (class 1259 OID 33901)
-- Name: web_sessions_user_id_index; Type: INDEX; Schema: auth; Owner: -
--

CREATE INDEX web_sessions_user_id_index ON auth.web_sessions USING btree (user_id);


--
-- TOC entry 5910 (class 1259 OID 41060)
-- Name: core_teacher_availability_user_id_index; Type: INDEX; Schema: core; Owner: -
--

CREATE INDEX core_teacher_availability_user_id_index ON core.teacher_availability USING btree (user_id);


--
-- TOC entry 5895 (class 1259 OID 40992)
-- Name: core_teacher_educations_user_id_index; Type: INDEX; Schema: core; Owner: -
--

CREATE INDEX core_teacher_educations_user_id_index ON core.teacher_educations USING btree (user_id);


--
-- TOC entry 5898 (class 1259 OID 41009)
-- Name: core_teacher_experiences_user_id_index; Type: INDEX; Schema: core; Owner: -
--

CREATE INDEX core_teacher_experiences_user_id_index ON core.teacher_experiences USING btree (user_id);


--
-- TOC entry 5905 (class 1259 OID 41044)
-- Name: core_teacher_teaching_styles_user_id_index; Type: INDEX; Schema: core; Owner: -
--

CREATE INDEX core_teacher_teaching_styles_user_id_index ON core.teacher_teaching_styles USING btree (user_id);


--
-- TOC entry 5913 (class 1259 OID 41078)
-- Name: core_teacher_verifications_user_id_index; Type: INDEX; Schema: core; Owner: -
--

CREATE INDEX core_teacher_verifications_user_id_index ON core.teacher_verifications USING btree (user_id);


--
-- TOC entry 5918 (class 2606 OID 32636)
-- Name: email_verification_tokens email_verification_tokens_user_id_fkey; Type: FK CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.email_verification_tokens
    ADD CONSTRAINT email_verification_tokens_user_id_fkey FOREIGN KEY (user_id) REFERENCES auth.users(id) ON DELETE CASCADE;


--
-- TOC entry 5993 (class 2606 OID 33816)
-- Name: model_has_permissions model_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.model_has_permissions
    ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES auth.permissions(id) ON DELETE CASCADE;


--
-- TOC entry 5994 (class 2606 OID 33830)
-- Name: model_has_roles model_has_roles_role_id_foreign; Type: FK CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.model_has_roles
    ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES auth.roles(id) ON DELETE CASCADE;


--
-- TOC entry 6000 (class 2606 OID 34027)
-- Name: nav_buttons nav_buttons_submenu_id_foreign; Type: FK CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.nav_buttons
    ADD CONSTRAINT nav_buttons_submenu_id_foreign FOREIGN KEY (submenu_id) REFERENCES auth.nav_submenus(id) ON DELETE CASCADE;


--
-- TOC entry 5998 (class 2606 OID 34050)
-- Name: nav_menus nav_menus_parent_menu_id_foreign; Type: FK CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.nav_menus
    ADD CONSTRAINT nav_menus_parent_menu_id_foreign FOREIGN KEY (parent_menu_id) REFERENCES auth.nav_parent_menus(id) ON DELETE SET NULL;


--
-- TOC entry 5999 (class 2606 OID 34005)
-- Name: nav_submenus nav_submenus_menu_id_foreign; Type: FK CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.nav_submenus
    ADD CONSTRAINT nav_submenus_menu_id_foreign FOREIGN KEY (menu_id) REFERENCES auth.nav_menus(id) ON DELETE CASCADE;


--
-- TOC entry 5919 (class 2606 OID 32657)
-- Name: oauth_accounts oauth_accounts_user_id_fkey; Type: FK CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.oauth_accounts
    ADD CONSTRAINT oauth_accounts_user_id_fkey FOREIGN KEY (user_id) REFERENCES auth.users(id) ON DELETE CASCADE;


--
-- TOC entry 5917 (class 2606 OID 32613)
-- Name: password_reset_tokens password_reset_tokens_user_id_fkey; Type: FK CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_user_id_fkey FOREIGN KEY (user_id) REFERENCES auth.users(id) ON DELETE CASCADE;


--
-- TOC entry 5991 (class 2606 OID 33868)
-- Name: permissions permissions_module_id_foreign; Type: FK CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.permissions
    ADD CONSTRAINT permissions_module_id_foreign FOREIGN KEY (module_id) REFERENCES auth.modules(id) ON DELETE CASCADE;


--
-- TOC entry 5992 (class 2606 OID 33938)
-- Name: permissions permissions_sub_module_id_foreign; Type: FK CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.permissions
    ADD CONSTRAINT permissions_sub_module_id_foreign FOREIGN KEY (sub_module_id) REFERENCES auth.sub_modules(id) ON DELETE SET NULL;


--
-- TOC entry 5995 (class 2606 OID 33842)
-- Name: role_has_permissions role_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.role_has_permissions
    ADD CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES auth.permissions(id) ON DELETE CASCADE;


--
-- TOC entry 5996 (class 2606 OID 33847)
-- Name: role_has_permissions role_has_permissions_role_id_foreign; Type: FK CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.role_has_permissions
    ADD CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES auth.roles(id) ON DELETE CASCADE;


--
-- TOC entry 6001 (class 2606 OID 34101)
-- Name: role_nav_permissions role_nav_permissions_role_id_foreign; Type: FK CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.role_nav_permissions
    ADD CONSTRAINT role_nav_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES auth.roles(id) ON DELETE CASCADE;


--
-- TOC entry 5916 (class 2606 OID 32590)
-- Name: sessions sessions_user_id_fkey; Type: FK CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.sessions
    ADD CONSTRAINT sessions_user_id_fkey FOREIGN KEY (user_id) REFERENCES auth.users(id) ON DELETE CASCADE;


--
-- TOC entry 5997 (class 2606 OID 33933)
-- Name: sub_modules sub_modules_module_id_foreign; Type: FK CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.sub_modules
    ADD CONSTRAINT sub_modules_module_id_foreign FOREIGN KEY (module_id) REFERENCES auth.modules(id) ON DELETE CASCADE;


--
-- TOC entry 6002 (class 2606 OID 34121)
-- Name: user_nav_permissions user_nav_permissions_user_id_foreign; Type: FK CONSTRAINT; Schema: auth; Owner: -
--

ALTER TABLE ONLY auth.user_nav_permissions
    ADD CONSTRAINT user_nav_permissions_user_id_foreign FOREIGN KEY (user_id) REFERENCES auth.users(id) ON DELETE CASCADE;


--
-- TOC entry 5953 (class 2606 OID 33227)
-- Name: coupons coupons_course_id_fkey; Type: FK CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.coupons
    ADD CONSTRAINT coupons_course_id_fkey FOREIGN KEY (course_id) REFERENCES learn.courses(id);


--
-- TOC entry 5954 (class 2606 OID 33222)
-- Name: coupons coupons_teacher_id_fkey; Type: FK CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.coupons
    ADD CONSTRAINT coupons_teacher_id_fkey FOREIGN KEY (teacher_id) REFERENCES auth.users(id);


--
-- TOC entry 5946 (class 2606 OID 33148)
-- Name: enrollments enrollments_course_id_fkey; Type: FK CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.enrollments
    ADD CONSTRAINT enrollments_course_id_fkey FOREIGN KEY (course_id) REFERENCES learn.courses(id);


--
-- TOC entry 5947 (class 2606 OID 33153)
-- Name: enrollments enrollments_last_lesson_id_fkey; Type: FK CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.enrollments
    ADD CONSTRAINT enrollments_last_lesson_id_fkey FOREIGN KEY (last_lesson_id) REFERENCES learn.lessons(id);


--
-- TOC entry 5948 (class 2606 OID 33143)
-- Name: enrollments enrollments_student_id_fkey; Type: FK CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.enrollments
    ADD CONSTRAINT enrollments_student_id_fkey FOREIGN KEY (student_id) REFERENCES auth.users(id);


--
-- TOC entry 5949 (class 2606 OID 33692)
-- Name: enrollments fk_certificate; Type: FK CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.enrollments
    ADD CONSTRAINT fk_certificate FOREIGN KEY (certificate_id) REFERENCES learn.certificates(id);


--
-- TOC entry 5955 (class 2606 OID 33283)
-- Name: payments payments_coupon_id_fkey; Type: FK CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.payments
    ADD CONSTRAINT payments_coupon_id_fkey FOREIGN KEY (coupon_id) REFERENCES commerce.coupons(id);


--
-- TOC entry 5956 (class 2606 OID 33268)
-- Name: payments payments_course_id_fkey; Type: FK CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.payments
    ADD CONSTRAINT payments_course_id_fkey FOREIGN KEY (course_id) REFERENCES learn.courses(id);


--
-- TOC entry 5957 (class 2606 OID 33273)
-- Name: payments payments_enrollment_id_fkey; Type: FK CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.payments
    ADD CONSTRAINT payments_enrollment_id_fkey FOREIGN KEY (enrollment_id) REFERENCES commerce.enrollments(id);


--
-- TOC entry 5958 (class 2606 OID 33288)
-- Name: payments payments_method_id_fkey; Type: FK CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.payments
    ADD CONSTRAINT payments_method_id_fkey FOREIGN KEY (method_id) REFERENCES core.payment_method(id);


--
-- TOC entry 5959 (class 2606 OID 33263)
-- Name: payments payments_student_id_fkey; Type: FK CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.payments
    ADD CONSTRAINT payments_student_id_fkey FOREIGN KEY (student_id) REFERENCES auth.users(id);


--
-- TOC entry 5960 (class 2606 OID 33278)
-- Name: payments payments_subscription_id_fkey; Type: FK CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.payments
    ADD CONSTRAINT payments_subscription_id_fkey FOREIGN KEY (subscription_id) REFERENCES commerce.subscriptions(id);


--
-- TOC entry 5962 (class 2606 OID 33329)
-- Name: payouts payouts_teacher_id_fkey; Type: FK CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.payouts
    ADD CONSTRAINT payouts_teacher_id_fkey FOREIGN KEY (teacher_id) REFERENCES auth.users(id);


--
-- TOC entry 5950 (class 2606 OID 33193)
-- Name: subscriptions subscriptions_course_id_fkey; Type: FK CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.subscriptions
    ADD CONSTRAINT subscriptions_course_id_fkey FOREIGN KEY (course_id) REFERENCES learn.courses(id);


--
-- TOC entry 5951 (class 2606 OID 33183)
-- Name: subscriptions subscriptions_enrollment_id_fkey; Type: FK CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.subscriptions
    ADD CONSTRAINT subscriptions_enrollment_id_fkey FOREIGN KEY (enrollment_id) REFERENCES commerce.enrollments(id);


--
-- TOC entry 5952 (class 2606 OID 33188)
-- Name: subscriptions subscriptions_student_id_fkey; Type: FK CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.subscriptions
    ADD CONSTRAINT subscriptions_student_id_fkey FOREIGN KEY (student_id) REFERENCES auth.users(id);


--
-- TOC entry 5961 (class 2606 OID 33307)
-- Name: teacher_wallet teacher_wallet_teacher_id_fkey; Type: FK CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.teacher_wallet
    ADD CONSTRAINT teacher_wallet_teacher_id_fkey FOREIGN KEY (teacher_id) REFERENCES auth.users(id);


--
-- TOC entry 5963 (class 2606 OID 33355)
-- Name: wallet_transactions wallet_transactions_payment_id_fkey; Type: FK CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.wallet_transactions
    ADD CONSTRAINT wallet_transactions_payment_id_fkey FOREIGN KEY (payment_id) REFERENCES commerce.payments(id);


--
-- TOC entry 5964 (class 2606 OID 33360)
-- Name: wallet_transactions wallet_transactions_payout_id_fkey; Type: FK CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.wallet_transactions
    ADD CONSTRAINT wallet_transactions_payout_id_fkey FOREIGN KEY (payout_id) REFERENCES commerce.payouts(id);


--
-- TOC entry 5965 (class 2606 OID 33350)
-- Name: wallet_transactions wallet_transactions_teacher_id_fkey; Type: FK CONSTRAINT; Schema: commerce; Owner: -
--

ALTER TABLE ONLY commerce.wallet_transactions
    ADD CONSTRAINT wallet_transactions_teacher_id_fkey FOREIGN KEY (teacher_id) REFERENCES auth.users(id);


--
-- TOC entry 5987 (class 2606 OID 33653)
-- Name: announcements announcements_course_id_fkey; Type: FK CONSTRAINT; Schema: comms; Owner: -
--

ALTER TABLE ONLY comms.announcements
    ADD CONSTRAINT announcements_course_id_fkey FOREIGN KEY (course_id) REFERENCES learn.courses(id);


--
-- TOC entry 5988 (class 2606 OID 33648)
-- Name: announcements announcements_teacher_id_fkey; Type: FK CONSTRAINT; Schema: comms; Owner: -
--

ALTER TABLE ONLY comms.announcements
    ADD CONSTRAINT announcements_teacher_id_fkey FOREIGN KEY (teacher_id) REFERENCES auth.users(id);


--
-- TOC entry 5989 (class 2606 OID 33687)
-- Name: notifications notifications_type_id_fkey; Type: FK CONSTRAINT; Schema: comms; Owner: -
--

ALTER TABLE ONLY comms.notifications
    ADD CONSTRAINT notifications_type_id_fkey FOREIGN KEY (type_id) REFERENCES core.notification_type(id);


--
-- TOC entry 5990 (class 2606 OID 33682)
-- Name: notifications notifications_user_id_fkey; Type: FK CONSTRAINT; Schema: comms; Owner: -
--

ALTER TABLE ONLY comms.notifications
    ADD CONSTRAINT notifications_user_id_fkey FOREIGN KEY (user_id) REFERENCES auth.users(id) ON DELETE CASCADE;


--
-- TOC entry 5983 (class 2606 OID 33606)
-- Name: reviews reviews_course_id_fkey; Type: FK CONSTRAINT; Schema: comms; Owner: -
--

ALTER TABLE ONLY comms.reviews
    ADD CONSTRAINT reviews_course_id_fkey FOREIGN KEY (course_id) REFERENCES learn.courses(id) ON DELETE CASCADE;


--
-- TOC entry 5984 (class 2606 OID 33621)
-- Name: reviews reviews_enrollment_id_fkey; Type: FK CONSTRAINT; Schema: comms; Owner: -
--

ALTER TABLE ONLY comms.reviews
    ADD CONSTRAINT reviews_enrollment_id_fkey FOREIGN KEY (enrollment_id) REFERENCES commerce.enrollments(id);


--
-- TOC entry 5985 (class 2606 OID 33616)
-- Name: reviews reviews_student_id_fkey; Type: FK CONSTRAINT; Schema: comms; Owner: -
--

ALTER TABLE ONLY comms.reviews
    ADD CONSTRAINT reviews_student_id_fkey FOREIGN KEY (student_id) REFERENCES auth.users(id);


--
-- TOC entry 5986 (class 2606 OID 33611)
-- Name: reviews reviews_teacher_id_fkey; Type: FK CONSTRAINT; Schema: comms; Owner: -
--

ALTER TABLE ONLY comms.reviews
    ADD CONSTRAINT reviews_teacher_id_fkey FOREIGN KEY (teacher_id) REFERENCES auth.users(id);


--
-- TOC entry 5927 (class 2606 OID 32811)
-- Name: media media_uploader_id_fkey; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.media
    ADD CONSTRAINT media_uploader_id_fkey FOREIGN KEY (uploader_id) REFERENCES auth.users(id);


--
-- TOC entry 5920 (class 2606 OID 32684)
-- Name: profiles profiles_id_fkey; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.profiles
    ADD CONSTRAINT profiles_id_fkey FOREIGN KEY (id) REFERENCES auth.users(id) ON DELETE CASCADE;


--
-- TOC entry 5921 (class 2606 OID 32706)
-- Name: student_profiles student_profiles_grade_level_id_fkey; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.student_profiles
    ADD CONSTRAINT student_profiles_grade_level_id_fkey FOREIGN KEY (grade_level_id) REFERENCES core.grade_level(id);


--
-- TOC entry 5922 (class 2606 OID 32701)
-- Name: student_profiles student_profiles_user_id_fkey; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.student_profiles
    ADD CONSTRAINT student_profiles_user_id_fkey FOREIGN KEY (user_id) REFERENCES auth.users(id) ON DELETE CASCADE;


--
-- TOC entry 5926 (class 2606 OID 32782)
-- Name: subjects subjects_parent_id_fkey; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.subjects
    ADD CONSTRAINT subjects_parent_id_fkey FOREIGN KEY (parent_id) REFERENCES core.subjects(id);


--
-- TOC entry 5923 (class 2606 OID 32752)
-- Name: teacher_profiles teacher_profiles_teacher_type_id_fkey; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.teacher_profiles
    ADD CONSTRAINT teacher_profiles_teacher_type_id_fkey FOREIGN KEY (teacher_type_id) REFERENCES core.teacher_type(id);


--
-- TOC entry 5924 (class 2606 OID 32747)
-- Name: teacher_profiles teacher_profiles_user_id_fkey; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.teacher_profiles
    ADD CONSTRAINT teacher_profiles_user_id_fkey FOREIGN KEY (user_id) REFERENCES auth.users(id) ON DELETE CASCADE;


--
-- TOC entry 5925 (class 2606 OID 32757)
-- Name: teacher_profiles teacher_profiles_verified_by_fkey; Type: FK CONSTRAINT; Schema: core; Owner: -
--

ALTER TABLE ONLY core.teacher_profiles
    ADD CONSTRAINT teacher_profiles_verified_by_fkey FOREIGN KEY (verified_by) REFERENCES auth.users(id);


--
-- TOC entry 5976 (class 2606 OID 33522)
-- Name: assignment_submissions assignment_submissions_assignment_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.assignment_submissions
    ADD CONSTRAINT assignment_submissions_assignment_id_fkey FOREIGN KEY (assignment_id) REFERENCES learn.assignments(id) ON DELETE CASCADE;


--
-- TOC entry 5977 (class 2606 OID 33532)
-- Name: assignment_submissions assignment_submissions_enrollment_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.assignment_submissions
    ADD CONSTRAINT assignment_submissions_enrollment_id_fkey FOREIGN KEY (enrollment_id) REFERENCES commerce.enrollments(id);


--
-- TOC entry 5978 (class 2606 OID 33537)
-- Name: assignment_submissions assignment_submissions_graded_by_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.assignment_submissions
    ADD CONSTRAINT assignment_submissions_graded_by_fkey FOREIGN KEY (graded_by) REFERENCES auth.users(id);


--
-- TOC entry 5979 (class 2606 OID 33527)
-- Name: assignment_submissions assignment_submissions_student_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.assignment_submissions
    ADD CONSTRAINT assignment_submissions_student_id_fkey FOREIGN KEY (student_id) REFERENCES auth.users(id);


--
-- TOC entry 5973 (class 2606 OID 33479)
-- Name: assignments assignments_course_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.assignments
    ADD CONSTRAINT assignments_course_id_fkey FOREIGN KEY (course_id) REFERENCES learn.courses(id) ON DELETE CASCADE;


--
-- TOC entry 5974 (class 2606 OID 33489)
-- Name: assignments assignments_section_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.assignments
    ADD CONSTRAINT assignments_section_id_fkey FOREIGN KEY (section_id) REFERENCES learn.sections(id);


--
-- TOC entry 5975 (class 2606 OID 33484)
-- Name: assignments assignments_teacher_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.assignments
    ADD CONSTRAINT assignments_teacher_id_fkey FOREIGN KEY (teacher_id) REFERENCES auth.users(id);


--
-- TOC entry 5980 (class 2606 OID 33567)
-- Name: certificates certificates_course_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.certificates
    ADD CONSTRAINT certificates_course_id_fkey FOREIGN KEY (course_id) REFERENCES learn.courses(id);


--
-- TOC entry 5981 (class 2606 OID 33572)
-- Name: certificates certificates_enrollment_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.certificates
    ADD CONSTRAINT certificates_enrollment_id_fkey FOREIGN KEY (enrollment_id) REFERENCES commerce.enrollments(id);


--
-- TOC entry 5982 (class 2606 OID 33562)
-- Name: certificates certificates_student_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.certificates
    ADD CONSTRAINT certificates_student_id_fkey FOREIGN KEY (student_id) REFERENCES auth.users(id);


--
-- TOC entry 5932 (class 2606 OID 32904)
-- Name: course_requirements course_requirements_course_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.course_requirements
    ADD CONSTRAINT course_requirements_course_id_fkey FOREIGN KEY (course_id) REFERENCES learn.courses(id) ON DELETE CASCADE;


--
-- TOC entry 5940 (class 2606 OID 33047)
-- Name: course_schedule course_schedule_course_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.course_schedule
    ADD CONSTRAINT course_schedule_course_id_fkey FOREIGN KEY (course_id) REFERENCES learn.courses(id) ON DELETE CASCADE;


--
-- TOC entry 5928 (class 2606 OID 32883)
-- Name: courses courses_format_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.courses
    ADD CONSTRAINT courses_format_id_fkey FOREIGN KEY (format_id) REFERENCES core.course_format(id);


--
-- TOC entry 5929 (class 2606 OID 32878)
-- Name: courses courses_language_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.courses
    ADD CONSTRAINT courses_language_id_fkey FOREIGN KEY (language_id) REFERENCES core.language_medium(id);


--
-- TOC entry 5930 (class 2606 OID 32873)
-- Name: courses courses_subject_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.courses
    ADD CONSTRAINT courses_subject_id_fkey FOREIGN KEY (subject_id) REFERENCES core.subjects(id);


--
-- TOC entry 5931 (class 2606 OID 32868)
-- Name: courses courses_teacher_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.courses
    ADD CONSTRAINT courses_teacher_id_fkey FOREIGN KEY (teacher_id) REFERENCES auth.users(id);


--
-- TOC entry 5966 (class 2606 OID 33400)
-- Name: lesson_progress lesson_progress_course_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.lesson_progress
    ADD CONSTRAINT lesson_progress_course_id_fkey FOREIGN KEY (course_id) REFERENCES learn.courses(id) ON DELETE CASCADE;


--
-- TOC entry 5967 (class 2606 OID 33405)
-- Name: lesson_progress lesson_progress_enrollment_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.lesson_progress
    ADD CONSTRAINT lesson_progress_enrollment_id_fkey FOREIGN KEY (enrollment_id) REFERENCES commerce.enrollments(id);


--
-- TOC entry 5968 (class 2606 OID 33395)
-- Name: lesson_progress lesson_progress_lesson_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.lesson_progress
    ADD CONSTRAINT lesson_progress_lesson_id_fkey FOREIGN KEY (lesson_id) REFERENCES learn.lessons(id) ON DELETE CASCADE;


--
-- TOC entry 5969 (class 2606 OID 33390)
-- Name: lesson_progress lesson_progress_student_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.lesson_progress
    ADD CONSTRAINT lesson_progress_student_id_fkey FOREIGN KEY (student_id) REFERENCES auth.users(id);


--
-- TOC entry 5934 (class 2606 OID 32964)
-- Name: lessons lessons_course_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.lessons
    ADD CONSTRAINT lessons_course_id_fkey FOREIGN KEY (course_id) REFERENCES learn.courses(id) ON DELETE CASCADE;


--
-- TOC entry 5935 (class 2606 OID 32969)
-- Name: lessons lessons_lesson_type_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.lessons
    ADD CONSTRAINT lessons_lesson_type_id_fkey FOREIGN KEY (lesson_type_id) REFERENCES core.lesson_type(id);


--
-- TOC entry 5936 (class 2606 OID 32959)
-- Name: lessons lessons_section_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.lessons
    ADD CONSTRAINT lessons_section_id_fkey FOREIGN KEY (section_id) REFERENCES learn.sections(id) ON DELETE CASCADE;


--
-- TOC entry 5941 (class 2606 OID 33078)
-- Name: live_sessions live_sessions_course_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.live_sessions
    ADD CONSTRAINT live_sessions_course_id_fkey FOREIGN KEY (course_id) REFERENCES learn.courses(id);


--
-- TOC entry 5942 (class 2606 OID 33083)
-- Name: live_sessions live_sessions_lesson_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.live_sessions
    ADD CONSTRAINT live_sessions_lesson_id_fkey FOREIGN KEY (lesson_id) REFERENCES learn.lessons(id);


--
-- TOC entry 5943 (class 2606 OID 33088)
-- Name: live_sessions live_sessions_teacher_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.live_sessions
    ADD CONSTRAINT live_sessions_teacher_id_fkey FOREIGN KEY (teacher_id) REFERENCES auth.users(id);


--
-- TOC entry 5970 (class 2606 OID 33441)
-- Name: quiz_attempts quiz_attempts_enrollment_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.quiz_attempts
    ADD CONSTRAINT quiz_attempts_enrollment_id_fkey FOREIGN KEY (enrollment_id) REFERENCES commerce.enrollments(id);


--
-- TOC entry 5971 (class 2606 OID 33436)
-- Name: quiz_attempts quiz_attempts_quiz_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.quiz_attempts
    ADD CONSTRAINT quiz_attempts_quiz_id_fkey FOREIGN KEY (quiz_id) REFERENCES learn.quizzes(id);


--
-- TOC entry 5972 (class 2606 OID 33431)
-- Name: quiz_attempts quiz_attempts_student_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.quiz_attempts
    ADD CONSTRAINT quiz_attempts_student_id_fkey FOREIGN KEY (student_id) REFERENCES auth.users(id);


--
-- TOC entry 5939 (class 2606 OID 33025)
-- Name: quiz_questions quiz_questions_quiz_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.quiz_questions
    ADD CONSTRAINT quiz_questions_quiz_id_fkey FOREIGN KEY (quiz_id) REFERENCES learn.quizzes(id) ON DELETE CASCADE;


--
-- TOC entry 5937 (class 2606 OID 33002)
-- Name: quizzes quizzes_course_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.quizzes
    ADD CONSTRAINT quizzes_course_id_fkey FOREIGN KEY (course_id) REFERENCES learn.courses(id) ON DELETE CASCADE;


--
-- TOC entry 5938 (class 2606 OID 32997)
-- Name: quizzes quizzes_lesson_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.quizzes
    ADD CONSTRAINT quizzes_lesson_id_fkey FOREIGN KEY (lesson_id) REFERENCES learn.lessons(id) ON DELETE CASCADE;


--
-- TOC entry 5933 (class 2606 OID 32928)
-- Name: sections sections_course_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.sections
    ADD CONSTRAINT sections_course_id_fkey FOREIGN KEY (course_id) REFERENCES learn.courses(id) ON DELETE CASCADE;


--
-- TOC entry 5944 (class 2606 OID 33108)
-- Name: session_attendance session_attendance_session_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.session_attendance
    ADD CONSTRAINT session_attendance_session_id_fkey FOREIGN KEY (session_id) REFERENCES learn.live_sessions(id) ON DELETE CASCADE;


--
-- TOC entry 5945 (class 2606 OID 33113)
-- Name: session_attendance session_attendance_student_id_fkey; Type: FK CONSTRAINT; Schema: learn; Owner: -
--

ALTER TABLE ONLY learn.session_attendance
    ADD CONSTRAINT session_attendance_student_id_fkey FOREIGN KEY (student_id) REFERENCES auth.users(id);


-- Completed on 2026-10-25 00:58:25

--
-- PostgreSQL database dump complete
--

\unrestrict dvPa9aXUYcpbHuvBACmKGfZVll11qYcGGfhgY8LRtnJgJhaMdKS3Oa4WhoIM1aK

