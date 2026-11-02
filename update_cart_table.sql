-- Update cart table to include user_id column
-- Run this SQL if your cart table doesn't have user_id column

-- First, check if user_id column exists
-- If not, add it
ALTER TABLE cart ADD COLUMN user_id INT NOT NULL DEFAULT 1;

-- Add foreign key constraint (optional but recommended)
ALTER TABLE cart ADD CONSTRAINT fk_cart_user 
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

-- Update existing cart items to have user_id = 1 (or remove them)
-- DELETE FROM cart WHERE user_id = 1; -- Uncomment this if you want to clear existing cart items

-- Make user_id NOT NULL after adding it
ALTER TABLE cart MODIFY COLUMN user_id INT NOT NULL;

-- Remove the default value
ALTER TABLE cart ALTER COLUMN user_id DROP DEFAULT;
