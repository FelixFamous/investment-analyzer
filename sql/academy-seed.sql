INSERT INTO academy_courses (stage, title, description, icon, sort_order) VALUES
('basic', 'What Are Financial Markets?', 'Beginner tour of stocks, crypto, and how trading works.', '🌐', 1),
('basic', 'How to Read Candlestick Charts', 'Learn the most important chart in trading.', '🕯️', 2),
('basic', 'Crypto vs Stocks vs Indices', 'Differences, risks, and opportunities.', '⚖️', 3),
('basic', 'Your First Paper Trade', 'Execute your first trade step-by-step.', '💵', 4),
('intermediate', 'Support, Resistance & Market Structure', 'Where price reacts and why.', '📐', 1),
('intermediate', 'Technical Indicators Explained', 'RSI, MACD, Bollinger, and moving averages.', '📊', 2),
('intermediate', 'Risk Management Fundamentals', 'Position sizing, stop-losses, R:R ratios.', '🛡️', 3),
('intermediate', 'Psychology of Trading', 'Emotions, discipline, and habits of good traders.', '🧠', 4),
('advanced', 'Advanced Chart Patterns', 'Head & shoulders, wedges, flags, triangles.', '📈', 1),
('advanced', 'Multi-Timeframe Analysis', 'H4 → H1 → M15 entry timing.', '🕰️', 2),
('advanced', 'Order Flow & Microstructure', 'Order books, volume, and large orders.', '📖', 3),
('advanced', 'Portfolio Management & Correlation', 'Diversification and risk-adjusted sizing.', '🧺', 4),
('strategies', 'Trend Following Strategies', 'SMA crossovers and breakout riding.', '🚀', 1),
('strategies', 'Mean Reversion Strategies', 'Buying dips and fading overextensions.', '🔄', 2),
('strategies', 'Breakout & Momentum Strategies', 'Range breakouts with volume confirmation.', '⚡', 3),
('strategies', 'Build & Backtest Your Own Strategy', 'Design, test, and refine a strategy.', '🧪', 4);

-- Lessons (2 per course)
INSERT INTO academy_lessons (course_id, title, content, video_url, sort_order) 
SELECT id, 'Introduction', 'Welcome to this course. In this lesson we cover the fundamentals.', 'https://www.youtube.com/results?search_query=financial+markets+explained', 1 FROM academy_courses;

INSERT INTO academy_lessons (course_id, title, content, video_url, sort_order) 
SELECT id, 'Key Concepts', 'This lesson dives into the practical details. Take notes and revisit often.', 'https://www.youtube.com/results?search_query=technical+analysis+basics', 2 FROM academy_courses;

-- Badges
INSERT INTO academy_badges (name, icon, description) VALUES
('First Steps', '🐣', 'Completed your first lesson'),
('Student', '📖', 'Completed 5 lessons'),
('Scholar', '📚', 'Completed 15 lessons'),
('Basic Graduate', '🎓', 'Finished the Basic stage'),
('Intermediate Pro', '🎯', 'Finished the Intermediate stage'),
('Advanced Master', '🏆', 'Finished the Advanced stage'),
('Strategist', '⚔️', 'Finished the Strategies stage'),
('Perfect Student', '💎', 'Completed every lesson'),
('First Trade', '💰', 'Placed your first trade'),
('Verified', '🪪', 'Completed KYC verification'),
('Secured', '🔐', 'Enabled 2FA'),
('Diversified', '🧺', 'Held 5+ positions at once');