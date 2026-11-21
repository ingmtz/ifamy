-- Apply ONLY to pits_dev_hus_20261121 / a new HUS database. Never to pits or pits_medica.
CREATE TABLE IF NOT EXISTS pits_inventory_lock (id INT PRIMARY KEY) ENGINE=InnoDB;
INSERT IGNORE INTO pits_inventory_lock VALUES (1);
CREATE TABLE IF NOT EXISTS pits_hu_captures (
 hu VARCHAR(20) NOT NULL PRIMARY KEY, location VARCHAR(200) NOT NULL,
 warehouse VARCHAR(200) NOT NULL, user_id BIGINT NOT NULL,
 captured_at DATETIME NOT NULL, correlation VARCHAR(40) NOT NULL,
 revision INT NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE IF NOT EXISTS pits_hu_events (
 id BIGINT AUTO_INCREMENT PRIMARY KEY, hu VARCHAR(20), user_id BIGINT,
 action VARCHAR(40) NOT NULL, outcome VARCHAR(20) NOT NULL,
 correlation VARCHAR(40) NOT NULL, detail LONGTEXT, created_at DATETIME NOT NULL,
 KEY hu_time (hu,created_at), KEY correlation (correlation)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE IF NOT EXISTS pits_hu_audits (
 id BIGINT AUTO_INCREMENT PRIMARY KEY, hu VARCHAR(20) NOT NULL,
 warehouse VARCHAR(200) NOT NULL, location VARCHAR(200) NOT NULL,
 result VARCHAR(3) NOT NULL, observations TEXT NOT NULL,
 user_id BIGINT NOT NULL, created_at DATETIME NOT NULL, snapshot LONGTEXT NOT NULL,
 KEY hu_time (hu,created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
CREATE TABLE IF NOT EXISTS pits_hu_corrections (
 id BIGINT AUTO_INCREMENT PRIMARY KEY, hu VARCHAR(20) NOT NULL,
 detail_id BIGINT NOT NULL, user_id BIGINT NOT NULL, reason VARCHAR(500) NOT NULL,
 before_value LONGTEXT NOT NULL, after_value LONGTEXT NOT NULL,
 created_at DATETIME NOT NULL, KEY hu_time(hu,created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
ALTER TABLE hus ADD COLUMN description VARCHAR(200) NULL;
ALTER TABLE hus ADD COLUMN area_description VARCHAR(200) NULL;
ALTER TABLE hus ADD INDEX pits_hu_lookup(tag);
-- Retain detail rows: no UNIQUE on hus.tag.
-- Every table written by HUS imports is transactional, on the NEW copy only.
ALTER TABLE rawdata ENGINE=InnoDB;
