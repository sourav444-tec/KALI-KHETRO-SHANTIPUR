CREATE DATABASE IF NOT EXISTS kali_khetro_santipur CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kali_khetro_santipur;

CREATE TABLE IF NOT EXISTS admin_users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(80) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS pujas (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name_en VARCHAR(180) NOT NULL,
  name_bn VARCHAR(180) DEFAULT '',
  description_en TEXT,
  description_bn TEXT,
  category VARCHAR(100) DEFAULT 'Popular',
  address VARCHAR(255) DEFAULT '',
  map_url VARCHAR(1000) DEFAULT '',
  lat DECIMAL(10,7) NULL,
  lng DECIMAL(10,7) NULL,
  years_continued VARCHAR(80) DEFAULT '',
  theme VARCHAR(180) DEFAULT '',
  contact VARCHAR(60) DEFAULT '',
  photo VARCHAR(255) DEFAULT '',
  featured TINYINT(1) DEFAULT 0,
  published TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS services (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name_en VARCHAR(180) NOT NULL,
  name_bn VARCHAR(180) DEFAULT '',
  service_type ENUM('Police','Hospital','Fire Brigade','Railway Station','Ferry Ghat','Bus Stop','Road Access','Other') DEFAULT 'Other',
  address VARCHAR(255) DEFAULT '',
  phone VARCHAR(60) DEFAULT '',
  distance VARCHAR(80) DEFAULT '',
  notes_en TEXT,
  notes_bn TEXT,
  lat DECIMAL(10,7) NULL,
  lng DECIMAL(10,7) NULL,
  puja_id INT UNSIGNED NULL,
  published TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_service_puja FOREIGN KEY (puja_id) REFERENCES pujas(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS emergency_contacts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  label_en VARCHAR(100) NOT NULL,
  label_bn VARCHAR(100) DEFAULT '',
  phone VARCHAR(40) NOT NULL,
  instructions_en VARCHAR(255) DEFAULT '',
  instructions_bn VARCHAR(255) DEFAULT '',
  sort_order INT DEFAULT 0,
  published TINYINT(1) DEFAULT 1
);

CREATE TABLE IF NOT EXISTS partners (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(180) NOT NULL,
  partner_type ENUM('Media Partner','Collaborator','Sponsor') DEFAULT 'Collaborator',
  logo VARCHAR(255) DEFAULT '',
  website VARCHAR(255) DEFAULT '',
  description TEXT,
  sort_order INT DEFAULT 0,
  published TINYINT(1) DEFAULT 1
);

CREATE TABLE IF NOT EXISTS team_members (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(180) NOT NULL,
  role VARCHAR(180) DEFAULT '',
  photo VARCHAR(255) DEFAULT '',
  bio TEXT,
  sort_order INT DEFAULT 0,
  published TINYINT(1) DEFAULT 1
);

CREATE TABLE IF NOT EXISTS feedback (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) DEFAULT '',
  email VARCHAR(180) DEFAULT '',
  message TEXT NOT NULL,
  feedback_type VARCHAR(40) NOT NULL DEFAULT 'Website',
  rating TINYINT UNSIGNED NULL,
  status ENUM('Pending','Reviewed','Resolved') NOT NULL DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS puja_ratings (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  puja_id INT UNSIGNED NOT NULL,
  rating TINYINT UNSIGNED NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  voter_hash CHAR(64) NOT NULL,
  UNIQUE KEY unique_puja_voter (puja_id, voter_hash),
  KEY idx_puja_rating (puja_id),
  CONSTRAINT fk_rating_puja FOREIGN KEY (puja_id) REFERENCES pujas(id) ON DELETE CASCADE,
  CONSTRAINT chk_puja_rating CHECK (rating BETWEEN 1 AND 5)
);

INSERT INTO emergency_contacts (label_en,label_bn,phone,instructions_en,instructions_bn,sort_order)
SELECT * FROM (
 SELECT 'Police','পুলিশ','112','For immediate emergency assistance','জরুরি সহায়তার জন্য',1 UNION ALL
 SELECT 'Ambulance','অ্যাম্বুলেন্স','112','Call and explain the location clearly','ফোন করে অবস্থান স্পষ্টভাবে জানান',2 UNION ALL
 SELECT 'Fire Brigade','দমকল','112','Report the fire and exact location','আগুন ও সঠিক অবস্থান জানান',3
) AS seed
WHERE NOT EXISTS (SELECT 1 FROM emergency_contacts);
