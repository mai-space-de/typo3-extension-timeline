CREATE TABLE tx_timeline_domain_model_timelineentry (
    year int DEFAULT 0 NOT NULL,
    title varchar(255) DEFAULT '' NOT NULL,
    description text,
    media int unsigned DEFAULT 0 NOT NULL,
    categories int unsigned DEFAULT 0 NOT NULL
);
