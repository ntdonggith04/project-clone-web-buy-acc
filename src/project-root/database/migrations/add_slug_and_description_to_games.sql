-- Add new columns to games table
ALTER TABLE games
ADD COLUMN slug VARCHAR(255) AFTER name,
ADD COLUMN short_description TEXT AFTER slug;

-- Update existing records to have default values
UPDATE games 
SET slug = LOWER(REPLACE(name, ' ', '-')),
    short_description = COALESCE(LEFT(description, 255), 'No description available');

-- Add unique constraint to slug
ALTER TABLE games
ADD UNIQUE INDEX idx_games_slug (slug); 