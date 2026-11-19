ALTER TABLE `orders`
  MODIFY COLUMN `status` enum('pending_payment','processing','shipped','delivered','payment_rejected') NOT NULL DEFAULT 'pending_payment',
  ADD COLUMN IF NOT EXISTS `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  ADD COLUMN IF NOT EXISTS `payment_slip_path` varchar(255) DEFAULT NULL;

DROP TABLE IF EXISTS `wallet_transactions`;
