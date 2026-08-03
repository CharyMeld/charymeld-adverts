# Webhook Examples

## Paystack
- Configure Paystack dashboard to send webhooks to: `https://your-domain.com/webhook/paystack`
- Verify signature header `x-paystack-signature` (optional but recommended).
- Paystack will send events like `charge.success`. Validate transaction reference and amount via Paystack API before updating order/ad status.

## Flutterwave
- Configure webhook endpoint: `https://your-domain.com/webhook/flutterwave`
- Flutterwave posts a transaction object to the endpoint. Verify via Flutterwave's verify endpoint or validate payload using your secret.
