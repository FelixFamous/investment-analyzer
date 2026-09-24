\# AlphaEdge · Investment Analyzer (Paper Trading Demo)



A full-stack PHP investment analyzer with AI-style market signals, a simulated

trading engine, live portfolio tracking, and an admin dashboard.



> ⚠️ \*\*Educational demo only.\*\* No real money is handled anywhere in this project.

> All "deposits", "withdrawals", and "trades" are virtual.



\---



\## Features



\- \*\*User accounts\*\* — register, log in, log out, session management, CSRF protection, bcrypt password hashing.

\- \*\*Signal engine\*\* — RSI, SMA (20/50), momentum, and ATR combined into a probability score for each tracked asset.

\- \*\*Trading simulation\*\* — buy and sell assets; positions tracked with weighted-average cost; P\&L computed on every sell.

\- \*\*Live portfolio dashboard\*\* — cash, holdings value, total equity, and total P\&L update on every page load.

\- \*\*Deposit / Withdraw\*\* — virtual fund movements (logged in `cash\_transactions`).

\- \*\*Trade history\*\* — full log of every BUY / SELL with realized P\&L.

\- \*\*Admin page\*\* — lists every user, their cash, holdings value, equity, and role.

\- \*\*Real market data (optional)\*\* — plug a free \[Finnhub](https://finnhub.io) API key into `config/config.php` to switch from simulated prices to live quotes.



\---



\## Tech Stack



\- \*\*PHP 8.2\*\* (no framework, no Composer)

\- \*\*MySQL / MariaDB\*\* via PDO

\- \*\*Vanilla JavaScript\*\* for modals and fetch calls

\- \*\*Vanilla CSS\*\* — dark, minimal theme

\- \*\*Apache\*\* (XAMPP for local dev)



No Node, no npm, no build step. Drop it into `htdocs` and it runs.



\---



\## Setup (Local — XAMPP on Windows)



1\. \*\*Install XAMPP\*\* — https://www.apachefriends.org

2\. \*\*Start Apache and MySQL\*\* in the XAMPP Control Panel

3\. \*\*Copy the project\*\* into `C:\\xampp\\htdocs\\investment-analyzer\\`

4\. \*\*Create the database\*\*:

&#x20;  - Open http://localhost/phpmyadmin

&#x20;  - Click \*\*Import\*\*

&#x20;  - Choose `sql/schema.sql`

&#x20;  - Click \*\*Go\*\*

5\. \*\*Edit `config/config.php`\*\* if needed:

&#x20;  - `DB\_USER` / `DB\_PASS` — defaults are `root` / empty (XAMPP)

&#x20;  - `APP\_URL` — defaults to `http://localhost/investment-analyzer`

&#x20;  - `FINNHUB\_API\_KEY` — optional, for live prices

6\. \*\*Open\*\* http://localhost/investment-analyzer/

7\. \*\*Register\*\* an account — you start with \*\*$100,000 virtual\*\*

8\. \*\*(Optional) Make yourself admin\*\* — run in phpMyAdmin:

&#x20;  ```sql

&#x20;  UPDATE users SET is\_admin = 1 WHERE username = 'your\_username';

