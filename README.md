# CryptoTrade

CryptoTrade is a full-stack cryptocurrency trading simulation platform built in core PHP, integrating the CoinGecko API for live market data and Stripe (test mode) for simulated deposits

The platform allows users to deposit funds, buy and sell cryptocurrencies in real time, track wallet balances, and monitor transaction history with persistent database storage

---

# Project Overview

CryptoTrade simulates a simplified crypto exchange environment where users can:

- Register and authenticate
- Deposit funds (Stripe Test Mode)
- Buy cryptocurrencies at real-time market price
- Sell holdings instantly
- Track wallet balances
- View complete transaction history
- Trigger price alerts

This project focuses heavily on backend financial logic, state management, and API integration

---

# Tech Stack

- PHP (Core PHP)
- MySQL
- CoinGecko Public API
- Stripe PHP SDK
- AJAX / Fetch API
- Bootstrap
- MVC-inspired structure
- Composer (vendor dependencies)

---

# External APIs

## CoinGecko API

Used to fetch real-time cryptocurrency prices

## Stripe API (Test Mode)

Used to simulate fiat deposits into the user wallet

---

# Core Features

## User Authentication

- Secure login system
- Password hashing
- Session management

## Deposit System

- Stripe Test Mode integration
- Payment confirmation
- Wallet balance auto-update
- Payment history tracking

## Buy Crypto

- Real-time price fetched from CoinGecko
- Fiat balance validation
- Crypto wallet update
- Transaction recorded

## Sell Crypto

- Real-time valuation
- Crypto balance validation
- Fiat wallet update
- Transaction recorded

## Wallet System

Tracks:

- Fiat balance (USDT / USD)
- Individual crypto holdings
- Transaction history
- Deposit history

## Price Alerts

- Store target price in database
- Trigger notification logic
- Persistent after refresh
