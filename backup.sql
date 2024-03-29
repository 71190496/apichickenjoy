PGDMP                         {            chickenjoy-api    12.14    12.14 H    p           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                      false            q           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                      false            r           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                      false            s           1262    46973    chickenjoy-api    DATABASE     �   CREATE DATABASE "chickenjoy-api" WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'English_United States.1252' LC_CTYPE = 'English_United States.1252';
     DROP DATABASE "chickenjoy-api";
                postgres    false            �            1259    47126    DetailTransaksi    TABLE     �  CREATE TABLE public."DetailTransaksi" (
    id_transaksi bigint NOT NULL,
    id_user bigint NOT NULL,
    jumlah_pesanan integer DEFAULT 0,
    total_harga numeric(8,2) DEFAULT 0,
    metode_pembayaran character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    waktu_transaksi timestamp without time zone,
    nama_pelanggan character varying(255)
);
 %   DROP TABLE public."DetailTransaksi";
       public         heap    postgres    false            �            1259    47124     DetailTransaksi_id_transaksi_seq    SEQUENCE     �   CREATE SEQUENCE public."DetailTransaksi_id_transaksi_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 9   DROP SEQUENCE public."DetailTransaksi_id_transaksi_seq";
       public          postgres    false    216            t           0    0     DetailTransaksi_id_transaksi_seq    SEQUENCE OWNED BY     i   ALTER SEQUENCE public."DetailTransaksi_id_transaksi_seq" OWNED BY public."DetailTransaksi".id_transaksi;
          public          postgres    false    215            �            1259    47094    Menu    TABLE     S  CREATE TABLE public."Menu" (
    id_menu bigint NOT NULL,
    nama_menu character varying(255) NOT NULL,
    harga numeric(8,2) NOT NULL,
    kategori character varying(255) NOT NULL,
    image character varying(255),
    jumlah_stok integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public."Menu";
       public         heap    postgres    false            �            1259    47092    Menu_id_menu_seq    SEQUENCE     {   CREATE SEQUENCE public."Menu_id_menu_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public."Menu_id_menu_seq";
       public          postgres    false    214            u           0    0    Menu_id_menu_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE public."Menu_id_menu_seq" OWNED BY public."Menu".id_menu;
          public          postgres    false    213            �            1259    47201    Pesanan    TABLE     7  CREATE TABLE public."Pesanan" (
    id_pesanan bigint NOT NULL,
    id_transaksi bigint NOT NULL,
    id_menu bigint NOT NULL,
    jumlah_pesanan integer NOT NULL,
    total_harga numeric(8,2) NOT NULL,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone,
    catatan text
);
    DROP TABLE public."Pesanan";
       public         heap    postgres    false            �            1259    47199    Pesanan_id_pesanan_seq    SEQUENCE     �   CREATE SEQUENCE public."Pesanan_id_pesanan_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 /   DROP SEQUENCE public."Pesanan_id_pesanan_seq";
       public          postgres    false    219            v           0    0    Pesanan_id_pesanan_seq    SEQUENCE OWNED BY     U   ALTER SEQUENCE public."Pesanan_id_pesanan_seq" OWNED BY public."Pesanan".id_pesanan;
          public          postgres    false    218            �            1259    47033    User    TABLE     /  CREATE TABLE public."User" (
    id_user bigint NOT NULL,
    username character varying(255),
    password character varying(255),
    role character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    nama_karyawan character varying(255)
);
    DROP TABLE public."User";
       public         heap    postgres    false            �            1259    47031    User_id_user_seq    SEQUENCE     {   CREATE SEQUENCE public."User_id_user_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public."User_id_user_seq";
       public          postgres    false    212            w           0    0    User_id_user_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE public."User_id_user_seq" OWNED BY public."User".id_user;
          public          postgres    false    211            �            1259    47005    failed_jobs    TABLE     &  CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);
    DROP TABLE public.failed_jobs;
       public         heap    postgres    false            �            1259    47003    failed_jobs_id_seq    SEQUENCE     {   CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public.failed_jobs_id_seq;
       public          postgres    false    208            x           0    0    failed_jobs_id_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;
          public          postgres    false    207            �            1259    46976 
   migrations    TABLE     �   CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);
    DROP TABLE public.migrations;
       public         heap    postgres    false            �            1259    46974    migrations_id_seq    SEQUENCE     �   CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.migrations_id_seq;
       public          postgres    false    203            y           0    0    migrations_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;
          public          postgres    false    202            �            1259    46995    password_reset_tokens    TABLE     �   CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);
 )   DROP TABLE public.password_reset_tokens;
       public         heap    postgres    false            �            1259    47019    personal_access_tokens    TABLE     �  CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 *   DROP TABLE public.personal_access_tokens;
       public         heap    postgres    false            �            1259    47017    personal_access_tokens_id_seq    SEQUENCE     �   CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 4   DROP SEQUENCE public.personal_access_tokens_id_seq;
       public          postgres    false    210            z           0    0    personal_access_tokens_id_seq    SEQUENCE OWNED BY     _   ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;
          public          postgres    false    209            �            1259    47171     pesanan_id_pesanan_seq start 1    SEQUENCE     �   CREATE SEQUENCE public."pesanan_id_pesanan_seq start 1"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 9   DROP SEQUENCE public."pesanan_id_pesanan_seq start 1";
       public          postgres    false            �            1259    46984    users    TABLE     x  CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.users;
       public         heap    postgres    false            �            1259    46982    users_id_seq    SEQUENCE     u   CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.users_id_seq;
       public          postgres    false    205            {           0    0    users_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;
          public          postgres    false    204            �
           2604    47129    DetailTransaksi id_transaksi    DEFAULT     �   ALTER TABLE ONLY public."DetailTransaksi" ALTER COLUMN id_transaksi SET DEFAULT nextval('public."DetailTransaksi_id_transaksi_seq"'::regclass);
 M   ALTER TABLE public."DetailTransaksi" ALTER COLUMN id_transaksi DROP DEFAULT;
       public          postgres    false    215    216    216            �
           2604    47097    Menu id_menu    DEFAULT     p   ALTER TABLE ONLY public."Menu" ALTER COLUMN id_menu SET DEFAULT nextval('public."Menu_id_menu_seq"'::regclass);
 =   ALTER TABLE public."Menu" ALTER COLUMN id_menu DROP DEFAULT;
       public          postgres    false    213    214    214            �
           2604    47204    Pesanan id_pesanan    DEFAULT     |   ALTER TABLE ONLY public."Pesanan" ALTER COLUMN id_pesanan SET DEFAULT nextval('public."Pesanan_id_pesanan_seq"'::regclass);
 C   ALTER TABLE public."Pesanan" ALTER COLUMN id_pesanan DROP DEFAULT;
       public          postgres    false    219    218    219            �
           2604    47036    User id_user    DEFAULT     p   ALTER TABLE ONLY public."User" ALTER COLUMN id_user SET DEFAULT nextval('public."User_id_user_seq"'::regclass);
 =   ALTER TABLE public."User" ALTER COLUMN id_user DROP DEFAULT;
       public          postgres    false    212    211    212            �
           2604    47008    failed_jobs id    DEFAULT     p   ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);
 =   ALTER TABLE public.failed_jobs ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    208    207    208            �
           2604    46979    migrations id    DEFAULT     n   ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);
 <   ALTER TABLE public.migrations ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    202    203    203            �
           2604    47022    personal_access_tokens id    DEFAULT     �   ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);
 H   ALTER TABLE public.personal_access_tokens ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    210    209    210            �
           2604    46987    users id    DEFAULT     d   ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);
 7   ALTER TABLE public.users ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    205    204    205            j          0    47126    DetailTransaksi 
   TABLE DATA           �   COPY public."DetailTransaksi" (id_transaksi, id_user, jumlah_pesanan, total_harga, metode_pembayaran, created_at, updated_at, waktu_transaksi, nama_pelanggan) FROM stdin;
    public          postgres    false    216   SY       h          0    47094    Menu 
   TABLE DATA           q   COPY public."Menu" (id_menu, nama_menu, harga, kategori, image, jumlah_stok, created_at, updated_at) FROM stdin;
    public          postgres    false    214   CZ       m          0    47201    Pesanan 
   TABLE DATA           �   COPY public."Pesanan" (id_pesanan, id_transaksi, id_menu, jumlah_pesanan, total_harga, created_at, updated_at, catatan) FROM stdin;
    public          postgres    false    219   e]       f          0    47033    User 
   TABLE DATA           j   COPY public."User" (id_user, username, password, role, created_at, updated_at, nama_karyawan) FROM stdin;
    public          postgres    false    212   �^       b          0    47005    failed_jobs 
   TABLE DATA           a   COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
    public          postgres    false    208   �`       ]          0    46976 
   migrations 
   TABLE DATA           :   COPY public.migrations (id, migration, batch) FROM stdin;
    public          postgres    false    203   �`       `          0    46995    password_reset_tokens 
   TABLE DATA           I   COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
    public          postgres    false    206   �a       d          0    47019    personal_access_tokens 
   TABLE DATA           �   COPY public.personal_access_tokens (id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at) FROM stdin;
    public          postgres    false    210   �a       _          0    46984    users 
   TABLE DATA           u   COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) FROM stdin;
    public          postgres    false    205   �a       |           0    0     DetailTransaksi_id_transaksi_seq    SEQUENCE SET     Q   SELECT pg_catalog.setval('public."DetailTransaksi_id_transaksi_seq"', 24, true);
          public          postgres    false    215            }           0    0    Menu_id_menu_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public."Menu_id_menu_seq"', 70, true);
          public          postgres    false    213            ~           0    0    Pesanan_id_pesanan_seq    SEQUENCE SET     G   SELECT pg_catalog.setval('public."Pesanan_id_pesanan_seq"', 38, true);
          public          postgres    false    218                       0    0    User_id_user_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public."User_id_user_seq"', 38, true);
          public          postgres    false    211            �           0    0    failed_jobs_id_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);
          public          postgres    false    207            �           0    0    migrations_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.migrations_id_seq', 14, true);
          public          postgres    false    202            �           0    0    personal_access_tokens_id_seq    SEQUENCE SET     L   SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 12, true);
          public          postgres    false    209            �           0    0     pesanan_id_pesanan_seq start 1    SEQUENCE SET     Q   SELECT pg_catalog.setval('public."pesanan_id_pesanan_seq start 1"', 1, false);
          public          postgres    false    217            �           0    0    users_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.users_id_seq', 1, false);
          public          postgres    false    204            �
           2606    47134 $   DetailTransaksi DetailTransaksi_pkey 
   CONSTRAINT     p   ALTER TABLE ONLY public."DetailTransaksi"
    ADD CONSTRAINT "DetailTransaksi_pkey" PRIMARY KEY (id_transaksi);
 R   ALTER TABLE ONLY public."DetailTransaksi" DROP CONSTRAINT "DetailTransaksi_pkey";
       public            postgres    false    216            �
           2606    47102    Menu Menu_pkey 
   CONSTRAINT     U   ALTER TABLE ONLY public."Menu"
    ADD CONSTRAINT "Menu_pkey" PRIMARY KEY (id_menu);
 <   ALTER TABLE ONLY public."Menu" DROP CONSTRAINT "Menu_pkey";
       public            postgres    false    214            �
           2606    47206    Pesanan Pesanan_pkey 
   CONSTRAINT     ^   ALTER TABLE ONLY public."Pesanan"
    ADD CONSTRAINT "Pesanan_pkey" PRIMARY KEY (id_pesanan);
 B   ALTER TABLE ONLY public."Pesanan" DROP CONSTRAINT "Pesanan_pkey";
       public            postgres    false    219            �
           2606    47041    User User_pkey 
   CONSTRAINT     U   ALTER TABLE ONLY public."User"
    ADD CONSTRAINT "User_pkey" PRIMARY KEY (id_user);
 <   ALTER TABLE ONLY public."User" DROP CONSTRAINT "User_pkey";
       public            postgres    false    212            �
           2606    47014    failed_jobs failed_jobs_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);
 F   ALTER TABLE ONLY public.failed_jobs DROP CONSTRAINT failed_jobs_pkey;
       public            postgres    false    208            �
           2606    47016 #   failed_jobs failed_jobs_uuid_unique 
   CONSTRAINT     ^   ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);
 M   ALTER TABLE ONLY public.failed_jobs DROP CONSTRAINT failed_jobs_uuid_unique;
       public            postgres    false    208            �
           2606    46981    migrations migrations_pkey 
   CONSTRAINT     X   ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);
 D   ALTER TABLE ONLY public.migrations DROP CONSTRAINT migrations_pkey;
       public            postgres    false    203            �
           2606    47002 0   password_reset_tokens password_reset_tokens_pkey 
   CONSTRAINT     q   ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);
 Z   ALTER TABLE ONLY public.password_reset_tokens DROP CONSTRAINT password_reset_tokens_pkey;
       public            postgres    false    206            �
           2606    47027 2   personal_access_tokens personal_access_tokens_pkey 
   CONSTRAINT     p   ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);
 \   ALTER TABLE ONLY public.personal_access_tokens DROP CONSTRAINT personal_access_tokens_pkey;
       public            postgres    false    210            �
           2606    47030 :   personal_access_tokens personal_access_tokens_token_unique 
   CONSTRAINT     v   ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);
 d   ALTER TABLE ONLY public.personal_access_tokens DROP CONSTRAINT personal_access_tokens_token_unique;
       public            postgres    false    210            �
           2606    46994    users users_email_unique 
   CONSTRAINT     T   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);
 B   ALTER TABLE ONLY public.users DROP CONSTRAINT users_email_unique;
       public            postgres    false    205            �
           2606    46992    users users_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public            postgres    false    205            �
           1259    47028 8   personal_access_tokens_tokenable_type_tokenable_id_index    INDEX     �   CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);
 L   DROP INDEX public.personal_access_tokens_tokenable_type_tokenable_id_index;
       public            postgres    false    210    210            �
           2606    47135 ,   DetailTransaksi DetailTransaksi_id_user_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public."DetailTransaksi"
    ADD CONSTRAINT "DetailTransaksi_id_user_fkey" FOREIGN KEY (id_user) REFERENCES public."User"(id_user) NOT VALID;
 Z   ALTER TABLE ONLY public."DetailTransaksi" DROP CONSTRAINT "DetailTransaksi_id_user_fkey";
       public          postgres    false    216    212    2772            �
           2606    47212    Pesanan Pesanan_id_menu_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public."Pesanan"
    ADD CONSTRAINT "Pesanan_id_menu_fkey" FOREIGN KEY (id_menu) REFERENCES public."Menu"(id_menu) NOT VALID;
 J   ALTER TABLE ONLY public."Pesanan" DROP CONSTRAINT "Pesanan_id_menu_fkey";
       public          postgres    false    214    219    2774            �
           2606    47207 !   Pesanan Pesanan_id_transaksi_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public."Pesanan"
    ADD CONSTRAINT "Pesanan_id_transaksi_fkey" FOREIGN KEY (id_transaksi) REFERENCES public."DetailTransaksi"(id_transaksi) NOT VALID;
 O   ALTER TABLE ONLY public."Pesanan" DROP CONSTRAINT "Pesanan_id_transaksi_fkey";
       public          postgres    false    216    219    2776            j   �   x����J1����S�Z�#kn�'� ��D���ՃoobA�n �?�?f���і���B��;CE��K�I�c.ȡ��tŖXgF��%�ۯO�	�P�$�h}��i�;���}I�7k��-�_�^�:-�b�����m�y��'|��3����lw��'�o�����!�7m;;P��Oц�����S^P���Ń!�Z�n̧\���E�o��      h     x�ř�n1�מ��>����Җ&� e��I��e.�3���'�\#%��J� ��>��}�Pt�v�m��$� 9�_�RD]BȐt[H���(v��h���Y����%K��r��]SW�0!S/�_5�'ܒť��n���,�1<<��w���>Z¦ķ��3�=[A'[x�3���t}�;|����wD�O2l��u���ز�\��*K8C4ԍ�yV�jUA�9䀿��W�j}P5��]��az8�=z5�L:�y.ӌ�d[CѤդ ?D�U�N��i�|"�є՘C�br3���33J��g�b=���(x��g���/���s[l��N0�,��v��ys�����B���	�f�����%�	��籼�aW�/��V�OT2���Qc��]�Rs� �j�z�N��؆�����D�"��AI��um�軤�6-+*.X��G�>; ��_�t��<pUc�۱ϳpb��?��l���?���玄.Z�>��r!����m�p��P`�����cl��Y G�s�i7,T��T�a��S���.̼_�6����#}
z~g�t>���u��ȼe�ײ'.8��LDյSU}��oTu�?��q0�֝�6Ƥf���qRs�;�ZS�Y��&n�X��L��P_}i�\�_S�l�Y��
����8^V��֔��l��$j�l�	�꽲��wY���-�����k�M�Y��(ԍ
�uzZ�O�
�O�0(W���Uy����<��e����f���|?B���DY?�� �,      m   %  x���=k�0�Y��K��>,U{�N�(�ڦ����zCu�@66��^�����x���D��ey-���D�^y���3#�Nh$#m1v�}����q-�`}LÆ����Æ���!��`4���ʭ���#�E�������H�d�#S����3d^~��m݅#~�a_O����*Nk���g��rfN����r?�5lk�~y��>���w-���Y�i������Y˴;�i7�۽S��)Y�����w>%�r?r�3|������h�Л���M��      f   �  x�u�Ks�@�5��Y���A��y��T6Mh�V����If�ԌV��Y����W,y� ׃]�={�d�og�+k��)����8�/'Yضb�G��ӳq'sGyB�W��x��	���ֺ�f�!�����S�0y���la1b��T�b�^�2U�}N�taeOx�e�u��ߊWFh��WRS��-]��隱¥�\+^���7W
�~ �Az���#�_8v
υ�1��k�7F6m_L���NjP͞��6VaL$���zc8���ɋ�s��O ?�І�8���/reV�.��ڜ�q�k���ݙDW7Oj���Z��<;:����|�z4k��#�!T�d��?.&���w���nR�ṉi6�ȥr@��?�{�Z��ӈ�ȁ��t�S ��jw�O�G*�֘J7}*�]����jL*Aj��Nn=���a���OR4��Y{�h�P׆��ʌ�x��O�O      b      x������ � �      ]   �   x�e��n� @���i���dT�l�� ���}ǚ��� ��4΢g������1G���%��U�%�_ē�-�<�<q�%V���H[`�6�v�?�ĉ?�����oE���_�gM���c,�Z��HG{{��'M��`�g�Xjx����$��;}�b��pA�9�b�ƹfIE�r3��,!�@{a��Z@<26.�/�;@w�H?���tx�W-�D��"I����[�=�u<����� ?$A�5      `      x������ � �      d      x������ � �      _      x������ � �     