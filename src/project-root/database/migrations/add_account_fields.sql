-- Add new columns to accounts table
ALTER TABLE accounts
ADD COLUMN username VARCHAR(255) NOT NULL AFTER title,
ADD COLUMN password VARCHAR(255) NOT NULL AFTER username,
ADD COLUMN basic_description TEXT AFTER password,
ADD COLUMN detailed_description TEXT AFTER basic_description,
MODIFY COLUMN description TEXT NULL; 