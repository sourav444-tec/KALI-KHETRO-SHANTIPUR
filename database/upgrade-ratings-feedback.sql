USE kali_khetro_santipur;
ALTER TABLE feedback ADD COLUMN feedback_type VARCHAR(40) NOT NULL DEFAULT 'Website';
ALTER TABLE feedback ADD COLUMN rating TINYINT UNSIGNED NULL;
ALTER TABLE feedback ADD COLUMN status ENUM('Pending','Reviewed','Resolved') NOT NULL DEFAULT 'Pending';
CREATE TABLE IF NOT EXISTS puja_ratings (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  puja_id INT UNSIGNED NOT NULL, rating TINYINT UNSIGNED NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, voter_hash CHAR(64) NOT NULL,
  UNIQUE KEY unique_puja_voter (puja_id, voter_hash), KEY idx_puja_rating (puja_id),
  CONSTRAINT fk_rating_puja FOREIGN KEY (puja_id) REFERENCES pujas(id) ON DELETE CASCADE
);
