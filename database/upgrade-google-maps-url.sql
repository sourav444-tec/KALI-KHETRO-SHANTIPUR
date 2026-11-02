-- Run this once in phpMyAdmin after selecting the kali_khetro_santipur database.
-- Adds a Google Maps link field to existing Puja records.
ALTER TABLE pujas ADD COLUMN map_url VARCHAR(1000) DEFAULT '' AFTER address;
