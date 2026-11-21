-- Apply ONLY to pits_dev_tags_20261121 / a new TAGS database. Never to pits or pits_medica.
CREATE TABLE IF NOT EXISTS pits_inventory_lock (id INT PRIMARY KEY) ENGINE=InnoDB;
INSERT IGNORE INTO pits_inventory_lock VALUES (1);
CREATE TABLE IF NOT EXISTS pits_tag_captures (
 tag VARCHAR(20) NOT NULL PRIMARY KEY, location VARCHAR(200) NOT NULL,
 warehouse VARCHAR(200) NOT NULL, user_id BIGINT NOT NULL,
 captured_at DATETIME NOT NULL, correlation VARCHAR(40) NOT NULL,
 revision INT NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE IF NOT EXISTS pits_tag_events (
 id BIGINT AUTO_INCREMENT PRIMARY KEY, tag VARCHAR(20), user_id BIGINT,
 action VARCHAR(40) NOT NULL, outcome VARCHAR(20) NOT NULL,
 correlation VARCHAR(40) NOT NULL, detail LONGTEXT, created_at DATETIME NOT NULL,
 KEY tag_time (tag,created_at), KEY correlation (correlation)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE IF NOT EXISTS pits_tag_audits (
 id BIGINT AUTO_INCREMENT PRIMARY KEY, tag VARCHAR(20) NOT NULL,
 warehouse VARCHAR(200) NOT NULL, location VARCHAR(200) NOT NULL,
 result VARCHAR(3) NOT NULL, observations TEXT NOT NULL,
 user_id BIGINT NOT NULL, created_at DATETIME NOT NULL, snapshot LONGTEXT NOT NULL,
 KEY tag_time (tag,created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE IF NOT EXISTS pits_tag_corrections (
 id BIGINT AUTO_INCREMENT PRIMARY KEY, tag VARCHAR(20) NOT NULL,
 detail_id BIGINT NOT NULL, user_id BIGINT NOT NULL, reason VARCHAR(500) NOT NULL,
 before_value LONGTEXT NOT NULL, after_value LONGTEXT NOT NULL,
 created_at DATETIME NOT NULL, KEY tag_time(tag,created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
ALTER TABLE tags ADD COLUMN description VARCHAR(200) NULL;
ALTER TABLE tags ADD COLUMN area_description VARCHAR(200) NULL;
ALTER TABLE tags ADD UNIQUE INDEX pits_tag_lookup(tag);
-- A marbete identifies one capture row; reject duplicate source identities before migration.
-- Every table written by TAGS imports is transactional, on the NEW copy only.
ALTER TABLE rawdata ENGINE=InnoDB;

ALTER TABLE rawdata MODIFY material_description VARCHAR(200), MODIFY location VARCHAR(50), MODIFY wh VARCHAR(50);
