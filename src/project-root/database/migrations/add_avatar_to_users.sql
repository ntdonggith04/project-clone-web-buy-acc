ALTER TABLE users
ADD COLUMN avatar VARCHAR(255) DEFAULT '/images/avatars/default.png';

-- Update existing users to have the default avatar
UPDATE users SET avatar = '/images/avatars/default.png' WHERE avatar IS NULL; 