CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pgcrypto";
CREATE EXTENSION IF NOT EXISTS "pg_trgm";
CREATE EXTENSION IF NOT EXISTS "btree_gin";


CREATE TYPE user_role           AS ENUM ('student', 'teacher','parent', 'admin', 'super_admin');
CREATE TYPE user_status         AS ENUM ('active', 'inactive', 'suspended', 'pending_verification');
CREATE TYPE course_status       AS ENUM ('draft', 'pending_review', 'live', 'archived', 'suspended');
CREATE TYPE pricing_model       AS ENUM ('monthly_subscription', 'one_time', 'free');
CREATE TYPE enrollment_status   AS ENUM ('active', 'paused', 'cancelled', 'expired', 'pending');
CREATE TYPE payment_status      AS ENUM ('pending', 'completed', 'failed', 'refunded', 'disputed');
CREATE TYPE assignment_status   AS ENUM ('draft', 'active', 'closed', 'archived');
CREATE TYPE submission_status   AS ENUM ('not_submitted', 'submitted', 'late', 'graded', 'returned');
CREATE TYPE session_status      AS ENUM ('scheduled', 'live', 'completed', 'cancelled', 'rescheduled');
CREATE TYPE message_status      AS ENUM ('sent', 'delivered', 'read');
CREATE TYPE day_of_week         AS ENUM ('saturday','sunday','monday','tuesday','wednesday','thursday','friday');
CREATE TYPE media_type          AS ENUM ('image', 'video', 'document', 'audio', 'archive');
CREATE TYPE certificate_status  AS ENUM ('not_earned', 'earned', 'issued', 'revoked');
CREATE TYPE board_type          AS ENUM ('nctb_national', 'dhaka', 'rajshahi', 'chittagong','sylhet', 'barisal', 'comilla', 'jessore', 'dinajpur','mymensingh', 'madrasah', 'technical', 'general');


CREATE SCHEMA IF NOT EXISTS auth;
CREATE SCHEMA IF NOT EXISTS core;
CREATE SCHEMA IF NOT EXISTS learn;
CREATE SCHEMA IF NOT EXISTS commerce;
CREATE SCHEMA IF NOT EXISTS comms;
CREATE SCHEMA IF NOT EXISTS analytics;
CREATE SCHEMA IF NOT EXISTS audit;



CREATE TABLE core.teacher_type (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
); -- ('teacher', 'university_professor', 'professor', 'expert')

CREATE TABLE core.course_format (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
); -- ('live_recorded', 'self_paced', 'one_to_one', 'batch')

CREATE TABLE core.lesson_type (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
); -- ('video', 'text', 'interactive', 'quiz', 'assignment')

CREATE TABLE core.payment_method (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
); -- ('credit_card', 'paypal', 'bank_transfer', 'crypto')

CREATE TABLE core.notification_type (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
); -- ('enrollment', 'payment', 'assignment_due', 'assignment_graded', 'session_reminder', 'session_started', 'message', 'review','course_update', 'achievement', 'system', 'at_risk_alert')

CREATE TABLE core.grade_level (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
); -- ('kindergarten', '1st_grade', '2nd_grade', '3rd_grade', '4th_grade', '5th_grade', '6th_grade', '7th_grade', '8th_grade', '9th_grade', '10th_grade', '11th_grade', '12th_grade')

CREATE TABLE core.language_medium (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
); -- ('bangla', 'english', 'mixed')


--.................................................. AUTH .............................................

CREATE TABLE auth.users (
  id                    UUID            PRIMARY KEY DEFAULT uuid_generate_v4(),
  email                 VARCHAR(320)    NOT NULL UNIQUE,
  phone                 VARCHAR(20)     UNIQUE,
  password_hash         TEXT            NOT NULL,
  role                  user_role       NOT NULL DEFAULT 'student',
  status                user_status     NOT NULL DEFAULT 'pending_verification',
  email_verified        BOOLEAN         NOT NULL DEFAULT FALSE,
  phone_verified        BOOLEAN         NOT NULL DEFAULT FALSE,
  last_login_at         TIMESTAMPTZ,
  last_login_ip         INET,
  login_attempts        SMALLINT        NOT NULL DEFAULT 0,
  locked_until          TIMESTAMPTZ,
  created_at            TIMESTAMPTZ     NOT NULL DEFAULT NOW(),
  updated_at            TIMESTAMPTZ     NOT NULL DEFAULT NOW()  
);

CREATE TABLE auth.sessions (
  id                UUID            PRIMARY KEY DEFAULT uuid_generate_v4(), 
  user_id           UUID            NOT NULL REFERENCES auth.users(id) ON DELETE CASCADE,
  refresh_token     TEXT            NOT NULL UNIQUE,
  device_info       JSONB,                                            -- {ua, browser, os, device_type}
  ip_address        INET,
  is_revoked        BOOLEAN         NOT NULL DEFAULT FALSE,
  expires_at        TIMESTAMPTZ     NOT NULL,
  created_at        TIMESTAMPTZ     NOT NULL DEFAULT NOW()
);

CREATE TABLE auth.password_reset_tokens (
  id          UUID        PRIMARY KEY DEFAULT uuid_generate_v4(),
  user_id     UUID        NOT NULL REFERENCES auth.users(id) ON DELETE CASCADE,
  token_hash  TEXT        NOT NULL UNIQUE,
  used        BOOLEAN     NOT NULL DEFAULT FALSE,
  expires_at  TIMESTAMPTZ NOT NULL,
  created_at  TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE auth.email_verification_tokens (
  id          UUID        PRIMARY KEY DEFAULT uuid_generate_v4(),
  user_id     UUID        NOT NULL REFERENCES auth.users(id) ON DELETE CASCADE,
  token_hash  TEXT        NOT NULL UNIQUE,
  used        BOOLEAN     NOT NULL DEFAULT FALSE,
  expires_at  TIMESTAMPTZ NOT NULL,
  created_at  TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE auth.oauth_accounts (
  id                UUID        PRIMARY KEY DEFAULT uuid_generate_v4(),
  user_id           UUID        NOT NULL REFERENCES auth.users(id) ON DELETE CASCADE,
  provider          VARCHAR(32) NOT NULL,
  provider_user_id  TEXT        NOT NULL,
  access_token      TEXT,
  refresh_token     TEXT,
  expires_at        TIMESTAMPTZ,
  created_at        TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  UNIQUE (provider, provider_user_id)
);

--.......................................... CORE .....................................................

CREATE TABLE core.profiles (
  id                UUID          PRIMARY KEY REFERENCES auth.users(id) ON DELETE CASCADE,
  first_name        VARCHAR(80)   NOT NULL,
  last_name         VARCHAR(80)   NOT NULL,
  display_name      VARCHAR(120),
  avatar_url        TEXT,
  cover_url         TEXT,
  bio               TEXT,
  date_of_birth     DATE,
  gender            VARCHAR(16),
  country           VARCHAR(2)    NOT NULL DEFAULT 'BD',
  city              VARCHAR(80),
  timezone          VARCHAR(64)   NOT NULL DEFAULT 'Asia/Dhaka',
  language          VARCHAR(8)    NOT NULL DEFAULT 'bn', 
  notifications_prefs JSONB       NOT NULL DEFAULT '{}'::JSONB,
  created_at        TIMESTAMPTZ   NOT NULL DEFAULT NOW(),
  updated_at        TIMESTAMPTZ   NOT NULL DEFAULT NOW()
);


 CREATE TABLE core.student_profiles (
  user_id             UUID          PRIMARY KEY REFERENCES auth.users(id) ON DELETE CASCADE,
  grade_level_id      INTEGER       REFERENCES core.grade_level (id),
  school_name         VARCHAR(200),
  board               board_type,
  roll_number         VARCHAR(30),
  registration_number VARCHAR(30),
  parent_name         VARCHAR(160),
  parent_phone        VARCHAR(20),
  parent_email        VARCHAR(320),
  interested_subjects TEXT[],
  points              INTEGER       NOT NULL DEFAULT 0,
  last_activity_at    TIMESTAMPTZ,
  updated_at          TIMESTAMPTZ   NOT NULL DEFAULT NOW()
);

CREATE TABLE core.teacher_profiles (
  user_id               UUID          PRIMARY KEY REFERENCES auth.users(id) ON DELETE CASCADE,
  teacher_type_id       INTEGER       REFERENCES core.teacher_type (id) NOT NULL DEFAULT 1,
  headline              VARCHAR(200),
  specializations       TEXT[],                                       -- array of subjects
  qualifications        JSONB,                                        -- [{degree, institution, year, verified}]
  experience_years      SMALLINT,
  institution           VARCHAR(200),
  department            VARCHAR(200),
  verified              BOOLEAN       NOT NULL DEFAULT FALSE,
  verified_at           TIMESTAMPTZ,
  verified_by           UUID          REFERENCES auth.users(id),
  id_document_url       TEXT,
  id_verified           BOOLEAN       NOT NULL DEFAULT FALSE,
  rating_avg            NUMERIC(3,2)  NOT NULL DEFAULT 0.00,
  rating_count          INTEGER       NOT NULL DEFAULT 0,
  total_students        INTEGER       NOT NULL DEFAULT 0,
  total_courses         SMALLINT      NOT NULL DEFAULT 0,
  total_revenue         NUMERIC(14,2) NOT NULL DEFAULT 0.00,
  payout_account        JSONB,                                        -- {method, account_number, name, verified}
  payout_threshold      NUMERIC(10,2) NOT NULL DEFAULT 500.00,
  commission_rate       NUMERIC(5,4)  NOT NULL DEFAULT 0.1500,        -- 15%
  is_featured           BOOLEAN       NOT NULL DEFAULT FALSE,
  social_links          JSONB         NOT NULL DEFAULT '{}'::JSONB,   -- {youtube, linkedin, website, u2026}
  languages_taught      INTEGER[]     NOT NULL DEFAULT '{1}',         -- references language_medium(id)
  updated_at            TIMESTAMPTZ   NOT NULL DEFAULT NOW()
);

CREATE TABLE core.subjects (
  id            SMALLSERIAL   PRIMARY KEY,
  name          VARCHAR(120)  NOT NULL UNIQUE,
  slug          VARCHAR(120)  NOT NULL UNIQUE,
  name_bn       VARCHAR(120),
  icon          VARCHAR(64),
  color         VARCHAR(7),
  parent_id     SMALLINT      REFERENCES core.subjects(id),
  sort_order    SMALLINT      NOT NULL DEFAULT 0,
  is_active     BOOLEAN       NOT NULL DEFAULT TRUE,
  created_at    TIMESTAMPTZ   NOT NULL DEFAULT NOW()
);


 CREATE TABLE core.media (
  id              UUID          PRIMARY KEY DEFAULT uuid_generate_v4(),
  uploader_id     UUID          NOT NULL REFERENCES auth.users(id),
  media_type      media_type    NOT NULL,
  original_name   VARCHAR(255)  NOT NULL,
  storage_key     TEXT          NOT NULL UNIQUE,
  public_url      TEXT          NOT NULL,
  cdn_url         TEXT,
  mime_type       VARCHAR(127)  NOT NULL,
  size_bytes      BIGINT        NOT NULL,
  width_px        INTEGER,
  height_px       INTEGER,
  duration_sec    INTEGER, 
  metadata        JSONB         NOT NULL DEFAULT '{}'::JSONB,
  is_public       BOOLEAN       NOT NULL DEFAULT FALSE,
  created_at      TIMESTAMPTZ   NOT NULL DEFAULT NOW()
);

CREATE TABLE learn.courses (
  id                  UUID            PRIMARY KEY DEFAULT uuid_generate_v4(),
  teacher_id          UUID            NOT NULL REFERENCES auth.users(id),
  subject_id          SMALLINT        NOT NULL REFERENCES core.subjects(id),
  title               VARCHAR(120)    NOT NULL,
  slug                VARCHAR(140)    NOT NULL UNIQUE,
  short_description   VARCHAR(300)    NOT NULL,
  full_description    TEXT,
  thumbnail_url       TEXT,
  trailer_url         TEXT,
  grade_level_ids     INTEGER[]       NOT NULL DEFAULT '{}',          -- references grade_level(id)
  board               board_type,
  language_id         INTEGER         NOT NULL DEFAULT 1 REFERENCES core.language_medium (id),
  format_id           INTEGER         NOT NULL DEFAULT 1 REFERENCES core.course_format (id),
  status              course_status   NOT NULL DEFAULT 'draft',
  tags                TEXT[]          NOT NULL DEFAULT '{}',
  -- pricing
  pricing_model       pricing_model   NOT NULL DEFAULT 'monthly_subscription',
  price               NUMERIC(10,2)   NOT NULL DEFAULT 0.00,
  original_price      NUMERIC(10,2),
  currency            CHAR(3)         NOT NULL DEFAULT 'BDT',
  -- limits
  max_students        INTEGER,                                        -- NULL = unlimited
  -- schedule metadata
  start_date          DATE,
  end_date            DATE,
  session_duration_min SMALLINT       NOT NULL DEFAULT 60,
  -- stats (denormalized for read performance)
  enrolled_count      INTEGER         NOT NULL DEFAULT 0,
  completed_count     INTEGER         NOT NULL DEFAULT 0,
  rating_avg          NUMERIC(3,2)    NOT NULL DEFAULT 0.00,
  rating_count        INTEGER         NOT NULL DEFAULT 0,
  total_lessons       SMALLINT        NOT NULL DEFAULT 0,
  total_sections      SMALLINT        NOT NULL DEFAULT 0,
  total_duration_min  INTEGER         NOT NULL DEFAULT 0,
  -- timestamps
  published_at        TIMESTAMPTZ,
  created_at          TIMESTAMPTZ     NOT NULL DEFAULT NOW(),
  updated_at          TIMESTAMPTZ     NOT NULL DEFAULT NOW(),
  deleted_at          TIMESTAMPTZ
);

CREATE TABLE learn.course_requirements (
  id          SERIAL        PRIMARY KEY,
  course_id   UUID          NOT NULL REFERENCES learn.courses(id) ON DELETE CASCADE,
  type        VARCHAR(16)   NOT NULL CHECK (type IN ('prerequisite','outcome','target_student')),
  content     TEXT          NOT NULL,
  sort_order  SMALLINT      NOT NULL DEFAULT 0
);

CREATE TABLE learn.sections (
  id          UUID          PRIMARY KEY DEFAULT uuid_generate_v4(),
  course_id   UUID          NOT NULL REFERENCES learn.courses(id) ON DELETE CASCADE,
  title       VARCHAR(200)  NOT NULL,
  description TEXT,
  sort_order  SMALLINT      NOT NULL DEFAULT 0,
  is_free     BOOLEAN       NOT NULL DEFAULT FALSE,
  created_at  TIMESTAMPTZ   NOT NULL DEFAULT NOW(),
  updated_at  TIMESTAMPTZ   NOT NULL DEFAULT NOW()
); -- Chapter

CREATE TABLE learn.lessons (
  id                UUID          PRIMARY KEY DEFAULT uuid_generate_v4(),
  section_id        UUID          NOT NULL REFERENCES learn.sections(id) ON DELETE CASCADE,
  course_id         UUID          NOT NULL REFERENCES learn.courses(id) ON DELETE CASCADE,
  title             VARCHAR(200)  NOT NULL,
  description       TEXT,
  lesson_type_id    INTEGER       NOT NULL DEFAULT 1 REFERENCES core.lesson_type (id),
  sort_order        SMALLINT      NOT NULL DEFAULT 0,
  is_free_preview   BOOLEAN       NOT NULL DEFAULT FALSE,
  is_published      BOOLEAN       NOT NULL DEFAULT FALSE,
  -- content refs
  video_url         TEXT,
  video_duration_sec INTEGER,
  document_url      TEXT,
  document_pages    SMALLINT,
  external_url      TEXT,
  content_json      JSONB,                                            -- for quiz/rich content
  -- live session fields
  scheduled_at      TIMESTAMPTZ,
  live_platform     VARCHAR(32),                                      -- 'learnforge','zoom','meet'
  live_meeting_url  TEXT,
  live_meeting_id   VARCHAR(128),
  live_password     VARCHAR(64),
  -- metadata
  thumbnail_url     TEXT,
  attachments       JSONB         NOT NULL DEFAULT '[]'::JSONB,       -- [{name,url,size}]
  created_at        TIMESTAMPTZ   NOT NULL DEFAULT NOW(),
  updated_at        TIMESTAMPTZ   NOT NULL DEFAULT NOW()
);

CREATE TABLE learn.quizzes (
  id              UUID        PRIMARY KEY DEFAULT uuid_generate_v4(),
  lesson_id       UUID        NOT NULL REFERENCES learn.lessons(id) ON DELETE CASCADE,
  course_id       UUID        NOT NULL REFERENCES learn.courses(id) ON DELETE CASCADE,
  title           VARCHAR(200),
  instructions    TEXT,
  time_limit_min  SMALLINT,
  pass_percentage SMALLINT    NOT NULL DEFAULT 60,
  attempts_allowed SMALLINT   NOT NULL DEFAULT 3,                     -- -1 = unlimited
  randomize_questions BOOLEAN NOT NULL DEFAULT FALSE,
  show_answers_after  BOOLEAN NOT NULL DEFAULT TRUE,
  created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  updated_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE learn.quiz_questions (
  id              UUID          PRIMARY KEY DEFAULT uuid_generate_v4(),
  quiz_id         UUID          NOT NULL REFERENCES learn.quizzes(id) ON DELETE CASCADE,
  question_text   TEXT          NOT NULL,
  question_image  TEXT,
  question_type   VARCHAR(20)   NOT NULL DEFAULT 'mcq'
                    CHECK (question_type IN ('mcq','multi_select','true_false','short_answer','fill_blank')),
  options         JSONB,                                              -- [{id, text, image, is_correct}]
  correct_answer  TEXT,
  explanation     TEXT,
  points          SMALLINT      NOT NULL DEFAULT 1,
  sort_order      SMALLINT      NOT NULL DEFAULT 0
);

CREATE TABLE learn.course_schedule (
  id                UUID          PRIMARY KEY DEFAULT uuid_generate_v4(),
  course_id         UUID          NOT NULL REFERENCES learn.courses(id) ON DELETE CASCADE,
  day_of_week       day_of_week   NOT NULL,
  start_time        TIME          NOT NULL,
  end_time          TIME          NOT NULL,
  timezone          VARCHAR(64)   NOT NULL DEFAULT 'Asia/Dhaka',
  is_active         BOOLEAN       NOT NULL DEFAULT TRUE,
  effective_from    DATE,
  effective_until   DATE,
  created_at        TIMESTAMPTZ   NOT NULL DEFAULT NOW()
);


 CREATE TABLE learn.live_sessions (
  id                  UUID          PRIMARY KEY DEFAULT uuid_generate_v4(),
  course_id           UUID          NOT NULL REFERENCES learn.courses(id),
  lesson_id           UUID          REFERENCES learn.lessons(id),
  teacher_id          UUID          NOT NULL REFERENCES auth.users(id),
  title               VARCHAR(200)  NOT NULL,
  description         TEXT,
  status              session_status NOT NULL DEFAULT 'scheduled',
  scheduled_start_at  TIMESTAMPTZ   NOT NULL,
  scheduled_end_at    TIMESTAMPTZ   NOT NULL,
  actual_start_at     TIMESTAMPTZ,
  actual_end_at       TIMESTAMPTZ,
  platform            VARCHAR(32)   NOT NULL DEFAULT 'learnforge',
  meeting_url         TEXT,
  meeting_id          VARCHAR(128),
  recording_url       TEXT,
  recording_available BOOLEAN       NOT NULL DEFAULT FALSE,
  attendee_count      INTEGER       NOT NULL DEFAULT 0,
  notes               TEXT,
  created_at          TIMESTAMPTZ   NOT NULL DEFAULT NOW(),
  updated_at          TIMESTAMPTZ   NOT NULL DEFAULT NOW()
);

CREATE TABLE learn.session_attendance (
  id              UUID          PRIMARY KEY DEFAULT uuid_generate_v4(),
  session_id      UUID          NOT NULL REFERENCES learn.live_sessions(id) ON DELETE CASCADE,
  student_id      UUID          NOT NULL REFERENCES auth.users(id),
  joined_at       TIMESTAMPTZ,
  left_at         TIMESTAMPTZ,
  duration_min    SMALLINT,
  attended        BOOLEAN       NOT NULL DEFAULT FALSE,
  created_at      TIMESTAMPTZ   NOT NULL DEFAULT NOW(),
  UNIQUE (session_id, student_id)
);

--................................................Commorce....................................................

CREATE TABLE commerce.enrollments (
  id                  UUID            PRIMARY KEY DEFAULT uuid_generate_v4(),
  student_id          UUID            NOT NULL REFERENCES auth.users(id),
  course_id           UUID            NOT NULL REFERENCES learn.courses(id),
  status              enrollment_status NOT NULL DEFAULT 'active',
  enrolled_at         TIMESTAMPTZ     NOT NULL DEFAULT NOW(),
  expires_at          TIMESTAMPTZ,                                    -- NULL = rolling
  cancelled_at        TIMESTAMPTZ,
  cancellation_reason TEXT,
  -- progress snapshot (denormalized)
  progress_pct        SMALLINT        NOT NULL DEFAULT 0,
  last_lesson_id      UUID            REFERENCES learn.lessons(id),
  last_accessed_at    TIMESTAMPTZ,
  completed_at        TIMESTAMPTZ,
  certificate_id      UUID,                                           -- FK added later
  -- billing
  current_plan_price  NUMERIC(10,2)   NOT NULL DEFAULT 0.00,
  created_at          TIMESTAMPTZ     NOT NULL DEFAULT NOW(),
  updated_at          TIMESTAMPTZ     NOT NULL DEFAULT NOW(),
  UNIQUE (student_id, course_id)
);

CREATE TABLE commerce.subscriptions (
  id                  UUID            PRIMARY KEY DEFAULT uuid_generate_v4(),
  enrollment_id       UUID            NOT NULL REFERENCES commerce.enrollments(id),
  student_id          UUID            NOT NULL REFERENCES auth.users(id),
  course_id           UUID            NOT NULL REFERENCES learn.courses(id),
  status              enrollment_status NOT NULL DEFAULT 'active',
  billing_cycle       VARCHAR(16)     NOT NULL DEFAULT 'monthly',
  price_per_cycle     NUMERIC(10,2)   NOT NULL,
  currency            CHAR(3)         NOT NULL DEFAULT 'BDT',
  current_period_start TIMESTAMPTZ    NOT NULL,
  current_period_end   TIMESTAMPTZ    NOT NULL,
  next_billing_date   TIMESTAMPTZ,
  trial_end_at        TIMESTAMPTZ,
  cancelled_at        TIMESTAMPTZ,
  pause_until         TIMESTAMPTZ,
  auto_renew          BOOLEAN         NOT NULL DEFAULT TRUE,
  created_at          TIMESTAMPTZ     NOT NULL DEFAULT NOW(),
  updated_at          TIMESTAMPTZ     NOT NULL DEFAULT NOW()
);


 CREATE TABLE commerce.coupons (
  id                  SERIAL          PRIMARY KEY,
  teacher_id          UUID            REFERENCES auth.users(id),  
  course_id           UUID            REFERENCES learn.courses(id),
  code                VARCHAR(32)     NOT NULL UNIQUE,
  description         TEXT,
  discount_type       VARCHAR(10)     NOT NULL CHECK (discount_type IN ('percentage','fixed')),
  discount_value      NUMERIC(10,2)   NOT NULL,
  max_uses            INTEGER,
  used_count          INTEGER         NOT NULL DEFAULT 0,
  min_order_amount    NUMERIC(10,2),
  valid_from          TIMESTAMPTZ     NOT NULL DEFAULT NOW(),
  valid_until         TIMESTAMPTZ,
  is_active           BOOLEAN         NOT NULL DEFAULT TRUE,
  created_at          TIMESTAMPTZ     NOT NULL DEFAULT NOW()
);


 CREATE TABLE commerce.payments (
  id                  UUID            PRIMARY KEY DEFAULT uuid_generate_v4(),
  student_id          UUID            NOT NULL REFERENCES auth.users(id),
  course_id           UUID            NOT NULL REFERENCES learn.courses(id),
  enrollment_id       UUID            REFERENCES commerce.enrollments(id),
  subscription_id     UUID            REFERENCES commerce.subscriptions(id),
  coupon_id           INTEGER         REFERENCES commerce.coupons(id),
  -- amounts
  gross_amount        NUMERIC(10,2)   NOT NULL,
  discount_amount     NUMERIC(10,2)   NOT NULL DEFAULT 0.00,
  net_amount          NUMERIC(10,2)   NOT NULL,
  platform_fee        NUMERIC(10,2)   NOT NULL,
  teacher_amount      NUMERIC(10,2)   NOT NULL,
  currency            CHAR(3)         NOT NULL DEFAULT 'BDT',
  -- payment gateway
  status              payment_status  NOT NULL DEFAULT 'pending',
  method_id           INTEGER         REFERENCES core.payment_method (id),
  gateway             VARCHAR(32),                                    -- 'sslcommerz','stripe','bkash'
  gateway_txn_id      VARCHAR(128)    UNIQUE,
  gateway_response    JSONB,
  gateway_fee         NUMERIC(10,2)   NOT NULL DEFAULT 0.00,
  -- invoice
  invoice_number      VARCHAR(32)     UNIQUE,
  invoice_url         TEXT,
  -- refund
  refunded_at         TIMESTAMPTZ,
  refund_amount       NUMERIC(10,2),
  refund_reason       TEXT,
  refund_txn_id       VARCHAR(128),
  -- timestamps
  paid_at             TIMESTAMPTZ,
  created_at          TIMESTAMPTZ     NOT NULL DEFAULT NOW(),
  updated_at          TIMESTAMPTZ     NOT NULL DEFAULT NOW()
);

 CREATE TABLE commerce.teacher_wallet (
  teacher_id      UUID            PRIMARY KEY REFERENCES auth.users(id),
  available       NUMERIC(12,2)   NOT NULL DEFAULT 0.00,
  pending         NUMERIC(12,2)   NOT NULL DEFAULT 0.00,
  lifetime_earned NUMERIC(14,2)   NOT NULL DEFAULT 0.00,
  updated_at      TIMESTAMPTZ     NOT NULL DEFAULT NOW()
);

-- Create payouts table (referenced by wallet_transactions)
CREATE TABLE commerce.payouts (
  id              UUID            PRIMARY KEY DEFAULT uuid_generate_v4(),
  teacher_id      UUID            NOT NULL REFERENCES auth.users(id),
  amount          NUMERIC(12,2)   NOT NULL,
  status          VARCHAR(20)     NOT NULL DEFAULT 'pending' CHECK (status IN ('pending','processing','completed','failed')),
  method          VARCHAR(32)     NOT NULL,
  account_info    JSONB,
  processed_at    TIMESTAMPTZ,
  created_at      TIMESTAMPTZ     NOT NULL DEFAULT NOW()
);

CREATE TABLE commerce.wallet_transactions (
  id              UUID            PRIMARY KEY DEFAULT uuid_generate_v4(),
  teacher_id      UUID            NOT NULL REFERENCES auth.users(id),
  payment_id      UUID            REFERENCES commerce.payments(id),
  payout_id       UUID            REFERENCES commerce.payouts(id),
  type            VARCHAR(16)     NOT NULL CHECK (type IN ('credit','debit','reversal')),
  amount          NUMERIC(12,2)   NOT NULL,
  balance_after   NUMERIC(12,2)   NOT NULL,
  description     TEXT,
  created_at      TIMESTAMPTZ     NOT NULL DEFAULT NOW()
);

 --....................................................Learn................................................

 CREATE TABLE learn.lesson_progress (
  id                  UUID          PRIMARY KEY DEFAULT uuid_generate_v4(),
  student_id          UUID          NOT NULL REFERENCES auth.users(id),
  lesson_id           UUID          NOT NULL REFERENCES learn.lessons(id) ON DELETE CASCADE,
  course_id           UUID          NOT NULL REFERENCES learn.courses(id) ON DELETE CASCADE,
  enrollment_id       UUID          NOT NULL REFERENCES commerce.enrollments(id),
  is_completed        BOOLEAN       NOT NULL DEFAULT FALSE,
  watch_seconds       INTEGER       NOT NULL DEFAULT 0,               -- video watch time
  last_position_sec   INTEGER       NOT NULL DEFAULT 0,               -- resume position
  completed_at        TIMESTAMPTZ,
  first_opened_at     TIMESTAMPTZ,
  last_opened_at      TIMESTAMPTZ,
  notes               TEXT,                                           -- student's private notes
  created_at          TIMESTAMPTZ   NOT NULL DEFAULT NOW(),
  updated_at          TIMESTAMPTZ   NOT NULL DEFAULT NOW(),
  UNIQUE (student_id, lesson_id)
);


 CREATE TABLE learn.quiz_attempts (
  id                  UUID          PRIMARY KEY DEFAULT uuid_generate_v4(),
  student_id          UUID          NOT NULL REFERENCES auth.users(id),
  quiz_id             UUID          NOT NULL REFERENCES learn.quizzes(id),
  enrollment_id       UUID          NOT NULL REFERENCES commerce.enrollments(id),
  attempt_number      SMALLINT      NOT NULL DEFAULT 1,
  started_at          TIMESTAMPTZ   NOT NULL DEFAULT NOW(),
  submitted_at        TIMESTAMPTZ,
  time_taken_sec      INTEGER,
  score               SMALLINT,
  max_score           SMALLINT      NOT NULL,
  percentage          NUMERIC(5,2),
  passed              BOOLEAN,
  answers             JSONB         NOT NULL DEFAULT '[]'::JSONB,     -- [{question_id, answer, is_correct, points}]
  created_at          TIMESTAMPTZ   NOT NULL DEFAULT NOW()
);



CREATE TABLE learn.assignments (
  id                  UUID          PRIMARY KEY DEFAULT uuid_generate_v4(),
  course_id           UUID          NOT NULL REFERENCES learn.courses(id) ON DELETE CASCADE,
  teacher_id          UUID          NOT NULL REFERENCES auth.users(id),
  section_id          UUID          REFERENCES learn.sections(id),
  title               VARCHAR(200)  NOT NULL,
  description         TEXT          NOT NULL,
  instructions        TEXT,
  attachments         JSONB         NOT NULL DEFAULT '[]'::JSONB,     -- [{name,url,size}]
  status              assignment_status NOT NULL DEFAULT 'draft',
  due_date            TIMESTAMPTZ,
  total_marks         SMALLINT      NOT NULL DEFAULT 100,
  pass_marks          SMALLINT      NOT NULL DEFAULT 40,
  allow_late_submit   BOOLEAN       NOT NULL DEFAULT FALSE,
  late_penalty_pct    SMALLINT      NOT NULL DEFAULT 0,
  max_file_size_mb    SMALLINT      NOT NULL DEFAULT 10,
  allowed_file_types  TEXT[]        NOT NULL DEFAULT '{}',
  created_at          TIMESTAMPTZ   NOT NULL DEFAULT NOW(),
  updated_at          TIMESTAMPTZ   NOT NULL DEFAULT NOW()
);

CREATE TABLE learn.assignment_submissions (
  id                  UUID          PRIMARY KEY DEFAULT uuid_generate_v4(),
  assignment_id       UUID          NOT NULL REFERENCES learn.assignments(id) ON DELETE CASCADE,
  student_id          UUID          NOT NULL REFERENCES auth.users(id),
  enrollment_id       UUID          NOT NULL REFERENCES commerce.enrollments(id),
  status              submission_status NOT NULL DEFAULT 'not_submitted',
  submitted_at        TIMESTAMPTZ,
  is_late             BOOLEAN       NOT NULL DEFAULT FALSE,
  files               JSONB         NOT NULL DEFAULT '[]'::JSONB,     -- [{name,url,size,uploaded_at}]
  student_note        TEXT,
  -- grading
  marks               SMALLINT,
  grade               VARCHAR(4),                                     -- 'A+','A','B' etc.
  feedback            TEXT,
  graded_by           UUID          REFERENCES auth.users(id),
  graded_at           TIMESTAMPTZ,
  returned_at         TIMESTAMPTZ,
  -- re-submission
  resubmit_allowed    BOOLEAN       NOT NULL DEFAULT FALSE,
  resubmit_count      SMALLINT      NOT NULL DEFAULT 0,
  created_at          TIMESTAMPTZ   NOT NULL DEFAULT NOW(),
  updated_at          TIMESTAMPTZ   NOT NULL DEFAULT NOW(),
  UNIQUE (assignment_id, student_id)
);

CREATE TABLE learn.certificates (
  id              UUID          PRIMARY KEY DEFAULT uuid_generate_v4(),
  student_id      UUID          NOT NULL REFERENCES auth.users(id),
  course_id       UUID          NOT NULL REFERENCES learn.courses(id),
  enrollment_id   UUID          NOT NULL REFERENCES commerce.enrollments(id),
  status          certificate_status NOT NULL DEFAULT 'not_earned',
  certificate_number VARCHAR(32) UNIQUE,
  issued_at       TIMESTAMPTZ,
  pdf_url         TEXT,
  final_score     NUMERIC(5,2),
  created_at      TIMESTAMPTZ   NOT NULL DEFAULT NOW(),
  UNIQUE (student_id, course_id)
);

--..............................................Comms..........................................................

CREATE TABLE comms.reviews (
  id              UUID          PRIMARY KEY DEFAULT uuid_generate_v4(),
  course_id       UUID          NOT NULL REFERENCES learn.courses(id) ON DELETE CASCADE,
  teacher_id      UUID          NOT NULL REFERENCES auth.users(id),
  student_id      UUID          NOT NULL REFERENCES auth.users(id),
  enrollment_id   UUID          NOT NULL REFERENCES commerce.enrollments(id),
  rating          SMALLINT      NOT NULL CHECK (rating BETWEEN 1 AND 5),
  title           VARCHAR(120),
  body            TEXT,
  is_verified     BOOLEAN       NOT NULL DEFAULT FALSE,               -- verified purchase
  is_featured     BOOLEAN       NOT NULL DEFAULT FALSE,
  is_hidden       BOOLEAN       NOT NULL DEFAULT FALSE,
  helpful_count   INTEGER       NOT NULL DEFAULT 0,
  reply           TEXT,                                               -- teacher reply
  reply_at        TIMESTAMPTZ,
  created_at      TIMESTAMPTZ   NOT NULL DEFAULT NOW(),
  updated_at      TIMESTAMPTZ   NOT NULL DEFAULT NOW(),
  UNIQUE (course_id, student_id)
);

CREATE TABLE comms.announcements (
  id              UUID          PRIMARY KEY DEFAULT uuid_generate_v4(),
  teacher_id      UUID          NOT NULL REFERENCES auth.users(id),
  course_id       UUID          REFERENCES learn.courses(id),         -- NULL = all courses by teacher
  title           VARCHAR(200)  NOT NULL,
  body            TEXT          NOT NULL,
  is_pinned       BOOLEAN       NOT NULL DEFAULT FALSE,
  send_email      BOOLEAN       NOT NULL DEFAULT TRUE,
  send_push       BOOLEAN       NOT NULL DEFAULT TRUE,
  published_at    TIMESTAMPTZ,
  created_at      TIMESTAMPTZ   NOT NULL DEFAULT NOW(),
  updated_at      TIMESTAMPTZ   NOT NULL DEFAULT NOW()
);


 CREATE TABLE comms.notifications (
  id              UUID              PRIMARY KEY DEFAULT uuid_generate_v4(),
  user_id         UUID              NOT NULL REFERENCES auth.users(id) ON DELETE CASCADE,
  type_id         INTEGER           NOT NULL REFERENCES core.notification_type (id),
  title           VARCHAR(200)      NOT NULL,
  body            TEXT,
  data            JSONB             NOT NULL DEFAULT '{}'::JSONB,     -- contextual payload
  action_url      TEXT,
  is_read         BOOLEAN           NOT NULL DEFAULT FALSE,
  read_at         TIMESTAMPTZ,
  sent_at         TIMESTAMPTZ       NOT NULL DEFAULT NOW(),
  -- delivery channels
  email_sent      BOOLEAN           NOT NULL DEFAULT FALSE,
  push_sent       BOOLEAN           NOT NULL DEFAULT FALSE,
  sms_sent        BOOLEAN           NOT NULL DEFAULT FALSE
);

-- Add foreign key constraint for certificate_id in enrollments
ALTER TABLE commerce.enrollments 
  ADD CONSTRAINT fk_certificate 
  FOREIGN KEY (certificate_id) REFERENCES learn.certificates(id);
