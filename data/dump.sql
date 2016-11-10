--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: tb_profile; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_profile (
    profile_id integer NOT NULL,
    profile_name character varying NOT NULL,
    profile_type character varying NOT NULL
);


ALTER TABLE public.tb_profile OWNER TO postgres;

--
-- Name: tb_profile_profile_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tb_profile_profile_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tb_profile_profile_id_seq OWNER TO postgres;

--
-- Name: tb_profile_profile_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE tb_profile_profile_id_seq OWNED BY tb_profile.profile_id;


--
-- Name: tb_user; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_user (
    user_id integer NOT NULL,
    user_fullname character varying NOT NULL,
    user_email character varying NOT NULL,
    user_birth_date date NOT NULL,
    user_profile integer NOT NULL,
    user_password character varying NOT NULL
);


ALTER TABLE public.tb_user OWNER TO postgres;

--
-- Name: tb_user_user_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tb_user_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tb_user_user_id_seq OWNER TO postgres;

--
-- Name: tb_user_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE tb_user_user_id_seq OWNED BY tb_user.user_id;


--
-- Name: profile_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tb_profile ALTER COLUMN profile_id SET DEFAULT nextval('tb_profile_profile_id_seq'::regclass);


--
-- Name: user_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tb_user ALTER COLUMN user_id SET DEFAULT nextval('tb_user_user_id_seq'::regclass);


--
-- Data for Name: tb_profile; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO tb_profile VALUES (1, 'Administrador', 'ADMIN');
INSERT INTO tb_profile VALUES (2, 'Gerente', 'MANAGER');
INSERT INTO tb_profile VALUES (3, 'Usuário', 'USER');


--
-- Name: tb_profile_profile_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tb_profile_profile_id_seq', 3, true);


--
-- Data for Name: tb_user; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO tb_user VALUES (1, 'Saulo de Siqueira', 'saulo@isolve.com.br', '1987-03-04', 1, 'pow3lk');


--
-- Name: tb_user_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tb_user_user_id_seq', 1, true);


--
-- Name: tb_profile_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_profile
    ADD CONSTRAINT tb_profile_pkey PRIMARY KEY (profile_id);


--
-- Name: tb_user_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_user
    ADD CONSTRAINT tb_user_pkey PRIMARY KEY (user_id);


--
-- Name: tb_user_user_profile_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tb_user
    ADD CONSTRAINT tb_user_user_profile_fkey FOREIGN KEY (user_profile) REFERENCES tb_profile(profile_id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

