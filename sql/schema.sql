-- =====================================================================
--  AlphaEdge · Investment Analyzer · Database Schema
--  Import this file via phpMyAdmin (see instructions below).
-- =====================================================================

CREATE DATABASE IF NOT EXISTS investment_analyzer
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE investment_analyzer;

-- ---------------------------------------------------------------------
--  Users
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username       VARCHAR(50)  NOT NULL UNIQUE,
  email          VARCHAR(120) NOT NULL UNIQUE,
  password_hash  VARCHAR(255) NOT NULL,
  cash_balance   DECIMAL(18,8) NOT NULL DEFAULT 100000.00000000,
  is_admin       TINYINT(1)   NOT NULL DEFAULT 0,
  created_at     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
--  Holdings  (one row per user per asset)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS holdings (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id    INT UNSIGNED NOT NULL,
  symbol     VARCHAR(20)  NOT NULL,
  quantity   DECIMAL(18,8) NOT NULL DEFAULT 0,
  avg_price  DECIMAL(18,8) NOT NULL DEFAULT 0,
  created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_user_symbol (user_id, symbol),
  CONSTRAINT fk_holdings_user FOREIGN KEY (user_id)
      REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
--  Trades  (immutable log of every buy/sell)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS trades (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id     INT UNSIGNED NOT NULL,
  symbol      VARCHAR(20)  NOT NULL,
  side        ENUM('BUY','SELL') NOT NULL,
  quantity    DECIMAL(18,8) NOT NULL,
  price       DECIMAL(18,8) NOT NULL,
  total       DECIMAL(18,8) NOT NULL,
  pnl         DECIMAL(18,8) NULL,
  created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_trades_user FOREIGN KEY (user_id)
      REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_trades_user_created (user_id, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
--  Cash transactions (deposit / withdrawal log — virtual only)
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS cash_transactions (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id     INT UNSIGNED NOT NULL,
  type        ENUM('DEPOSIT','WITHDRAW','ADJUSTMENT') NOT NULL,
  amount      DECIMAL(18,8) NOT NULL,
  note        VARCHAR(255) NULL,
  created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_cash_user FOREIGN KEY (user_id)
      REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_cash_user_created (user_id, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;