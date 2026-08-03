# 💳 Payment Gateway Setup Guide

This guide will help you set up Paystack and Flutterwave to receive payments directly into your bank account.

---

## 🎯 Overview

When users pay for adverts on your platform:
1. User selects a plan (Regular/Featured/Premium)
2. User pays through Paystack or Flutterwave
3. Payment gateway processes the payment
4. Money is automatically settled to your registered bank account
5. Your system activates the user's advert

---

## 📝 Step 1: Create Paystack Account

### 1.1 Sign Up
- Visit: https://paystack.com
- Click "Create Free Account"
- Use your business email address

### 1.2 Complete Business Verification (KYC)
Required information:
- ✅ Business Name: "CharyMeld Adverts" (or your registered name)
- ✅ Business Type: Select appropriate type
- ✅ Business Address
- ✅ CAC Registration Number (if incorporated) or RC Number
- ✅ Bank Account Details:
  - Bank Name
  - Account Number
  - Account Name (must match business name)
- ✅ ID Verification:
  - BVN (Bank Verification Number)
  - NIN (National ID Number), or
  - Driver's License
- ✅ Business Directors' Information

### 1.3 Get API Keys

**For Testing (Sandbox):**
1. Login to Paystack Dashboard: https://dashboard.paystack.com
2. Go to: **Settings** → **API Keys & Webhooks**
3. Copy your **Test Keys**:
   - Test Public Key: `pk_test_xxxxxxxxxxxxx`
   - Test Secret Key: `sk_test_xxxxxxxxxxxxx`

**For Production (Live):**
1. After KYC approval, toggle to "Live" mode
2. Copy your **Live Keys**:
   - Live Public Key: `pk_live_xxxxxxxxxxxxx`
   - Live Secret Key: `sk_live_xxxxxxxxxxxxx`

### 1.4 Paystack Settlement Details
- **Settlement Time**: T+1 (Next business day)
- **Transaction Fee**: 1.5% + ₦100 (capped at ₦2,000 per transaction)
- **Settlement Account**: Automatically paid to your registered bank account
- **Payout Schedule**: Automatic daily settlements

**Example Calculation:**
- Customer pays: ₦5,000
- Paystack fee: (5,000 × 1.5%) + ₦100 = ₦75 + ₦100 = ₦175
- You receive: ₦5,000 - ₦175 = **₦4,825**

---

## 🌊 Step 2: Create Flutterwave Account

### 2.1 Sign Up
- Visit: https://flutterwave.com/ng
- Click "Get Started"
- Complete registration

### 2.2 Business Verification
Required documents:
- ✅ CAC Certificate (Business Registration)
- ✅ Utility Bill (Business Address)
- ✅ Valid ID (Director's ID)
- ✅ Bank Account Details

### 2.3 Get API Keys
1. Login to Dashboard: https://dashboard.flutterwave.com
2. Go to: **Settings** → **API**
3. Copy your keys:
   - Public Key: `FLWPUBK-xxxxxxxxxxxxx`
   - Secret Key: `FLWSECK-xxxxxxxxxxxxx`
   - Encryption Key: `FLWSECK_TESTxxxxx`

### 2.4 Flutterwave Settlement
- **Settlement Time**: T+1 (Next business day)
- **Transaction Fee**: 1.4% for local cards
- **Minimum Payout**: No minimum
- **Settlement**: Automatic to registered bank account

**Example Calculation:**
- Customer pays: ₦5,000
- Flutterwave fee: 5,000 × 1.4% = ₦70
- You receive: ₦5,000 - ₦70 = **₦4,930**

---

## ⚙️ Step 3: Configure Your Application

### 3.1 Update Environment Variables

Open your `.env` file and add your API keys:

```env
# Paystack Configuration (Use TEST keys for testing)
PAYSTACK_PUBLIC_KEY=pk_test_xxxxxxxxxxxxxxxxxxxxx
PAYSTACK_SECRET_KEY=sk_test_xxxxxxxxxxxxxxxxxxxxx
PAYSTACK_PAYMENT_URL=https://api.paystack.co

# Flutterwave Configuration (Use TEST keys for testing)
FLUTTERWAVE_PUBLIC_KEY=FLWPUBK_TEST-xxxxxxxxxxxxxxxxxxxxx
FLUTTERWAVE_SECRET_KEY=FLWSECK-xxxxxxxxxxxxxxxxxxxxx
FLUTTERWAVE_SECRET_HASH=your_secret_hash_here
FLUTTERWAVE_PAYMENT_URL=https://api.flutterwave.com/v3
```

### 3.2 Test vs Live Keys

**For Development/Testing:**
- Use **TEST** keys from both gateways
- Use test card numbers provided by Paystack/Flutterwave
- No real money is charged

**For Production:**
- Replace with **LIVE** keys
- Real transactions will be processed
- Money will be settled to your bank account

### 3.3 Paystack Test Cards

```
Card Number: 4084084084084081
CVV: 408
Expiry: Any future date
PIN: 0000
OTP: 123456
```

### 3.4 Flutterwave Test Cards

```
Card Number: 5531886652142950
CVV: 564
Expiry: 09/32
PIN: 3310
OTP: 12345
```

---

## 🔐 Step 4: Set Up Webhooks (Important!)

Webhooks ensure your system is notified when payments succeed, even if user closes browser.

### 4.1 Paystack Webhook
1. Go to: **Settings** → **API Keys & Webhooks**
2. Set Webhook URL: `https://charymeld-adverts.teamodigitalsolutions.com/webhook/paystack`
3. Copy the webhook secret
4. Add to `.env`:
   ```env
   PAYSTACK_WEBHOOK_SECRET=sk_xxxxxxxxxxxxx
   ```

### 4.2 Flutterwave Webhook
1. Go to: **Settings** → **Webhooks**
2. Set Webhook URL: `https://charymeld-adverts.teamodigitalsolutions.com/webhook/flutterwave`
3. Set Secret Hash (same as FLUTTERWAVE_SECRET_HASH)

---

## 💰 Step 5: Understanding Money Flow

### 5.1 Transaction Flow
```
Customer (₦5,000)
    ↓
Payment Gateway (Paystack/Flutterwave)
    ↓ (deduct fees)
Your Bank Account (₦4,825 - ₦4,930)
```

### 5.2 Settlement Schedule
- **Paystack**: Settles at 12:00 PM daily (T+1)
- **Flutterwave**: Settles at 11:00 AM daily (T+1)
- **Weekends/Holidays**: Settlement on next business day

### 5.3 Where Money Goes
1. **Initial Payment**: Customer → Payment Gateway
2. **Settlement**: Payment Gateway → Your Bank Account (automatically)
3. **Viewing Settlements**:
   - Paystack: Dashboard → Transactions → Settlements
   - Flutterwave: Dashboard → Settlements

---

## 🧪 Step 6: Testing Payment Flow

### 6.1 Test Mode Setup
1. Use TEST API keys in `.env`
2. Create a test advert
3. Select a pricing plan
4. Choose payment gateway
5. Use test card details
6. Complete payment

### 6.2 Verify Transaction
- Check: `/admin/transactions` in your app
- Check: Paystack/Flutterwave dashboard
- Verify: Advert is activated

### 6.3 Go Live Checklist
- [ ] Business verification approved
- [ ] Bank account verified
- [ ] Test transactions successful
- [ ] Replace TEST keys with LIVE keys
- [ ] Update webhook URLs to production domain
- [ ] Test one live transaction with small amount

---

## 📊 Step 7: Monitoring Payments

### 7.1 In Your Application
- Admin Dashboard: `/admin/transactions`
- View all payments, statuses, and details

### 7.2 In Payment Gateway Dashboards

**Paystack Dashboard:**
- Transactions: See all payments
- Settlements: See bank transfers
- Customers: Customer database
- Disputes: Handle chargebacks

**Flutterwave Dashboard:**
- Transactions: Payment history
- Settlements: Payout history
- Analytics: Revenue reports

---

## ⚠️ Important Notes

### 7.1 Security
- ✅ Never commit API keys to Git
- ✅ Always use HTTPS in production
- ✅ Verify webhook signatures
- ✅ Keep secret keys secure

### 7.2 Compliance
- ✅ Complete KYC verification
- ✅ Maintain accurate business records
- ✅ Issue receipts to customers
- ✅ Comply with tax regulations

### 7.3 Customer Support
- Both gateways provide 24/7 support
- **Paystack**: support@paystack.com
- **Flutterwave**: support@flutterwave.com

---

## 🚀 Quick Start Commands

After adding API keys to `.env`:

```bash
# Clear config cache
php artisan config:clear

# Test payment integration
php artisan tinker
>>> env('PAYSTACK_SECRET_KEY')
>>> env('FLUTTERWAVE_SECRET_KEY')
```

---

## 📞 Need Help?

### Paystack Support
- Email: support@paystack.com
- Phone: +234 1 888 3664
- Docs: https://paystack.com/docs

### Flutterwave Support
- Email: support@flutterwave.com
- Phone: +234 1 888 3320
- Docs: https://developer.flutterwave.com

---

## 💡 Tips for Success

1. **Start with Test Mode**: Always test thoroughly before going live
2. **Monitor Transactions**: Check dashboards daily
3. **Handle Failures**: Implement retry logic for failed payments
4. **Customer Communication**: Send email confirmations
5. **Reconciliation**: Match gateway settlements with your records
6. **Backup Gateway**: Having both Paystack and Flutterwave gives redundancy

---

## ✅ Current Configuration Status

Your application is configured for:
- ✅ Paystack integration (needs API keys)
- ✅ Flutterwave integration (needs API keys)
- ✅ Payment plan selection (Regular/Featured/Premium)
- ✅ Gateway selection UI
- ✅ Webhook handlers
- ✅ Transaction tracking

**Next Step**: Add your API keys to `.env` file!

---

## 🎉 You're Ready!

Once you add your API keys, payments will flow directly to your bank account automatically!

Good luck with your marketplace! 🚀
